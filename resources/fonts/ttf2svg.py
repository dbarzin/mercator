#!/usr/bin/env python3
import os
import json
import argparse
from fontTools.ttLib import TTFont
from fontTools.pens.svgPathPen import SVGPathPen

SVG_TEMPLATE = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="{vb}">
  <path d="{d}" fill="currentColor"/>
</svg>
"""

def ensure_dir(p: str):
    os.makedirs(p, exist_ok=True)

def escaped_char(cp: int) -> str:
    # Retourne une séquence lisible avec backslash, stockée telle quelle dans le JSON.
    # BMP -> \uXXXX, sinon -> \UXXXXXXXX
    if cp <= 0xFFFF:
        return f"\\u{cp:04X}"
    return f"\\U{cp:08X}"

def pick_cmap(font: TTFont):
    # Prend une cmap Unicode "correcte" en priorité.
    # format 12 (UCS-4) si dispo, sinon format 4 (BMP).
    cmap_table = font["cmap"]
    candidates = []
    for t in cmap_table.tables:
        if t.isUnicode():
            candidates.append(t)

    # tri "qualité" : format 12 > format 4 > autre
    def score(t):
        fmt = getattr(t, "format", 0)
        if fmt == 12: return 100
        if fmt == 4:  return 80
        return 10

    if not candidates:
        raise RuntimeError("Aucune table cmap Unicode trouvée dans la police.")

    candidates.sort(key=score, reverse=True)
    return candidates[0].cmap  # dict: codepoint(int) -> glyphName(str)

def glyph_to_svg_path(font: TTFont, glyph_name: str):
    glyph_set = font.getGlyphSet()
    if glyph_name not in glyph_set:
        return None

    pen = SVGPathPen(glyph_set)
    glyph_set[glyph_name].draw(pen)
    d = pen.getCommands()
    if not d:
        return None

    # Bounding box du glyphe (xMin, yMin, xMax, yMax)
    glyf = font["glyf"][glyph_name] if "glyf" in font else None
    if glyf is not None and hasattr(glyf, "xMin"):
        xMin, yMin, xMax, yMax = glyf.xMin, glyf.yMin, glyf.xMax, glyf.yMax
    else:
        # fallback : viewBox "raisonnable"
        xMin, yMin, xMax, yMax = 0, 0, 1000, 1000

    # Attention : dans les fonts, Y monte vers le haut.
    # En SVG, Y descend vers le bas.
    # Solution simple: on inverse l’axe Y via viewBox négatif
    # et un transform scale(1,-1) sur le path.
    vb = f"{xMin} {-yMax} {xMax - xMin} {yMax - yMin}"
    return d, vb

def main():
    ap = argparse.ArgumentParser(description="Convertit un TTF en SVG par glyphe + mapping JSON.")
    ap.add_argument("ttf", help="Chemin vers le .ttf (ex: bpmn.ttf)")
    ap.add_argument("--out", default="out", help="Dossier de sortie (défaut: out)")
    ap.add_argument("--prefix", default="bpmn", help="Préfixe des fichiers svg (défaut: bpmn)")
    ap.add_argument("--only-pua", action="store_true",
                    help="Ne sortir que les glyphes en zone Unicode privée (PUA) U+E000..U+F8FF")
    args = ap.parse_args()

    out_dir = os.path.abspath(args.out)
    svg_dir = os.path.join(out_dir, "svg")
    ensure_dir(svg_dir)

    font = TTFont(args.ttf)
    cmap = pick_cmap(font)

    mapping = []
    # codepoint -> glyphName
    for cp, gname in sorted(cmap.items(), key=lambda x: x[0]):
        if args.only_pua and not (0xE000 <= cp <= 0xF8FF):
            continue

        res = glyph_to_svg_path(font, gname)
        if not res:
            continue

        d, vb = res

        # Nom de fichier stable
        # Ex: bpmn_uE800.svg
        fname = f"{args.prefix}_u{cp:04X}.svg"
        fpath = os.path.join(svg_dir, fname)

        # On inverse l'axe Y avec transform, pour éviter des SVG à l’envers
        svg = SVG_TEMPLATE.format(vb=vb, d=d).replace(
            '<path d="', '<path transform="scale(1,-1)" d="'
        )

        with open(fpath, "w", encoding="utf-8") as f:
            f.write(svg)

        mapping.append({
            "codepoint": f"U+{cp:04X}",
            "char": escaped_char(cp),
             "glyph": gname,
            "svg": f"svg/{fname}",
        })

    with open(os.path.join(out_dir, "map.json"), "w", encoding="utf-8") as f:
        json.dump(mapping, f, indent=2, ensure_ascii=True)

    print(f"OK: {len(mapping)} SVG générés dans {svg_dir}")
    print(f"Mapping: {os.path.join(out_dir, 'map.json')}")

if __name__ == "__main__":
    main()

