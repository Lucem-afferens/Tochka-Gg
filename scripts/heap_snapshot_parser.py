"""
Парсер Chrome/V8 heap snapshot (.heapsnapshot).
Формат: JSON с ключами snapshot (meta), nodes, edges, strings.
"""
import json
from pathlib import Path
from collections import defaultdict
from typing import Any


# Поля узла из meta.node_fields
NODE_FIELDS = ["type", "name", "id", "self_size", "edge_count", "detachedness"]
STRIDE = len(NODE_FIELDS)

# Типы узлов из meta.node_types[0]
NODE_TYPE_NAMES = [
    "hidden", "array", "string", "object", "code", "closure",
    "regexp", "number", "native", "synthetic", "concatenated string",
    "sliced string", "symbol", "bigint", "object shape"
]


def load_snapshot(path: str | Path) -> dict[str, Any]:
    """Загружает .heapsnapshot JSON. Путь может содержать ':' (Chrome naming)."""
    path = Path(path)
    if not path.exists():
        # Попытка как строка с двоеточием (например "Heap-...:before.heapsnapshot")
        alt = str(path).replace(":", "/")
        if alt != str(path):
            path = Path(alt)
    with open(path, "r", encoding="utf-8", errors="replace") as f:
        return json.load(f)


def get_meta(data: dict) -> dict:
    return data.get("snapshot", {}).get("meta", {})


def get_node_count(data: dict) -> int:
    return data.get("snapshot", {}).get("node_count", 0)


def get_strings(data: dict) -> list[str]:
    return data.get("strings", [])


def iter_nodes(data: dict):
    """
    Итератор по узлам: (type_name, name_string, id, self_size, edge_count, detachedness).
    """
    nodes = data.get("nodes", [])
    strings = data.get("strings", [])
    n = len(nodes) // STRIDE
    for i in range(n):
        base = i * STRIDE
        type_idx = nodes[base + 0]
        name_idx = nodes[base + 1]
        node_id = nodes[base + 2]
        self_size = nodes[base + 3]
        edge_count = nodes[base + 4]
        detachedness = nodes[base + 5]
        type_name = NODE_TYPE_NAMES[type_idx] if type_idx < len(NODE_TYPE_NAMES) else f"type_{type_idx}"
        name_str = strings[name_idx] if name_idx < len(strings) else ""
        yield type_name, name_str, node_id, self_size, edge_count, detachedness


def aggregate_by_type(data: dict) -> dict[str, dict]:
    """По типам: count, self_size."""
    by_type: dict[str, dict] = defaultdict(lambda: {"count": 0, "self_size": 0})
    for t, _name, _id, self_size, _ec, _det in iter_nodes(data):
        by_type[t]["count"] += 1
        by_type[t]["self_size"] += self_size
    return dict(by_type)


def aggregate_by_name(data: dict, min_self_size: int = 0) -> dict[str, dict]:
    """
    По имени конструктора/класса (для object и т.д.): count, self_size.
    Игнорируем пустые и системные, опционально отсекаем по min_self_size.
    """
    by_name: dict[str, dict] = defaultdict(lambda: {"count": 0, "self_size": 0})
    for _t, name, _id, self_size, _ec, _det in iter_nodes(data):
        if not name or name.startswith("system ") or name == "<dummy>":
            continue
        if self_size < min_self_size:
            continue
        by_name[name]["count"] += 1
        by_name[name]["self_size"] += self_size
    return dict(by_name)


def aggregate_strings(data: dict) -> dict[str, dict]:
    """Строки: количество и суммарный self_size."""
    by_name: dict[str, dict] = defaultdict(lambda: {"count": 0, "self_size": 0})
    for t, name, _id, self_size, _ec, _det in iter_nodes(data):
        if t != "string" and t != "concatenated string" and t != "sliced string":
            continue
        key = name[:80] + ("..." if len(name) > 80 else "") if name else "(empty)"
        by_name[key]["count"] += 1
        by_name[key]["self_size"] += self_size
    return dict(by_name)


def total_self_size(data: dict) -> int:
    total = 0
    for _t, _n, _id, self_size, _ec, _det in iter_nodes(data):
        total += self_size
    return total
