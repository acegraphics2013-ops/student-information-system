# PDF Export Workflow

The three draft deliverables are generated from the Markdown sources with the
repository helper `tools/render_submission_pdfs.py`:

- `submission/AI_PORTFOLIO.pdf`
- `submission/AGENT_DESIGN.pdf`
- `submission/REFLECTION.pdf`

The helper uses Python 3.11 and the packages `mistune`, `reportlab`, `Pillow`,
and `pypdf`. Install them in a local Python environment, then run from the
project root:

```powershell
python tools\render_submission_pdfs.py
```

The renderer creates a cover page, page numbers, readable Markdown tables,
embedded Portfolio screenshots, and a vector diagram from the Mermaid source
in `docs/AGENT_DESIGN.md`. If the existing Agent Design PDF has student-entered
cover values, the helper preserves that first page while refreshing the body.

All three current exports are labeled DRAFT. The Portfolio and Reflection
contain student-input placeholders; the Agent Design cover requires final
student review. Open the PDFs and inspect all pages before changing their
status. Do not treat generated first-person reflection or an unverified
handbook question as student-authored evidence.