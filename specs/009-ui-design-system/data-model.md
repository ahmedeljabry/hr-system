# Data Model & Components: Design System

Since this feature relies strictly on the presentation layer, standard database Entity Relationship Diagrams (ERDs) do not apply. Instead, this document outlines the **Blade Component schema and Props**.

## Blade Component Schemas

### 1. `x-button`
A reusable standardized button component.
* **Path:** `resources/views/components/button.blade.php`
* **Props:**
  * `type` (e.g., submit, button, reset) - Default: `submit`
  * `variant` (primary, secondary, danger) - Controls color mapping.
  * `size` (sm, md, lg) - Controls padding tracking.

### 2. `x-input`
A standardized text input with localized padding.
* **Path:** `resources/views/components/input.blade.php`
* **Props:**
  * `id`
  * `type` - Default: `text`
  * `disabled` - Boolean.
  * `error` - Maps to the validation state for red borders.

### 3. `x-card`
A structural container introducing glassmorphism or shadows.
* **Path:** `resources/views/components/card.blade.php`
* **Props:** None required; acts as a slot wrapper `<x-card>{{ $slot }}</x-card>`.

## CSS Token Definitions (`app.css`)
We will configure base HSL variables to override `gray-900`, `blue-600`, etc., ensuring we escape the default generic Tailwind color space.

```css
@layer base {
  :root {
    --primary: 221 83% 53%; /* Example Blue */
    --surface: 0 0% 100%;
    --text-main: 222 47% 11%;
  }
}
```
