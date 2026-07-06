Act as an expert Fullstack Laravel & Tailwind CSS developer. I want to completely redesign the login page UI inside my project using references from the `21st-dev-magic` MCP library to ensure a premium, modern, handcrafted UI/UX that does not look like generic AI code.

Please modify the following files:
- resources/views/auth/login.blade.php
- resources/views/layouts/guest.blade.php (if it acts as the main layout wrapper)

DIRECTIONS & UI REQUIRMENTS:
1. BACKGROUND GRADIENT STYLE:
   - Apply a dynamic, modern gradient background matching the provided reference image.
   - Use a clean combination of crisp White (#FFFFFF) and the specific Indigo/Violet tone from this button class: `bg-indigo-600` (hover: `bg-indigo-500`, active: `bg-indigo-700`).
   - The background should feel airy, professional, and well-spaced (utilize glassmorphism if necessary for the card container to blend into the gradient backdrop nicely).

2. CARD FORM DESIGN (Reference Layout):
   - Mimic the clean card design from the uploaded screenshot. Center the card viewport perfectly on the screen (flex or grid).
   - Card properties: Rounded corners (`rounded-2xl` or `rounded-3xl`), soft premium drop shadows (avoid harsh black shadows, use `shadow-xl shadow-indigo-100/50`).
   - Fields layout: Stacked vertically with comfortable padding (`p-8` or `p-10`).

3. LOGO REPLACEMENT:
   - Completely remove the generic placeholder "S" logo.
   - Replace it with the official logo component or image asset used in the navbar (render it cleanly as a raw SVG or standard asset path, standalone without any accompanied branding text).

4. AUTH FORM RESTRICTIONS:
   - KEEP existing Laravel Blade directives intact (e.g., `<form method="POST" action="{{ route('login') }}">`, `@csrf`, `x-model` or `old()`, and error validation alerts `@error`).
   - REMOVE any "Sign in with Google" or social OAuth buttons entirely.
   - REMOVE any "Sign up", "Create account", or registration anchor links. This is a closed corporate portal.

5. INPUT FIELDS & SUBMIT BUTTON STYLE:
   - Email/Username & Password fields must have smooth transiton borders (`focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500`).
   - The primary login/submit button must match this exact design pattern:
     class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-100 hover:shadow-lg transition-all duration-150"

Please fetch the components via `21st-dev-magic` to inject premium UX micro-interactions if applicable, then rewrite the login page with clean, scannable Tailwind utility classes.