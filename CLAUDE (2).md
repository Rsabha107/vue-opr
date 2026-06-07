# CLAUDE.md — Observation Programme Reporting Form

Guidance for porting the **Observation Form** prototype (in this project) into a
**Laravel + Inertia + Vue 3** application. The HTML/React prototype is the source
of truth for layout, fields, validation, and visual design. This file maps that
prototype onto a Laravel/Inertia/Vue stack.

> The prototype lives at `Observation Form.html` + `src/*.jsx`. There is also an
> admin view (`Observation Admin.html` + `src/admin*.jsx`). When in doubt about
> behaviour, read those files — they are canonical.

---

## 1. What this is

An **end-of-assignment reporting form** for an Observation Programme. One report is
submitted per assignment. A report contains:

- **A reporter** (one person submitting).
- **One or more venues** visited.
- Each venue has **one or more observation entries**.

The form supports a single-page layout and a step-by-step wizard layout (toggle in
the prototype). For the Laravel build, **single-page is the default**; treat the
wizard as optional progressive enhancement, not a requirement.

The form autosaves a draft to `localStorage` in the prototype. In the Laravel app,
drafts should persist server-side (see §7).

---

## 2. Domain / data model

Use these as the basis for migrations, Eloquent models, and the Inertia form payload.
Names below are the canonical field keys (match the prototype's JS state exactly so
the Vue port is a 1:1 translation).

### Report (root)
| Field | Type | Notes |
|---|---|---|
| `reporter.name` | string | **required** |
| `reporter.role` | string | optional ("Role / function") |
| `reporter.programme` | string | optional ("Assignment / programme") |
| `venues` | Venue[] | **min 1** |

### Venue
| Field | Type | Notes |
|---|---|---|
| `id` | string/uuid | client id in prototype; use real PK server-side |
| `type` | enum string | **required**, one of `VENUE_TYPES` (§3) |
| `otherText` | string | required **only** when `type === "Other (please specify)"` |
| `entries` | Entry[] | **min 1** |

### Entry (observation)
| Field | Type | Notes |
|---|---|---|
| `id` | string/uuid | client id in prototype |
| `types` | object/set | **at least one true**; keys = `ENTRY_TYPES[].id` (§3) |
| `description` | text | **required, min 40 chars** (`MIN_DESCRIPTION`) |
| `photos` | image[] | optional, multiple. See §6 — NOT persisted in prototype draft |
| `areas` | object/set | functional-area impact; keys = `FUNCTIONAL_AREAS[].code` (§3) |
| `areaOther` | string | shown only when `areas["Other"]` is true |
| `applicability` | enum string | **required**, one of `APPLICABILITY[].id` (§3) |

### Suggested DB shape
- `reports` (reporter_name, reporter_role, reporter_programme, status, submitted_at)
- `venues` (report_id, type, other_text, position)
- `entries` (venue_id, description, area_other, applicability, position)
- `entry_types` — pivot/JSON: which of the 5 types apply per entry
- `entry_functional_areas` — pivot/JSON: which FA codes apply per entry
- `photos` (entry_id, path, original_name) — see §6

`types` and `areas` are stored as multi-select sets. Either a pivot table or a JSON
column is fine; if JSON, store an **array of selected ids/codes**, not the
`{id: true}` map the prototype uses for convenience.

---

## 3. Reference enums (copy verbatim from `src/data.jsx`)

These lists are domain data and must match the prototype exactly. **Do not
paraphrase, reorder, or invent options.** Pull them straight from
`src/data.jsx` (`VENUE_TYPES`, `VENUE_GLYPH`, `ENTRY_TYPES`, `FUNCTIONAL_AREAS`,
`APPLICABILITY`, `MIN_DESCRIPTION`).

- **`VENUE_TYPES`** — 11 options incl. "Other (please specify)". Each has an icon
  hint in `VENUE_GLYPH`.
- **`ENTRY_TYPES`** — 5: `observation`, `best_practice`, `innovation`, `challenge`,
  `recommendation`.
- **`FUNCTIONAL_AREAS`** — 24 `{ code, name }` pairs (ACS & ACR, BRO, CAT, … plus
  "Other"). ⚠️ The `name` values were best-guess expansions — **verify against the
  real programme glossary before shipping.**
- **`APPLICABILITY`** — 5: `must_have`, `good_to_have`, `already`, `na`, `assess`,
  each with a `tone` used for colour.
- **`MIN_DESCRIPTION`** — `40`.

In Laravel, expose these from a single PHP source (config or enum classes) and pass
to the front end via Inertia shared props or a `defineProps`, so the Vue components
never hard-code option lists.

---

## 4. Validation (mirror in a Laravel FormRequest)

From `App()` in `src/app.jsx`. Errors are only shown after a submit attempt
(`showErrors`); replicate "validate on submit, then live-validate" UX.

- `reporter.name` — required.
- Each venue: `type` required; `otherText` required if `type` is the "Other" option.
- Each entry:
  - `types` — at least one selected.
  - `description` — required; trimmed length ≥ `MIN_DESCRIPTION` (40). Distinguish
    "Description is required" (empty) from "At least 40 characters" (too short).
  - `applicability` — required.
  - `areaOther` — required if `areas["Other"]` is selected.

