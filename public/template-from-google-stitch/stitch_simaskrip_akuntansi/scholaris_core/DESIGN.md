# Design System Specification: The Academic Curator

## 1. Overview & Creative North Star
The "Creative North Star" for this design system is **The Digital Curator**. 

In the context of an Accounting Thesis Management System, we must move beyond the "spreadsheet" aesthetic. We are designing a high-end, editorial experience that treats academic research with the same prestige as a professional audit or a legal brief. 

The system rejects the "boxed-in" nature of enterprise software. Instead of rigid grids and heavy borders, it utilizes **Organic Structuralism**—a philosophy where hierarchy is defined by tonal layering, generous white space, and sophisticated typography. This creates an environment that feels authoritative yet breathable, reducing the cognitive load on students and faculty during the high-stakes thesis process.

---

## 2. Colors: Tonal Architecture
The palette is rooted in a Deep Blue (`primary`) for trust, supported by a sophisticated range of greys and academic-professional status colors.

### The "No-Line" Rule
**Explicit Instruction:** Designers are prohibited from using 1px solid borders to section off the UI. Containers and sections must be defined solely through background shifts. For example, a `surface-container-low` section sitting on a `surface` background provides all the definition needed.

### Surface Hierarchy & Nesting
Treat the interface as a physical stack of fine paper. 
- **Base Layer:** `surface` (#F8F9FA)
- **Secondary Sectioning:** `surface-container-low` (#F1F4F6)
- **Primary Content Cards:** `surface-container-lowest` (#FFFFFF)
- **Active/Hover States:** `surface-container-high` (#E3E9EC)

### The "Glass & Gradient" Rule
To elevate the "Academic" feel into "High-End," use glassmorphism for floating elements (like the Progress Stepper or Navigation). Apply a `backdrop-blur: 12px` to a semi-transparent `surface-container-lowest`. 

For Primary CTAs, use a subtle linear gradient from `primary` (#4059AA) to `primary_dim` (#334D9D) at a 135-degree angle. This adds "soul" and depth that prevents the UI from looking like a flat template.

---

## 3. Typography: Editorial Authority
We utilize **Inter** to bridge the gap between technical data-centricity and academic elegance. 

*   **Display & Headline Scale:** Use `display-md` and `headline-lg` for dashboard greetings or thesis titles. These should have a slight tracking (letter-spacing) reduction of -0.02em to feel more "editorial."
*   **The Title Tier:** `title-lg` and `title-md` are your primary navigation and section headers. They represent the "Statement of Truth."
*   **Body & Label Scale:** Data density is handled by `body-md`. For metadata (dates, student IDs), use `label-md` in `on_surface_variant` to create a clear secondary visual layer.

**Hierarchy Tip:** Contrast is achieved through weight and color (e.g., `primary` for titles vs. `on_surface_variant` for body text), not just size.

---

## 4. Elevation & Depth: Tonal Layering
Traditional drop shadows are largely replaced by **Tonal Layering**.

*   **The Layering Principle:** Place a `surface-container-lowest` card on top of a `surface-container-low` background. This creates a "soft lift" that is easier on the eyes than high-contrast shadows.
*   **Ambient Shadows:** If a card requires a floating state (e.g., a dragged file or a modal), use a shadow with a blur radius of 32px and 4% opacity, using the `on_surface` color as the shadow tint.
*   **The "Ghost Border" Fallback:** If accessibility requires a container boundary, use a "Ghost Border": `outline-variant` at 15% opacity. Never use a 100% opaque border.

---

## 5. Components: Functional Minimalism

### Modern Cards
*   **Style:** No borders. Background: `surface-container-lowest`. 
*   **Nesting:** Use `surface-container-low` inside a card to highlight a specific data point (e.g., a "Current Status" box).
*   **Radius:** `lg` (0.5rem).

### Tables & Data Grids
*   **Rule:** Forbid horizontal and vertical divider lines.
*   **Separation:** Use `surface-container-low` for the header row. Use a subtle `surface-container-lowest` for alternating rows (zebra striping) or simply rely on `8px` of vertical padding between row items.
*   **Pagination:** Use "Ghost" buttons (text-only) with `primary` color for active states.

### Horizontal Progress Stepper
*   **Logic:** This is the "spine" of the thesis process. 
*   **Style:** Use a `surface-container-high` track. Completed steps use `primary`. 
*   **Interactive State:** The "Active" step should utilize a `primary_container` glow and `headline-sm` typography to anchor the user's focus.

### Forms & Inputs
*   **Input Fields:** Use a "filled" style with `surface-container-high` background. No border. Upon focus, transition to a `primary` 2px bottom-accent line.
*   **Status Chips:** 
    *   *Approved:* `on_primary_container` text on `primary_fixed` background.
    *   *Rejected:* `on_error_container` text on `error_container` background.
    *   *In-Progress:* Yellow (#FFC107) text at 90% opacity with a subtle blur background.

---

## 6. Do’s and Don’ts

### Do
*   **Do** use asymmetrical margins (e.g., more padding on the left than the right in sidebars) to create a custom, high-end feel.
*   **Do** use "Breathing Room." If you think there is enough white space, add 8px more.
*   **Do** use `primary_fixed_dim` for non-destructive actions to maintain the academic tonal range.

### Don’t
*   **Don't** use black (#000000). Use `on_surface` (#2B3437) for all primary text to maintain a softer, premium contrast.
*   **Don't** use standard "Bootstrap" blue. Stick strictly to the `primary` (#4059AA) academic blue.
*   **Don't** use cards inside cards unless you shift the background color tier. Nesting the same color surface is forbidden as it flattens the hierarchy.