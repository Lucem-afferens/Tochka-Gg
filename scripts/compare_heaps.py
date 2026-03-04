#!/usr/bin/env python3
"""
Сравнение двух heap snapshot'ов (до и после).
Показывает прирост по объектам и памяти, главные «виновники» тормозов.
Запуск: python scripts/compare_heaps.py before.heapsnapshot after.heapsnapshot
"""
import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
from heap_snapshot_parser import (
    load_snapshot,
    get_node_count,
    total_self_size,
    aggregate_by_type,
    aggregate_by_name,
)


def fmt_size(n: int) -> str:
    if n >= 1024 * 1024:
        return f"{n / (1024*1024):.2f} MB"
    if n >= 1024:
        return f"{n / 1024:.2f} KB"
    return f"{n} B"


def main() -> None:
    if len(sys.argv) < 3:
        print("Usage: python compare_heaps.py <before.heapsnapshot> <after.heapsnapshot>")
        sys.exit(1)
    path_before = sys.argv[1]
    path_after = sys.argv[2]
    print("Loading BEFORE:", path_before)
    before = load_snapshot(path_before)
    print("Loading AFTER:", path_after)
    after = load_snapshot(path_after)
    print()

    nodes_before = get_node_count(before)
    nodes_after = get_node_count(after)
    size_before = total_self_size(before)
    size_after = total_self_size(after)
    delta_nodes = nodes_after - nodes_before
    delta_size = size_after - size_before

    print("=" * 70)
    print("СРАВНЕНИЕ ДО / ПОСЛЕ")
    print("=" * 70)
    print(f"  Узлов:        {nodes_before:,}  →  {nodes_after:,}   (Δ {delta_nodes:+,})")
    print(f"  Self-size:    {fmt_size(size_before)}  →  {fmt_size(size_after)}   (Δ {fmt_size(delta_size)})")
    print()

    print("ПРИРОСТ ПО ТИПАМ ОБЪЕКТОВ (что больше всего выросло)")
    print("-" * 70)
    by_type_b = aggregate_by_type(before)
    by_type_a = aggregate_by_type(after)
    type_deltas = []
    all_types = set(by_type_b) | set(by_type_a)
    for t in all_types:
        count_b = by_type_b.get(t, {}).get("count", 0)
        count_a = by_type_a.get(t, {}).get("count", 0)
        size_b = by_type_b.get(t, {}).get("self_size", 0)
        size_a = by_type_a.get(t, {}).get("self_size", 0)
        type_deltas.append((t, count_a - count_b, size_a - size_b, count_a, size_a))
    for t, d_count, d_size, count_a, size_a in sorted(type_deltas, key=lambda x: -x[2])[:20]:
        if d_size == 0 and d_count == 0:
            continue
        print(f"  {t:25}  Δcount: {d_count:>+8,}   Δsize: {fmt_size(d_size):>12}   (после: {count_a:,} шт., {fmt_size(size_a)})")
    print()

    print("ПРИРОСТ ПО КОНСТРУКТОРАМ/КЛАССАМ (топ виновников «тяжести»)")
    print("-" * 70)
    by_name_b = aggregate_by_name(before, min_self_size=0)
    by_name_a = aggregate_by_name(after, min_self_size=0)
    name_deltas = []
    all_names = set(by_name_b) | set(by_name_a)
    for name in all_names:
        count_b = by_name_b.get(name, {}).get("count", 0)
        count_a = by_name_a.get(name, {}).get("count", 0)
        size_b = by_name_b.get(name, {}).get("self_size", 0)
        size_a = by_name_a.get(name, {}).get("self_size", 0)
        d_count = count_a - count_b
        d_size = size_a - size_b
        if d_size == 0 and d_count == 0:
            continue
        name_deltas.append((name, d_count, d_size, count_a, size_a))
    for name, d_count, d_size, count_a, size_a in sorted(name_deltas, key=lambda x: -x[2])[:35]:
        short = (name[:48] + "…") if len(name) > 48 else name
        print(f"  {short:50}  Δ {d_count:>+6} шт.  Δ {fmt_size(d_size):>10}")
    print()

    print("ВЫВОД: что делает сайт тяжёлым/тормозным")
    print("-" * 70)
    bullets = []
    if delta_nodes > 10000:
        bullets.append(f"• Сильный прирост числа объектов (+{delta_nodes:,}) — вероятно накопление без очистки (утечки, кеши, подписки).")
    if delta_size > 5 * 1024 * 1024:
        bullets.append(f"• Большой прирост памяти (+{fmt_size(delta_size)}) — ищите выше в списке конструкторов с наибольшим Δsize.")
    top_type = max(type_deltas, key=lambda x: x[2])
    if top_type[2] > 1024 * 1024:
        bullets.append(f"• По типам больше всего вырос «{top_type[0]}» (+{fmt_size(top_type[2])}) — смотрите объекты этого типа в DevTools.")
    top_name = max(name_deltas, key=lambda x: x[2]) if name_deltas else None
    if top_name and top_name[2] > 500 * 1024:
        bullets.append(f"• По конструкторам лидирует «{top_name[0][:50]}» (+{fmt_size(top_name[2])}) — вероятный кандидат на оптимизацию.")
    if not bullets:
        bullets.append("• Прирост небольшой; тормоза могут быть из-за CPU (много скриптов/анимаций) или layout, а не только heap.")
    for b in bullets:
        print(b)
    print()


if __name__ == "__main__":
    main()
