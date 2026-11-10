from __future__ import annotations

import re
from pathlib import Path
from xml.sax.saxutils import escape

import mistune
from PIL import Image as PILImage
from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import mm
from reportlab.platypus import (
    Flowable,
    Image,
    KeepTogether,
    ListFlowable,
    ListItem,
    PageBreak,
    Paragraph,
    Preformatted,
    SimpleDocTemplate,
    Spacer,
    Table,
    TableStyle,
)


ROOT = Path(__file__).resolve().parents[1]
OUTPUT = ROOT / "submission"
NAVY = colors.HexColor("#17324D")
GREEN = colors.HexColor("#18736C")
GOLD = colors.HexColor("#D49A2A")
INK = colors.HexColor("#263238")
MUTED = colors.HexColor("#64727A")
PALE = colors.HexColor("#F1F5F6")
WHITE = colors.white


class MermaidFlowchart(Flowable):
    """Render the documented flowchart as a vector diagram in the PDF."""

    def __init__(self, source: str, width: float):
        super().__init__()
        self.source = source
        self.width = width
        self.height = 445

    def wrap(self, available_width: float, available_height: float) -> tuple[float, float]:
        self.width = min(self.width, available_width)
        return self.width, self.height

    def draw(self) -> None:
        canvas = self.canv
        width = self.width
        box_width = min(330, width - 95)
        box_height = 38
        center = width / 2
        positions = {
            "H": (center, self.height - 34),
            "P": (center, self.height - 98),
            "D": (center, self.height - 162),
            "L": (center, self.height - 226),
            "S": (center, self.height - 290),
            "T": (center, self.height - 354),
            "W": (center, self.height - 418),
        }
        labels = {
            match.group(1): match.group(2).replace("/", " / ")
            for match in re.finditer(r"\b([A-Z])\[([^\]]+)\]", self.source)
        }
        node_colors = {
            "H": GREEN,
            "P": NAVY,
            "D": NAVY,
            "L": NAVY,
            "S": NAVY,
            "T": NAVY,
            "W": NAVY,
        }

        edges = []
        for line in self.source.splitlines():
            match = re.search(r"\b([A-Z])\s*-->(?:\|([^|]+)\|)?\s*([A-Z])", line)
            if match:
                edges.append((match.group(1), match.group(3), (match.group(2) or "").strip()))

        drawn: set[tuple[str, str, str]] = set()
        for start, end, label in edges:
            edge = (start, end, label)
            if edge in drawn or start not in positions or end not in positions:
                continue
            drawn.add(edge)
            sx, sy = positions[start]
            ex, ey = positions[end]
            if start == "T" and end == "L":
                x = 35
                canvas.setStrokeColor(GOLD)
                canvas.setLineWidth(1.2)
                canvas.bezier(sx - box_width / 2, sy, x, sy + 8, x, ey - 8, ex - box_width / 2, ey)
                self._arrowhead(x1=x, y1=ey - 8, x2=ex - box_width / 2, y2=ey)
                self._edge_label(label or "Failures and corrections", x + 7, (sy + ey) / 2)
            elif start == "W" and end == "H":
                x = width - 35
                canvas.setStrokeColor(GOLD)
                canvas.setLineWidth(1.2)
                canvas.bezier(ex + box_width / 2, ey, x, ey + 6, x, sy - 6, sx + box_width / 2, sy)
                self._arrowhead(x1=x, y1=sy - 6, x2=sx + box_width / 2, y2=sy)
                self._edge_label("Human review", x - 76, (sy + ey) / 2)
            elif start == "H" and end == "D":
                x = 55
                canvas.setStrokeColor(GOLD)
                canvas.setLineWidth(1)
                canvas.setDash(3, 2)
                canvas.bezier(sx - box_width / 2, sy, x, sy - 12, x, ey + 12, ex - box_width / 2, ey)
                canvas.setDash()
                self._arrowhead(x1=x, y1=ey + 12, x2=ex - box_width / 2, y2=ey)
                self._edge_label("Schema approval", x + 3, (sy + ey) / 2)
            elif start == "H" and end == "P":
                continue
            else:
                canvas.setStrokeColor(colors.HexColor("#87969C"))
                canvas.setLineWidth(1)
                canvas.line(sx, sy - box_height / 2, ex, ey + box_height / 2)
                self._arrowhead(x1=sx, y1=sy - box_height / 2, x2=ex, y2=ey + box_height / 2)
                if label:
                    self._edge_label(label, center + box_width / 2 + 5, (sy + ey) / 2)

        for node, (x, y) in positions.items():
            label = labels.get(node, node)
            canvas.setFillColor(node_colors[node])
            canvas.setStrokeColor(node_colors[node])
            canvas.roundRect(x - box_width / 2, y - box_height / 2, box_width, box_height, 5, fill=1, stroke=0)
            style = ParagraphStyle(
                f"diagram-{node}",
                fontName="Helvetica-Bold",
                fontSize=8.5,
                leading=10,
                textColor=WHITE,
                alignment=TA_CENTER,
            )
            paragraph = Paragraph(escape(label), style)
            _, paragraph_height = paragraph.wrap(box_width - 12, box_height - 4)
            paragraph.drawOn(canvas, x - box_width / 2 + 6, y - paragraph_height / 2)

        canvas.setFillColor(MUTED)
        canvas.setFont("Helvetica-Oblique", 7.5)
        canvas.drawCentredString(center, 4, "Proposed architecture; not evidence of independent agent execution")

    def _arrowhead(self, x1: float, y1: float, x2: float, y2: float) -> None:
        canvas = self.canv
        angle = __import__("math").atan2(y2 - y1, x2 - x1)
        size = 5
        left = (x2 - size * __import__("math").cos(angle - 0.45), y2 - size * __import__("math").sin(angle - 0.45))
        right = (x2 - size * __import__("math").cos(angle + 0.45), y2 - size * __import__("math").sin(angle + 0.45))
        canvas.setFillColor(GOLD)
        path = canvas.beginPath()
        path.moveTo(x2, y2)
        path.lineTo(*left)
        path.lineTo(*right)
        path.close()
        canvas.drawPath(path, fill=1, stroke=0)

    def _edge_label(self, text: str, x: float, y: float) -> None:
        self.canv.setFillColor(INK)
        self.canv.setFont("Helvetica", 6.5)
        self.canv.drawString(x, y, text[:34])


