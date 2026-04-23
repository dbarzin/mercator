#!/usr/bin/env python3
import os
import re
import json
import argparse
import math
from typing import Dict, Tuple, Optional, List
from xml.etree import ElementTree as ET

from fontTools.fontBuilder import FontBuilder
from fontTools.pens.ttGlyphPen import TTGlyphPen
from fontTools.svgLib.path import parse_path, TransformPen
from fontTools.ttLib.tables._g_l_y_f import Glyph

# ---------- Helpers ----------

def read_text(p: str) -> str:
    with open(p, "r", encoding="utf-8") as f:
        return f.read()

def ensure_parent_dir(p: str):
    os.makedirs(os.path.dirname(os.path.abspath(p)), exist_ok=True)

def svg_find_first_path(svg_root: ET.Element) -> Optional[ET.Element]:
    def local(tag: str) -> str:
        return tag.split("}", 1)[-1] if "}" in tag else tag
    for el in svg_root.iter():
        if local(el.tag) == "path":
            return el
    return None

def is_export_flip_transform(transform: str) -> bool:
    # ton export: transform="scale(1,-1)"
    t = transform.strip().lower()
    t = t.replace(" ", "")
    return t in ("scale(1,-1)", "scale(1,-1.0)")


def normalize_glyph_left(g: Glyph) -> int:
    """
    Translate le glyphe pour que xMin=0.
    Retourne le décalage appliqué (dx).
    """
    if g.numberOfContours == 0:
        return 0
    g.recalcBounds(None)
    dx = -int(g.xMin)
    if dx != 0:
        g.coordinates.translate((dx, 0))
        g.recalcBounds(None)
    return dx


def parse_simple_transform(transform: str) -> Optional[Tuple[float, float, float, float, float, float]]:
    """
    Support minimal:
      - matrix(a b c d e f)
      - scale(sx,sy?)
      - translate(tx,ty?)
    Retourne (a,b,c,d,e,f)
    """
    t = transform.strip()
    if not t:
        return None

    t = re.sub(r"\s+", " ", t)

    m = re.match(r"matrix\(([^)]+)\)", t)
    if m:
        nums = [float(x) for x in re.split(r"[,\s]+", m.group(1).strip()) if x]
        if len(nums) == 6:
            return tuple(nums)  # type: ignore

    m = re.match(r"scale\(([^)]+)\)", t)
    if m:
        nums = [float(x) for x in re.split(r"[,\s]+", m.group(1).strip()) if x]
        if len(nums) == 1:
            sx = nums[0]
            return (sx, 0.0, 0.0, sx, 0.0, 0.0)
        if len(nums) == 2:
            sx, sy = nums
            return (sx, 0.0, 0.0, sy, 0.0, 0.0)

    m = re.match(r"translate\(([^)]+)\)", t)
    if m:
        nums = [float(x) for x in re.split(r"[,\s]+", m.group(1).strip()) if x]
        if len(nums) == 1:
            tx = nums[0]
            return (1.0, 0.0, 0.0, 1.0, tx, 0.0)
        if len(nums) == 2:
            tx, ty = nums
            return (1.0, 0.0, 0.0, 1.0, tx, ty)

    return None

def glyph_bbox(g: Glyph) -> Tuple[int, int, int, int]:
    """
    Retourne (xMin,yMin,xMax,yMax) pour un glyphe glyf.
    Si glyphe vide -> bbox 0.
    """
    if g.numberOfContours == 0:
        return (0, 0, 0, 0)
    # recalc pour être sûr
    g.recalcBounds(None)
    return (g.xMin, g.yMin, g.xMax, g.yMax)

# ------------------------------------------

def _local(tag: str) -> str:
    return tag.split("}", 1)[-1] if "}" in tag else tag

def _as_float(v: Optional[str], default: float = 0.0) -> float:
    if v is None:
        return default
    # support "600", "600px"
    v = v.strip().replace("px", "")
    try:
        return float(v)
    except Exception:
        return default

def _parse_viewbox(vb: str) -> Optional[Tuple[float, float, float, float]]:
    try:
        parts = [float(x) for x in vb.replace(",", " ").split() if x]
        if len(parts) != 4:
            return None
        return parts[0], parts[1], parts[2], parts[3]
    except Exception:
        return None

