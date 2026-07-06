# [ANCHORS]
- [app.blade.php](file:///d:/code_personal/sdrf/resources/views/layouts/app.blade.php)
- [navigation.blade.php](file:///d:/code_personal/sdrf/resources/views/layouts/navigation.blade.php)

# [LOW-LEVEL INSTRUCTIONS]

## 1. Analisis Arsitektur
- Agar seluruh kotak konten putih (`bg-white rounded`) dan header sejajar secara presisi di batas kiri dan kanan dengan bilah navigasi (`navigation.blade.php`) di semua ukuran layar, nilai horizontal margin (`mx`) pada navigasi dan horizontal padding (`px`) pada kontainer `<main>` di `layouts/app.blade.php` harus diselaraskan secara simetris:
  - Pada layar kecil: padding horizontal `<main>` diset `px-4` untuk mencocokkan `mx-4` dari navigasi.
  - Pada layar sedang: padding horizontal `<main>` diset `sm:px-6` untuk mencocokkan `sm:mx-6` dari navigasi.
  - Pada layar besar: padding horizontal `<main>` diset `lg:px-8` untuk mencocokkan `lg:mx-8` dari navigasi.
  - Pada layar ekstra besar (>=1280px): padding horizontal `<main>` diset `xl:px-0` (0 padding) untuk mencocokkan `xl:mx-auto` (di mana navigasi mencapai lebar maksimal `max-w-7xl` tanpa margin tambahan).

## 2. Modifikasi Kode File Jangkar (Junction)

### modify: [app.blade.php](file:///d:/code_personal/sdrf/resources/views/layouts/app.blade.php)
Ubah pembungkus utama `<main>` pada baris ke-22:
- **SEBELUM**:
  ```html
  <main class="max-w-7xl mx-auto p-6 transition-all duration-300">
  ```
- **SESUDAH**:
  ```html
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-0 py-6 transition-all duration-300">
  ```

### modify: [navigation.blade.php](file:///d:/code_personal/sdrf/resources/views/layouts/navigation.blade.php)
Ubah kelas pembungkus navigasi `<nav>` pada baris ke-1 untuk menyelaraskan breakpoint margin:
- **SEBELUM**:
  ```html
  <nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-2xl shadow-soft sticky top-4 mx-4 xl:mx-auto max-w-7xl z-50 rounded-2xl border-0">
  ```
- **SESUDAH**:
  ```html
  <nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-2xl shadow-soft sticky top-4 mx-4 sm:mx-6 lg:mx-8 xl:mx-auto max-w-7xl z-50 rounded-2xl border-0">
  ```