def make_styles() -> dict[str, ParagraphStyle]:
    base = getSampleStyleSheet()
    return {
        "body": ParagraphStyle(
            "BodySIMS", parent=base["BodyText"], fontName="Helvetica",
            fontSize=9.1, leading=13, textColor=INK, spaceAfter=6,
            splitLongWords=1,
        ),
        "h1": ParagraphStyle(
            "H1SIMS", parent=base["Heading1"], fontName="Helvetica-Bold",
            fontSize=17, leading=21, textColor=NAVY, spaceBefore=13,
            spaceAfter=7, keepWithNext=True,
        ),
        "h2": ParagraphStyle(
            "H2SIMS", parent=base["Heading2"], fontName="Helvetica-Bold",
            fontSize=12.5, leading=16, textColor=GREEN, spaceBefore=10,
            spaceAfter=5, keepWithNext=True,
        ),
        "h3": ParagraphStyle(
            "H3SIMS", parent=base["Heading3"], fontName="Helvetica-Bold",
            fontSize=10.4, leading=13, textColor=NAVY, spaceBefore=8,
            spaceAfter=4, keepWithNext=True,
        ),
        "small": ParagraphStyle(
            "SmallSIMS", fontName="Helvetica", fontSize=7.1, leading=9,
            textColor=INK, splitLongWords=1,
        ),
        "caption": ParagraphStyle(
            "CaptionSIMS", fontName="Helvetica-Oblique", fontSize=7.4,
            leading=9, alignment=TA_CENTER, textColor=MUTED, spaceAfter=8,
        ),
        "cover-title": ParagraphStyle(
            "CoverTitleSIMS", fontName="Helvetica-Bold", fontSize=26,
            leading=31, alignment=TA_CENTER, textColor=NAVY, spaceAfter=12,
        ),
        "cover-subtitle": ParagraphStyle(
            "CoverSubtitleSIMS", fontName="Helvetica", fontSize=16,
            leading=21, alignment=TA_CENTER, textColor=GREEN, spaceAfter=8,
        ),
        "cover-meta": ParagraphStyle(
            "CoverMetaSIMS", fontName="Helvetica", fontSize=10,
            leading=15, alignment=TA_CENTER, textColor=INK,
        ),
    }