Server-side `FormRequest` rules should be the authority; the Vue layer can mirror
them for instant feedback but must not be the only gate.

---

## 5. Component breakdown (prototype → Vue SFCs)

The React components map cleanly to Vue 3 `<script setup>` SFCs. Suggested tree:

```
resources/js/
  Pages/
    Observation/Form.vue        // ← App() in src/app.jsx (page-level state, submit)
    Observation/Success.vue     // ← SuccessScreen (src/extra.jsx)
  Components/Observation/
    ReporterCard.vue            // reporter fields block
    VenueCard.vue               // ← VenueCard (src/venue.jsx)
    EntryCard.vue               // ← EntryCard (src/venue.jsx)
    Field.vue                   // ← Field wrapper (label/hint/error) — src/ui.jsx
    PillCheck.vue               // entry-type pills
    CheckTile.vue               // functional-area checkbox tiles
    RadioPills.vue              // applicability radios
    PhotoUploader.vue           // photo-row + add button
    Stepper.vue / ReviewPanel.vue  // wizard only — optional
    Icon.vue                    // ← Icon (inline SVG set in src/ui.jsx)
```

Read `src/ui.jsx` for the shared primitives (`Field`, `Icon`, `PillCheck`,
`CheckTile`, `RadioPills`, `ProgrammeMark`) and port their markup/classes 1:1.

**Conventions for the Vue port**
- Vue 3 `<script setup>` + `<script setup>` with the Composition API.
- Use `useForm()` from `@inertiajs/vue3` for the report payload and submission.
- Child components are controlled: pass the slice down as a prop, emit changes up
  (`update:modelValue` / `v-model`), mirroring the prototype's `onChange(next)` flow.
- Keep `id`s for v-for keys; generate client uuids for new venues/entries.
- Don't introduce a component library — the prototype is hand-rolled CSS. Match it.

---

## 6. Photos

In the prototype, photos use `URL.createObjectURL` and are **deliberately excluded
from the saved draft** (stripped before `localStorage.setItem`). For Laravel:

- Upload via `multipart/form-data` (Inertia `useForm` supports file fields).
- Validate `image` mime + a sane max size; store on a disk (e.g. `public` or `s3`),
  persist `path` + `original_name` in a `photos` table.
- On draft save, you can either upload immediately and reference by id, or defer
  uploads to final submit. Pick one and be consistent.

---

## 7. Drafts & autosave

Prototype: debounced (700ms) autosave to `localStorage` under
`observation_form_draft_v1`, restoring on load; a "Saved HH:MM" stamp shows in the
masthead. For Laravel:

- Persist a draft `report` row with `status = 'draft'`; flip to `submitted` on submit.
- Debounce an autosave POST (e.g. to `reports.draft.update`) and surface the same
  "Saved …" indicator from the server response timestamp.
- Photos excluded from autosave as above unless uploaded eagerly.

---

## 8. Design tokens & styling

The visual system is driven entirely by **CSS custom properties** defined in
`Observation Form.html` and `src/data.jsx` (`THEMES`). Three theme presets exist
(`navy` "Stadium Navy" = default, `championship`, `slate`) plus accent overrides
and font pairings. Keep this token-based approach.

- Lift the `:root` tokens and the `THEMES[*].vars` maps into a single CSS/SCSS
  tokens file (or a `data-theme` attribute on a wrapper, exactly as the prototype
  does with `data-theme` / `data-density`).
- Default theme = **navy**; default font pair = **Archivo / Barlow**; default
  density = **comfortable**.
- Fonts are Google Fonts (Archivo, Barlow, Saira, Oswald, Public Sans) — load the
  same families (self-host for production if preferred).
- Reuse the exact class names and CSS from the prototype `<style>` block; scope them
  per-SFC or ship one global stylesheet. Don't restyle from scratch.
- `inkFor(hex)` in `src/app.jsx` computes readable ink for a chosen accent — port it
  if you keep accent customisation; otherwise drop accent-switching.

The theme/font/density/layout switching is a **prototype "Tweaks" affordance**
(`src/tweaks-panel.jsx`). It is **not** a required product feature — pick one theme
for the real app unless the user asks for theming.

---

## 9. Admin view

`Observation Admin.html` + `src/admin*.jsx` is a separate read/triage surface for
submitted reports (list, filter, detail). If you build the admin in Laravel, treat
those files the same way: read them for the data table columns, filters, and detail
layout, and port the markup/classes 1:1.

---

## 10. Working rules for this project

- **The prototype is the spec.** Before building any Vue piece, read the matching
  `src/*.jsx` file and reproduce its fields, order, copy, classes, and behaviour.
- Don't invent fields, options, or copy. If something's missing, ask.
- Keep field keys identical to the prototype's JS state for a clean port.
- Verify the `FUNCTIONAL_AREAS` full names against the real glossary before launch.
- Server-side validation is authoritative; Vue validation is for UX only.