def _is_export_flip(transform: str) -> bool:
    t = (transform or "").strip().lower().replace(" ", "")
    return t in ("scale(1,-1)", "scale(1,-1.0)")

def _line_to_outline_polygon(
    x1: float, y1: float, x2: float, y2: float, stroke_w: float
) -> List[Tuple[float, float]]:
    """
    Convertit une ligne + épaisseur en rectangle (cap 'butt').
    Retourne 4 points (polygone).
    """
    dx = x2 - x1
    dy = y2 - y1
    L = math.hypot(dx, dy)
    if L == 0:
        # segment nul -> petit carré
        r = stroke_w / 2.0
        return [(x1 - r, y1 - r), (x1 + r, y1 - r), (x1 + r, y1 + r), (x1 - r, y1 + r)]

    ux = dx / L
    uy = dy / L
    # vecteur perpendiculaire unitaire
    px = -uy
    py = ux
    r = stroke_w / 2.0

    p1 = (x1 + px * r, y1 + py * r)
    p2 = (x1 - px * r, y1 - py * r)
    p3 = (x2 - px * r, y2 - py * r)
    p4 = (x2 + px * r, y2 + py * r)
    return [p1, p2, p3, p4]

def _draw_polygon(pen: TTGlyphPen, pts: List[Tuple[float, float]]):
    if not pts:
        return
    pen.moveTo(pts[0])
    for p in pts[1:]:
        pen.lineTo(p)
    pen.closePath()

def _parse_points(points: str) -> List[Tuple[float, float]]:
    # "x1,y1 x2,y2 ..." ou "x1 y1 x2 y2 ..."
    pts = []
    if not points:
        return pts
    # remplace virgules par espaces puis split
    parts = [p for p in re.split(r"[,\s]+", points.strip()) if p]
    if len(parts) % 2 != 0:
        return pts
    for i in range(0, len(parts), 2):
        pts.append((float(parts[i]), float(parts[i+1])))
    return pts

def _apply_aff_to_pts(pts: List[Tuple[float, float]], aff: Optional[Tuple[float,float,float,float,float,float]]):
    if not aff:
        return pts
    a,b,c,d,e,f = aff
    return [(a*x + c*y + e, b*x + d*y + f) for (x,y) in pts]

def _draw_poly(pen: TTGlyphPen, pts: List[Tuple[float, float]], close: bool):
    if not pts:
        return
    pen.moveTo(pts[0])
    for p in pts[1:]:
        pen.lineTo(p)
    if close:
        pen.closePath()