def inline_markup(node: dict) -> str:
    kind = node.get("type")
    children = "".join(inline_markup(child) for child in node.get("children", []))
    if kind == "text":
        return escape(node.get("raw", ""))
    if kind == "strong":
        return f"<b>{children}</b>"
    if kind == "emphasis":
        return f"<i>{children}</i>"
    if kind == "codespan":
        return f'<font name="Courier" size="8">{children}</font>'
    if kind == "link":
        url = escape(node.get("attrs", {}).get("url", ""), {'"': "&quot;"})
        return f'<link href="{url}" color="#18736C">{children}</link>'
    if kind in {"softbreak", "linebreak"}:
        return "<br/>"
    if kind == "inline_html":
        return escape(node.get("raw", ""))
    if kind == "image":
        return children
    return children or escape(node.get("raw", ""))


def paragraph_text(node: dict) -> str:
    return "".join(inline_markup(child) for child in node.get("children", []))


def markdown_ast(path: Path) -> list[dict]:
    parser = mistune.create_markdown(renderer="ast", plugins=["table", "strikethrough", "task_lists"])
    return parser(path.read_text(encoding="utf-8"))


def document_body(path: Path, nodes: list[dict]) -> list[dict]:
    output = []
    started = False
    for node in nodes:
        if not started:
            if node.get("type") == "heading":
                continue
            if node.get("type") == "table":
                continue
            if node.get("type") == "heading" and node.get("attrs", {}).get("level") == 2:
                started = True
            elif node.get("type") == "paragraph" and "Status" in paragraph_text(node):
                started = True
                output.append(node)
                continue
            else:
                continue
        output.append(node)
    if path.name == "AGENT_DESIGN.md":
        for index, node in enumerate(nodes):
            if node.get("type") == "heading" and node.get("attrs", {}).get("level") == 2:
                return nodes[index:]
    if path.name == "REFLECTION.md":
        for index, node in enumerate(nodes):
            if node.get("type") == "block_quote":
                return nodes[index:]
    return output


def cover_story(title: str, styles: dict[str, ParagraphStyle]) -> list:
    story = [Spacer(1, 25 * mm)]
    story.append(Paragraph("NORTHEASTERN MINDANAO STATE UNIVERSITY", styles["cover-meta"]))
    story.append(Paragraph("TANDAG CAMPUS", styles["cover-meta"]))
    story.append(Spacer(1, 24 * mm))
    story.append(Paragraph(escape(title), styles["cover-title"]))
    story.append(Paragraph("Student Information Management System", styles["cover-subtitle"]))
    story.append(Spacer(1, 5 * mm))
    story.append(Paragraph("School Year 2026-2027", styles["cover-meta"]))
    story.append(Spacer(1, 16 * mm))
    fields = [
        [Paragraph("Student name", styles["small"]), Paragraph("[STUDENT INPUT REQUIRED]", styles["small"])],
        [Paragraph("Instructor", styles["small"]), Paragraph("[STUDENT INPUT REQUIRED]", styles["small"])],
        [Paragraph("Course/Section", styles["small"]), Paragraph("[STUDENT INPUT REQUIRED]", styles["small"])],
        [Paragraph("Submission date", styles["small"]), Paragraph("[STUDENT INPUT REQUIRED]", styles["small"])],
    ]
    table = Table(fields, colWidths=[43 * mm, 67 * mm], hAlign="CENTER")
    table.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (0, -1), PALE),
        ("TEXTCOLOR", (0, 0), (0, -1), NAVY),
        ("GRID", (0, 0), (-1, -1), 0.4, colors.HexColor("#CFD8DC")),
        ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
        ("LEFTPADDING", (0, 0), (-1, -1), 8),
        ("RIGHTPADDING", (0, 0), (-1, -1), 8),
        ("TOPPADDING", (0, 0), (-1, -1), 7),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 7),
    ]))
    story.extend([table, Spacer(1, 16 * mm)])
    story.append(Paragraph("DRAFT - STUDENT REVIEW REQUIRED", styles["cover-meta"]))
    story.append(PageBreak())
    return story


