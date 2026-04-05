# Technical Research & Decisions: Design System & Core Theming

## Overview
This document consolidates the technical research and architecture choices to implement the UI Design System (Phase 009) using Laravel Blade and TailwindCSS. Since the specification is clear, this acts as the definitive design record.

## 1. Deep Integration of CSS Logical Properties
- **Decision:** Utilize Tailwind's native logical properties (e.g., `ps-4` instead of `pl-4` / `pr-4`) for all margins, paddings, and borders.
- **Rationale:** Strict adherence to Constitution Principle IV (Bilingual UI). Instead of duplicating logic (`ltr:pl-4 rtl:pr-4`), logical properties automatically adapt to the `dir="rtl"` attribute defined in `app.blade.php`.
- **Alternatives Considered:** Writing custom RTL CSS (rejected due to excessive maintenance), using a Laravel RTL package (rejected as Tailwind's native support is sufficient).

## 2. Component Abstraction Strategy
- **Decision:** Utilize Anonymous Blade Components for atomic UI elements.
- **Rationale:** Laravel's anonymous components (`<x-button>`, `<x-input>`) seamlessly integrate with Tailwind without requiring backend PHP class definitions. This keeps the components "Thin" and isolated to the view layer.
- **Alternatives Considered:** Livewire components (rejected for atomic presentation layers as overhead is unnecessary unless reactivity is strictly needed), AlpineJS-only templates (rejected because Alpine is ideal for interaction, not structural abstraction).

## 3. Theming & Dark Mode Tokens
- **Decision:** Configure Tailwind CSS variables within `resources/css/app.css` using the `@layer base` directive.
- **Rationale:** Aligns with FR-007 (CSS tokens without a UI toggle). We will map colors like `--color-primary` to specific HSL values and configure `tailwind.config.js` to consume these tokens. 
- **Alternatives Considered:** Hardcoding HEX values in the config (rejected as it breaks future dark mode support).

## 4. Typography Implementation
- **Decision:** Load `Inter` and `Outfit` via Google Fonts within the base layout layout and define them as sans-serif targets in the Tailwind config.
- **Rationale:** Immediately fulfills SC-001 while supporting standard browser fallbacks without complex local font hosting at this stage.