def build_glyph_from_svg(svg_file: str):
    root = ET.fromstring(read_text(svg_file))

    # Détermine le repère:
    # - Si viewBox présent (cas de ton export ttf2svg.py), en général les coords sont déjà “font-like”.
    # - Si viewBox absent mais width/height présents (cas ici), on flip Y sur la hauteur SVG.
    vb = root.attrib.get("viewBox")
    vb_parsed = _parse_viewbox(vb) if vb else None

    width = _as_float(root.attrib.get("width"), 0.0)
    height = _as_float(root.attrib.get("height"), 0.0)

    # Transform global pour passer SVG (Y down) -> font (Y up)
    # Seulement si pas de viewBox (typiquement fichier “dessiné” en SVG standard)
    apply_global_flip = vb_parsed is None and height > 0

    # matrice affine (a,b,c,d,e,f)
    # flipY autour de H: x' = x ; y' = -y + H
    global_aff = (1.0, 0.0, 0.0, -1.0, 0.0, float(height)) if apply_global_flip else None

    pen = TTGlyphPen(None)

    # Pen transformé si flip global
    target_pen = TransformPen(pen, global_aff) if global_aff else pen

    # Parcours de tous les éléments
    for el in root.iter():
        tag = _local(el.tag)

        if tag == "rect":
            x = _as_float(el.attrib.get("x"), 0.0)
            y = _as_float(el.attrib.get("y"), 0.0)
            w = _as_float(el.attrib.get("width"), 0.0)
            h = _as_float(el.attrib.get("height"), 0.0)

            # ignore les rect vides
            if w <= 0 or h <= 0:
                continue

            pts = [(x, y), (x + w, y), (x + w, y + h), (x, y + h)]
            pts = _apply_aff_to_pts(pts, global_aff)  # flip Y si besoin
            _draw_poly(pen, pts, close=True)

        elif tag == "polygon":
            pts = _parse_points(el.attrib.get("points", ""))
            pts = _apply_aff_to_pts(pts, global_aff)
            _draw_poly(pen, pts, close=True)

        elif tag == "polyline":
            pts = _parse_points(el.attrib.get("points", ""))
            pts = _apply_aff_to_pts(pts, global_aff)
            # polyline: par défaut ouvert. Si c'est rempli, on ferme.
            fill = (el.attrib.get("fill") or "").strip().lower()
            should_close = fill not in ("", "none")
            _draw_poly(pen, pts, close=should_close)

        elif tag == "line":
                x1 = _as_float(el.attrib.get("x1"))
                y1 = _as_float(el.attrib.get("y1"))
                x2 = _as_float(el.attrib.get("x2"))
                y2 = _as_float(el.attrib.get("y2"))
                sw = _as_float(el.attrib.get("stroke-width"), 1.0)

                # Convertit le stroke en outline rempli
                poly = _line_to_outline_polygon(x1, y1, x2, y2, sw)

                # Important: si global flip actif, on doit transformer les points
                if global_aff:
                    a,b,c,d,e,f = global_aff
                    poly = [ (a*x + c*y + e, b*x + d*y + f) for (x,y) in poly ]

                _draw_polygon(pen, poly)

        elif tag == "path":
            d_attr = el.attrib.get("d")
            if not d_attr:
                continue

            # Si le path a le transform d’export scale(1,-1), on l’ignore (c’est juste pour l’affichage SVG exporté)
            t_attr = el.attrib.get("transform", "")
            if t_attr and _is_export_flip(t_attr):
                t_attr = ""

            # Cas spécial: ton SVG a un path qui ressemble à un segment avec stroke-width.
            # S'il n'y a pas de fermeture, parse_path va dessiner un contour ouvert
            # (ce qui peut “disparaître” en glyf). Donc on le transforme en outline s’il ressemble à une ligne.
            sw = _as_float(el.attrib.get("stroke-width"), 0.0)
            fill = (el.attrib.get("fill") or "").strip().lower()
            stroke = (el.attrib.get("stroke") or "").strip().lower()

            looks_like_stroked_line = (sw > 0 and (stroke or fill == "#000000" or fill == "currentcolor"))

            # Heuristique simple pour les “m x,y dx,dy” (ton cas)
            m = re.match(r"^\s*m\s*([-\d.]+)\s*,\s*([-\d.]+)\s*([-\d.]+)\s*,\s*([-\d.]+)\s*$", d_attr, re.IGNORECASE)
            if looks_like_stroked_line and m:
                x1 = float(m.group(1)); y1 = float(m.group(2))
                dx = float(m.group(3)); dy = float(m.group(4))
                x2 = x1 + dx; y2 = y1 + dy
                poly = _line_to_outline_polygon(x1, y1, x2, y2, sw)
                if global_aff:
                    a,b,c,d,e,f = global_aff
                    poly = [ (a*x + c*y + e, b*x + d*y + f) for (x,y) in poly ]
                _draw_polygon(pen, poly)
                continue

            # Sinon on parse le path en contour rempli
            # (si c’est un shape rempli, il doit être fermé)
            parse_path(d_attr, target_pen)

    return pen.glyph()

