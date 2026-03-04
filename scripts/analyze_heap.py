#!/usr/bin/env python3
"""
Анализ одного heap snapshot: что занимает память и много ли объектов.
Запуск: python scripts/analyze_heap.py path/to/file.heapsnapshot
       или с путём вида "Heap-...:before.heapsnapshot"
"""
import sys
from pathlib import Path

# поддержка вызова из корня проекта
sys.path.insert(0, str(Path(__file__).resolve().parent))
from heap_snapshot_parser import (
    load_snapshot,
    get_node_count,
    iter_nodes,
    aggregate_by_type,
    aggregate_by_name,
    aggregate_strings,
    total_self_size,
    NODE_TYPE_NAMES,
)


def fmt_size(n: int) -> str:
    if n >= 1024 * 1024:
        return f"{n / (1024*1024):.2f} MB"
    if n >= 1024:
        return f"{n / 1024:.2f} KB"
    return f"{n} B"


def main() -> None:
    if len(sys.argv) < 2:
        print("Usage: python analyze_heap.py <path-to.heapsnapshot>")
        sys.exit(1)
    path = sys.argv[1]
    print(f"Loading: {path}")
    data = load_snapshot(path)
    node_count = get_node_count(data)
    total = total_self_size(data)
    print()
    print("=" * 60)
    print("ОБЩАЯ СТАТИСТИКА")
    print("=" * 60)
    print(f"  Узлов (объектов): {node_count:,}")
    print(f"  Суммарный self_size: {fmt_size(total)}")
    print()

    print("ПО ТИПАМ ОБЪЕКТОВ (память)")
    print("-" * 60)
    by_type = aggregate_by_type(data)
    for t in sorted(by_type.keys(), key=lambda x: by_type[x]["self_size"], reverse=True):
        info = by_type[t]
        if info["self_size"] == 0:
            continue
        print(f"  {t:25}  {info['count']:>8,} шт.   {fmt_size(info['self_size']):>12}")
    print()

    print("ТОП-30 КОНСТРУКТОРОВ/КЛАССОВ ПО ПАМЯТИ (self_size)")
    print("-" * 60)
    by_name = aggregate_by_name(data, min_self_size=1000)
    for name, info in sorted(by_name.items(), key=lambda x: x[1]["self_size"], reverse=True)[:30]:
        short = (name[:52] + "…") if len(name) > 52 else name
        print(f"  {short:54}  {info['count']:>6} шт.  {fmt_size(info['self_size']):>10}")
    print()

    print("ТОП-20 КОНСТРУКТОРОВ ПО КОЛИЧЕСТВУ ОБЪЕКТОВ")
    print("-" * 60)
    by_name_count = aggregate_by_name(data, min_self_size=0)
    for name, info in sorted(by_name_count.items(), key=lambda x: x[1]["count"], reverse=True)[:20]:
        short = (name[:52] + "…") if len(name) > 52 else name
        print(f"  {short:54}  {info['count']:>6} шт.  {fmt_size(info['self_size']):>10}")
    print()

    print("ТОП-15 СТРОК ПО ПАМЯТИ (string / concatenated / sliced)")
    print("-" * 60)
    by_str = aggregate_strings(data)
    for key, info in sorted(by_str.items(), key=lambda x: x[1]["self_size"], reverse=True)[:15]:
        short = (key[:52] + "…") if len(key) > 52 else key
        print(f"  {short:54}  {info['count']:>6} шт.  {fmt_size(info['self_size']):>10}")
    print()

    # Детект "тяжёлых" признаков
    print("ПРИЗНАКИ «ТЯЖЕСТИ» САЙТА")
    print("-" * 60)
    by_type = aggregate_by_type(data)
    strings_size = by_type.get("string", {}).get("self_size", 0) + \
                   by_type.get("concatenated string", {}).get("self_size", 0) + \
                   by_type.get("sliced string", {}).get("self_size", 0)
    objects_count = by_type.get("object", {}).get("count", 0)
    arrays_count = by_type.get("array", {}).get("count", 0)
    closures_count = by_type.get("closure", {}).get("count", 0)
    code_size = by_type.get("code", {}).get("self_size", 0)

    tips = []
    if strings_size > 5 * 1024 * 1024:
        tips.append(f"• Много строк в памяти: {fmt_size(strings_size)} — возможны утечки текста/кеша")
    if objects_count > 50000:
        tips.append(f"• Очень много JS-объектов: {objects_count:,} — тяжёлый DOM/виджеты/плагины")
    if arrays_count > 20000:
        tips.append(f"• Много массивов: {arrays_count:,} — проверьте списки и коллекции")
    if closures_count > 10000:
        tips.append(f"• Много замыканий: {closures_count:,} — возможно много обработчиков/колбэков")
    if code_size > 2 * 1024 * 1024:
        tips.append(f"• Код (JIT): {fmt_size(code_size)} — много скомпилированного кода")

    if tips:
        for t in tips:
            print(t)
    else:
        print("  Явных аномалий по типам не видно; смотрите сравнение до/после.")
    print()


if __name__ == "__main__":
    main()
