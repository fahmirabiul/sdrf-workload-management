---
name: SDRF V2 Systematic Design
colors:
  surface: '#fcf8ff'
  surface-dim: '#dcd8e5'
  surface-bright: '#fcf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f5f2ff'
  surface-container: '#f0ecf9'
  surface-container-high: '#eae6f4'
  surface-container-highest: '#e4e1ee'
  on-surface: '#1b1b24'
  on-surface-variant: '#464555'
  inverse-surface: '#302f39'
  inverse-on-surface: '#f3effc'
  outline: '#777587'
  outline-variant: '#c7c4d8'
  surface-tint: '#4d44e3'
  primary: '#3525cd'
  on-primary: '#ffffff'
  primary-container: '#4f46e5'
  on-primary-container: '#dad7ff'
  inverse-primary: '#c3c0ff'
  secondary: '#505f76'
  on-secondary: '#ffffff'
  secondary-container: '#d0e1fb'
  on-secondary-container: '#54647a'
  tertiary: '#7e3000'
  on-tertiary: '#ffffff'
  tertiary-container: '#a44100'
  on-tertiary-container: '#ffd2be'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e2dfff'
  primary-fixed-dim: '#c3c0ff'
  on-primary-fixed: '#0f0069'
  on-primary-fixed-variant: '#3323cc'
  secondary-fixed: '#d3e4fe'
  secondary-fixed-dim: '#b7c8e1'
  on-secondary-fixed: '#0b1c30'
  on-secondary-fixed-variant: '#38485d'
  tertiary-fixed: '#ffdbcc'
  tertiary-fixed-dim: '#ffb695'
  on-tertiary-fixed: '#351000'
  on-tertiary-fixed-variant: '#7b2f00'
  background: '#fcf8ff'
  on-background: '#1b1b24'
  surface-variant: '#e4e1ee'
typography:
  display-xl:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '800'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  headline-sm:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: '1'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 64px
  container-max: 1280px
  gutter: 24px
---

## Brand & Style

The design system is engineered for high-performance enterprise SaaS environments, specifically optimized for IT workload management and resource allocation. The brand personality is **utilitarian, precise, and dependable**, prioritizing cognitive ease over decorative flair.

The visual style follows a **Corporate / Modern** aesthetic. It utilizes a highly structured grid, generous whitespace to prevent data-density fatigue, and a systematic application of color to denote status and priority. The interface achieves depth through subtle tonal layering and hairline borders rather than heavy shadows, ensuring the UI remains crisp on low-dpi office monitors while looking premium on high-resolution displays.

## Colors

This design system utilizes a functional palette designed to guide user attention through information hierarchy. 

- **Core Palette**: The primary Indigo is reserved for high-intent actions and active states. The Neutral palette (Slate) is the backbone of the system, used for text, borders, and subtle backgrounds to keep the interface grounded.
- **Surface Strategy**: We use a "Level 0/Level 1" approach. **Slate-50** acts as the canvas (Level 0), while **Pure White** cards and containers (Level 1) float above it with hairline **Slate-200** borders to create distinct work zones.
- **Semantic Feedback**: Status colors are used sparingly but consistently. Emerald denotes "Available/Healthy," Amber signals "Pending/Risk," Rose indicates "Overload/Critical Error," and Sky Blue is used for technical "Analysis/In-progress" data points.

## Typography

The typography system relies exclusively on **Inter** for its exceptional legibility in data-heavy SaaS interfaces. 

- **Headlines**: Use heavy weights (Bold/ExtraBold) with slight negative letter-spacing for a confident, architectural feel.
- **Body**: Standard body text is set at 16px to ensure accessibility. For dense data tables or sidebars, the 14px `body-sm` is preferred.
- **Labels**: Small labels and status tags use a SemiBold weight to remain legible at reduced sizes. Capitalization is used for `label-sm` to distinguish metadata from content.

## Layout & Spacing

This design system uses an **8px linear scale** for consistent vertical and horizontal rhythm. 

- **Grid Model**: A 12-column fluid grid is used for main content areas. On Desktop (1280px+), containers have a max-width to maintain line-length readability.
- **Breakpoints**: 
    - **Mobile (<768px)**: 4 columns, 16px margins.
    - **Tablet (768px - 1024px)**: 8 columns, 24px margins.
    - **Desktop (>1024px)**: 12 columns, 24px-32px margins.
- **Density**: The system favors "Generous" density for dashboards to reduce visual clutter, while "Compact" density is applied only to data tables and list views to maximize vertical information visibility.

## Elevation & Depth

The design system conveys hierarchy through **Tonal Layers** and **Low-Contrast Outlines**.

1.  **Level 0 (Base)**: `#F8FAFC` (Slate-50). Used for the main application background.
2.  **Level 1 (Cards)**: `#FFFFFF` (White). Used for main content containers. It features a 1px border of `#E2E8F0` and a very soft, diffused shadow (`0px 1px 3px rgba(0,0,0,0.1)`).
3.  **Level 2 (Modals/Popovers)**: `#FFFFFF` (White). These use a more pronounced shadow to indicate focus and separation from the layout (`0px 10px 15px -3px rgba(0,0,0,0.1)`).

Shadows should be "clean"—no heavy blacks. Use transparent Slate or Indigo tints in shadow values to keep the UI feeling light.

## Shapes

The shape language is **Rounded (8px base)**. This provides a modern, approachable feel while maintaining the structural integrity required for an enterprise tool.

- **Primary Elements**: Buttons, Input fields, and Cards use the base 8px (`rounded-md`).
- **Large Elements**: Section containers or hero blocks use 16px (`rounded-lg`).
- **Small Elements**: Badges and tags use a full pill-shape (999px) to distinguish them from interactive buttons.
- **Borders**: All borders are strictly 1px unless used as a focus state, which increases to 2px.

## Components

- **Buttons**:
    - **Primary**: Solid Indigo (#4F46E5) with white text. 8px corner radius.
    - **Secondary**: White background with Slate-200 border and Slate-700 text.
    - **Ghost**: No background/border, Slate-600 text; used for secondary actions like "Cancel."
- **Input Fields**: 1px Slate-200 border, 8px padding, 8px radius. Active state uses 2px Indigo border with a subtle 4px Indigo glow (10% opacity).
- **Cards**: White surface, 1px Slate-200 border, 24px internal padding. Headers within cards should have a subtle bottom border.
- **Chips/Badges**: Small (12px) semi-bold text. Use 10% opacity backgrounds of the status colors (e.g., 10% Emerald for "Success") with a solid 100% color text for high legibility.
- **Progress Bars**: 8px height, rounded ends. Background is Slate-100; "Fill" uses the primary or semantic status color based on the data represented.
- **Lists**: Clean rows with 12px vertical padding, separated by hairline Slate-100 dividers. Hover states should use a subtle Slate-50 background tint.