def compute_metrics(g: Glyph, units_per_em: int, pad: int = 50, force_lsb: int | None = None) -> Tuple[int, int]:
    """
    Calcule (advanceWidth, lsb) depuis bbox réelle du glyphe.
    - lsb = xMin
    - advance = (xMax - xMin) + padding
    """
    xMin, _yMin, xMax, _yMax = glyph_bbox(g)
    width = max(0, xMax - xMin)
    aw = max(width + pad, units_per_em // 5)
    lsb = force_lsb if force_lsb is not None else int(xMin)
    return int(aw), int(lsb)

# ---------- Main ----------

def main():
    ap = argparse.ArgumentParser(description="Recrée une police TTF à partir de SVG + map.json (format exporté par ton script).")
    ap.add_argument("in_dir", help="Dossier contenant map.json et svg/ (ex: out)")
    ap.add_argument("--out", default="rebuilt.ttf", help="TTF de sortie (défaut: rebuilt.ttf)")
    ap.add_argument("--family", default="BPMN", help="Nom de famille (défaut: BPMN)")
    ap.add_argument("--style", default="Regular", help="Style (défaut: Regular)")
    ap.add_argument("--units-per-em", type=int, default=1000, help="unitsPerEm (défaut: 1000)")
    ap.add_argument("--ascent", type=int, default=800, help="Ascent (défaut: 800)")
    ap.add_argument("--descent", type=int, default=-200, help="Descent (défaut: -200)")
    ap.add_argument("--pad", type=int, default=60, help="Padding ajouté à advanceWidth (défaut: 60)")
    ap.add_argument("--version", default="1.0", help="Version (défaut: 1.0)")
    args = ap.parse_args()

    in_dir = os.path.abspath(args.in_dir)
    map_path = os.path.join(in_dir, "map.json")
    if not os.path.isfile(map_path):
        raise SystemExit(f"map.json introuvable: {map_path}")

    mapping = json.loads(read_text(map_path))
    if not isinstance(mapping, list):
        raise SystemExit("map.json invalide (attendu: liste JSON)")

    glyph_order = [".notdef"]
    glyphs: Dict[str, Glyph] = {".notdef": TTGlyphPen(None).glyph()}
    hmtx: Dict[str, Tuple[int, int]] = {".notdef": (args.units_per_em, 0)}
    cmap: Dict[int, str] = {}

    # Ajoute un space minimal (utile dans plein de cas)
    if "space" not in glyphs:
        glyphs["space"] = TTGlyphPen(None).glyph()
        hmtx["space"] = (args.units_per_em // 2, 0)
        cmap[0x20] = "space"
        glyph_order.append("space")

    # Build glyphs
    for entry in mapping:
        cp_str = entry.get("codepoint")
        gname = entry.get("glyph")
        svg_rel = entry.get("svg")

        if not (cp_str and gname and svg_rel):
            print('skip map entry 1')
            continue
        if not cp_str.startswith("U+"):
            print('skip map entry 2')
            continue

        cp = int(cp_str[2:], 16)
        svg_file = os.path.join(in_dir, svg_rel)
        if not os.path.isfile(svg_file):
            raise SystemExit(f"SVG introuvable: {svg_file}")

        print('add ', svg_rel)

        g = build_glyph_from_svg(svg_file)

        # gname unique
        name = gname
        if name in glyphs:
            base = name
            i = 2
            while f"{base}.{i}" in glyphs:
                i += 1
            name = f"{base}.{i}"

        # ✅ Normalisation: retire l'offset X dû à x="45", etc.
        normalize_glyph_left(g)
        glyphs[name] = g
        # ✅ Maintenant le LSB doit être 0 (car xMin=0)
        hmtx[name] = compute_metrics(g, args.units_per_em, pad=args.pad, force_lsb=0)
        cmap[cp] = name
        glyph_order.append(name)

    fb = FontBuilder(args.units_per_em, isTTF=True)
    fb.setupGlyphOrder(glyph_order)
    fb.setupCharacterMap(cmap)
    fb.setupGlyf(glyphs)
    fb.setupHorizontalMetrics(hmtx)
    fb.setupHorizontalHeader(ascent=args.ascent, descent=args.descent)

    fb.setupOS2(
        sTypoAscender=args.ascent,
        sTypoDescender=args.descent,
        usWinAscent=max(args.ascent, 0),
        usWinDescent=max(-args.descent, 0),
    )

    fb.setupNameTable(
        {
            "familyName": args.family,
            "styleName": args.style,
            "uniqueFontIdentifier": f"{args.family}-{args.style}",
            "fullName": f"{args.family} {args.style}",
            "version": f"Version {args.version}",
            "psName": f"{args.family.replace(' ', '')}-{args.style.replace(' ', '')}",
        }
    )

    fb.setupPost()
    fb.setupMaxp()
    fb.setupHead()

    out_path = os.path.abspath(args.out)
    ensure_parent_dir(out_path)
    fb.save(out_path)

    print(f"OK: {out_path}")
    print(f"Glyphes: {len(glyph_order)} | cmap: {len(cmap)}")

if __name__ == "__main__":
    main()