def table_flowable(node: dict, width: float, styles: dict[str, ParagraphStyle]) -> Table:
    rows = []
    for section in node.get("children", []):
        if section.get("type") == "table_head":
            rows.append([Paragraph(paragraph_text(cell), styles["small"]) for cell in section.get("children", [])])
        elif section.get("type") == "table_body":
            for row in section.get("children", []):
                rows.append([Paragraph(paragraph_text(cell), styles["small"]) for cell in row.get("children", [])])
    columns = max((len(row) for row in rows), default=1)
    table = Table(rows, colWidths=[width / columns] * columns, repeatRows=1, hAlign="LEFT")
    table.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, 0), NAVY),
        ("TEXTCOLOR", (0, 0), (-1, 0), WHITE),
        ("BACKGROUND", (0, 1), (-1, -1), colors.white),
        ("ROWBACKGROUNDS", (0, 1), (-1, -1), [colors.white, PALE]),
        ("GRID", (0, 0), (-1, -1), 0.35, colors.HexColor("#CBD5DA")),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("LEFTPADDING", (0, 0), (-1, -1), 5),
        ("RIGHTPADDING", (0, 0), (-1, -1), 5),
        ("TOPPADDING", (0, 0), (-1, -1), 5),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
    ]))
    return table


def image_flowable(src: str, alt: str, source: Path, width: float, styles: dict[str, ParagraphStyle]) -> list:
    image_path = (source.parent / src).resolve()
    if not image_path.is_file():
        return [Paragraph(f"[Image unavailable: {escape(src)}]", styles["caption"])]
    with PILImage.open(image_path) as bitmap:
        image_width, image_height = bitmap.size
    max_width = min(width * 0.88, 160 * mm)
    max_height = 95 * mm
    scale = min(max_width / image_width, max_height / image_height, 1)
    image = Image(str(image_path), width=image_width * scale, height=image_height * scale)
    image.hAlign = "CENTER"
    caption = Paragraph(escape(alt), styles["caption"])
    return [KeepTogether([image, caption])]


def list_flowable(node: dict, width: float, styles: dict[str, ParagraphStyle]) -> ListFlowable:
    items = []
    for item in node.get("children", []):
        content = []
        for child in item.get("children", []):
            if child.get("type") in {"paragraph", "block_text"}:
                content.append(Paragraph(paragraph_text(child), styles["body"]))
        items.append(ListItem(content or [Paragraph("", styles["body"])], leftIndent=8))
    return ListFlowable(
        items,
        bulletType="1" if node.get("attrs", {}).get("ordered") else "bullet",
        start="1",
        leftIndent=16,
        bulletFontName="Helvetica",
        bulletFontSize=8,
        bulletColor=GREEN,
        spaceAfter=5,
    )


def render_nodes(nodes: list[dict], source: Path, width: float, styles: dict[str, ParagraphStyle]) -> list:
    story = []
    for node in nodes:
        kind = node.get("type")
        if kind == "heading":
            level = node.get("attrs", {}).get("level", 2)
            text = paragraph_text(node)
            if level == 1:
                continue
            style = styles["h2"] if level == 2 else styles["h3"]
            story.append(Paragraph(text, style))
        elif kind == "paragraph":
            children = node.get("children", [])
            images = [child for child in children if child.get("type") == "image"]
            if images and len(children) == 1:
                attrs = images[0].get("attrs", {})
                story.extend(image_flowable(attrs.get("url", ""), paragraph_text(images[0]), source, width, styles))
            else:
                story.append(Paragraph(paragraph_text(node), styles["body"]))
        elif kind == "table":
            story.extend([table_flowable(node, width, styles), Spacer(1, 7)])
        elif kind == "list":
            story.append(list_flowable(node, width, styles))
        elif kind == "block_quote":
            inner = render_nodes(node.get("children", []), source, width - 14, styles)
            box = Table([[inner]], colWidths=[width], hAlign="LEFT")
            box.setStyle(TableStyle([
                ("BACKGROUND", (0, 0), (-1, -1), PALE),
                ("LINEBEFORE", (0, 0), (0, -1), 3, GREEN),
                ("LEFTPADDING", (0, 0), (-1, -1), 9),
                ("RIGHTPADDING", (0, 0), (-1, -1), 8),
                ("TOPPADDING", (0, 0), (-1, -1), 6),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 2),
            ]))
            story.extend([box, Spacer(1, 6)])
        elif kind == "block_code":
            raw = node.get("raw", "")
            info = node.get("attrs", {}).get("info", "")
            if info.strip().startswith("mermaid"):
                story.append(MermaidFlowchart(raw, width))
                story.append(Spacer(1, 8))
            else:
                code_style = ParagraphStyle(
                    "CodeSIMS", fontName="Courier", fontSize=7.2, leading=9,
                    textColor=INK, backColor=PALE, borderColor=colors.HexColor("#D7E0E4"),
                    borderWidth=0.4, borderPadding=6, splitLongWords=1,
                )
                lines = [escape(line) for line in raw.rstrip().splitlines()]
                story.append(Paragraph("<br/>".join(lines) or "&nbsp;", code_style))
                story.append(Spacer(1, 7))
        elif kind == "thematic_break":
            story.append(Spacer(1, 5))
        elif kind == "blank_line":
            continue
    return story


def draw_footer(canvas, document) -> None:
    canvas.saveState()
    page_width, _ = A4
    canvas.setStrokeColor(colors.HexColor("#D9E1E5"))
    canvas.setLineWidth(0.5)
    canvas.line(18 * mm, 15 * mm, page_width - 18 * mm, 15 * mm)
    canvas.setFont("Helvetica", 7.5)
    canvas.setFillColor(MUTED)
    canvas.drawString(18 * mm, 10 * mm, "NEMSU Tandag Campus | SIMS | DRAFT")
    canvas.drawRightString(page_width - 18 * mm, 10 * mm, f"Page {document.page}")
    canvas.restoreState()


def render_one(source: Path, filename: str, title: str) -> None:
    styles = make_styles()
    nodes = markdown_ast(source)
    body = document_body(source, nodes)
    document = SimpleDocTemplate(
        str(OUTPUT / filename), pagesize=A4,
        rightMargin=18 * mm, leftMargin=18 * mm,
        topMargin=18 * mm, bottomMargin=21 * mm,
        title=title, author="NEMSU Tandag Campus SIMS project",
    )
    content_width = A4[0] - 36 * mm
    story = cover_story(title, styles)
    story.extend(render_nodes(body, source, content_width, styles))
    document.build(story, onFirstPage=draw_footer, onLaterPages=draw_footer)


def main() -> None:
    OUTPUT.mkdir(exist_ok=True)
    render_one(ROOT / "docs" / "AI_PORTFOLIO.md", "AI_PORTFOLIO.pdf", "AI Engineering Portfolio")
    render_one(ROOT / "docs" / "AGENT_DESIGN.md", "AGENT_DESIGN.pdf", "AI Agent Design")
    render_one(ROOT / "docs" / "REFLECTION.md", "REFLECTION.pdf", "Personal Reflection")
    print("Rendered draft PDFs:")
    for filename in ("AI_PORTFOLIO.pdf", "AGENT_DESIGN.pdf", "REFLECTION.pdf"):
        path = OUTPUT / filename
        print(f"{path} ({path.stat().st_size} bytes)")


if __name__ == "__main__":
    main()