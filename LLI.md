# Panduan Low-Level Instructions (LLI) untuk Redesign welcome.blade.php

Dokumen ini berisi instruksi teknis langkah-demi-langkah untuk memodifikasi UI `resources/views/welcome.blade.php` agar tampil lebih modern, elegan (tidak kaku), dan premium (mencegah kesan AI slop). 

## 1. Modifikasi Tipografi (Font Family)
**Lokasi:** Baris 11 - 21 (`<head>`)
- Ubah font utama agar lebih modern dan berkarakter. Kita gunakan `Outfit` untuk teks tebal/heading dan `Inter` untuk body agar lebih bersih dan kontemporer.
  **Ganti tag `<link>` font dan `<style>` menjadi:**
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  
  <style>
      body {
          font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      }
      h1, h2, h3, h4, h5, h6, .font-display {
          font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      }
      /* (Tetahankan CSS grid-bg dan keyframes lainnya di sini) */
  ```
  *(Pastikan CSS `grid-bg`, `@keyframes`, `pulse-dot`, dan efek glow yang sudah ada tetap dipertahankan setelah deklarasi font).*

## 2. Modifikasi Header & Navigasi
**Lokasi:** Baris 77 - 166 (`<header>`)
- **Logo:** Buat logo badge lebih elegan dengan efek glass/subtle border.
  **Ganti blok logo menjadi:**
  ```html
  <a href="/" class="flex items-center gap-3 group">
      <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-white font-bold text-sm shadow-[0_2px_10px_rgba(0,0,0,0.1)] border border-slate-700/50 group-hover:scale-105 transition-transform">
          R
      </div>
      <span class="text-lg font-bold text-slate-900 tracking-tight font-display">SDRF <span class="text-slate-400 font-medium">V2</span></span>
  </a>
  ```
- **Menu Desktop & Auth:** Buat navigasi lebih clean dan tombol aksi utama lebih modern.
  **Ganti bagian `@auth ... @endauth` desktop menjadi:**
  ```html
  @auth
      <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors mr-4">Dashboard</a>
  @else
      <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors mr-4">Masuk</a>
      <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-full hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/20 transition-all duration-200">Mulai Sekarang</a>
  @endauth
  ```

## 3. Modifikasi Hero Section (Tipografi Besar & Estetika)
**Lokasi:** Sekitar baris 183 - 190 (`<h1>` dan `<p>`)
- **Headline:** Buat tipografi jauh lebih kekinian dengan `tracking-tighter`, ukuran proporsional, dan penekanan warna yang tidak pasaran.
  **Ganti `<h1 ...>` dan tag `<p>` deskripsi menjadi:**
  ```html
  <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-bold text-slate-900 tracking-tighter leading-[1.05] text-balance">
      Optimalkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 via-slate-600 to-slate-900">Transparansi</span><br/> & Kapasitas Tim IT
  </h1>
  
  <p class="text-lg sm:text-xl text-slate-500 leading-relaxed max-w-2xl font-light tracking-wide mt-6">
      Kelola siklus hidup permintaan software melalui FLOWCAST. Monitor estimasi beban kerja programmer secara proporsional dan resolusi konflik secara real-time.
  </p>
  ```
- **Tombol Hero (CTA):** Jadikan tombol aksi di hero lebih "premium" (bukan biru generik).
  Ubah style tombol utama (Buka Dashboard / Mulai Pengajuan) menjadi:
  ```html
  class="inline-flex items-center px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.2)] hover:-translate-y-0.5 transition-all duration-200"
  ```
  Dan tombol sekunder (Pelajari Fitur) menjadi:
  ```html
  class="inline-flex items-center px-7 py-3.5 bg-white border border-slate-200/80 text-slate-700 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-300 font-medium rounded-full transition-all duration-200 shadow-sm"
  ```

## 4. Modifikasi Audit Trail (Elegan & High-Contrast)
**Lokasi:** Sekitar baris 428 (Kontainer Audit Trail)
- Berikan kesan dark-mode yang elegan (bukan hitam murni) dengan subtle borders.
  **Ganti class kontainer tersebut menjadi:**
  ```html
  <div class="bg-slate-950 rounded-[2rem] p-8 border border-slate-800 shadow-2xl shadow-slate-900/50">
  ```
- **Heading Log:** Ubah heading text `Audit Trail & Log Riwayat Proyek` menjadi `font-display font-semibold text-white tracking-wide`.
- **Log Items (3 card di dalamnya):** 
  Ganti class wrapper tiap item menjadi:
  ```html
  <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-800/80 text-sm flex flex-col gap-1.5 backdrop-blur-sm hover:bg-slate-800 transition-colors">
  ```
- Sesuaikan teks:
  - Judul Status: `text-slate-200 font-medium`
  - Waktu: `text-slate-500 text-xs`
  - Deskripsi: `text-slate-400 leading-relaxed text-xs`

## 5. Modifikasi Layout Metrics (Minimalist & Clean)
**Lokasi:** Sekitar baris 473 (Grid Metrics)
- Hilangkan sekat kaku dan buat metrik terlihat monumental dengan tipografi display.
  **Ganti struktur Grid Metric menjadi:**
  ```html
  <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-4 w-full">
      <div class="flex flex-col items-center justify-center p-6 text-center">
          <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">20<span class="text-3xl text-slate-400">Pts</span></p>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Batas Beban Kerja</p>
      </div>
      <div class="flex flex-col items-center justify-center p-6 text-center">
          <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">100<span class="text-3xl text-slate-400">%</span></p>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Audit Terlacak</p>
      </div>
      <div class="flex flex-col items-center justify-center p-6 text-center">
          <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">3</p>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Hak Akses Sistem</p>
      </div>
      <div class="flex flex-col items-center justify-center p-6 text-center">
          <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">24<span class="text-3xl text-slate-400">/7</span></p>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Akses Terbuka</p>
      </div>
  </div>
  ```

## 6. Modifikasi Bagian CTA (Call to Action) Bawah
**Lokasi:** Sekitar baris 501
- Ubah desain CTA menjadi bentuk yang lebih organik (rounded ekstrem) dengan gaya monokrom elegan.
  **Ganti tag `<div>` pembungkus CTA menjadi:**
  ```html
  <div class="bg-slate-950 rounded-[3rem] sm:rounded-[4rem] p-12 sm:p-24 relative overflow-hidden shadow-2xl max-w-5xl mx-auto flex flex-col items-center text-center">
      <!-- Ornamen background halus -->
      <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800/40 via-transparent to-transparent pointer-events-none"></div>
  ```
- **Heading CTA:**
  ```html
  <h2 class="font-display text-4xl sm:text-5xl font-bold text-white tracking-tighter relative z-10">
      Mulai Kelola Workload IT Anda
  </h2>
  <p class="mt-6 text-slate-400 text-lg max-w-xl mx-auto leading-relaxed relative z-10">
      Gunakan kredensial akun IT Anda untuk mengelola penugasan, menyetujui tiket, atau mengirim ulasan UAT.
  </p>
  ```
- **Tombol CTA:**
  Ganti kelas tombol menjadi putih bersih:
  ```html
  <a href="{{ route('login') }}" class="relative z-10 inline-flex items-center px-8 py-4 bg-white text-slate-900 hover:bg-slate-100 font-semibold rounded-full shadow-[0_0_40px_rgba(255,255,255,0.1)] hover:scale-105 transition-all duration-300">
      Masuk Sekarang <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
  </a>
  ```

## 7. Modifikasi Footer Lengkap
**Lokasi:** Sekitar baris 538 (`<footer>`)
- Buat footer terlihat modern, bersih, dan berstruktur ala korporat SaaS terkini.
  **Ganti isi `<div class="max-w-7xl ...">` di dalam Footer menjadi:**
  ```html
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 pt-4">
          <!-- Kolom 1: Brand -->
          <div class="md:col-span-2">
              <a href="/" class="flex items-center gap-3 mb-6 group">
                  <div class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center text-white font-bold text-sm border border-slate-700 group-hover:bg-slate-700 transition-colors">
                      R
                  </div>
                  <span class="font-display text-xl font-bold text-white tracking-tight">SDRF V2</span>
              </a>
              <p class="text-sm text-slate-400 leading-relaxed max-w-md font-light">
                  Sistem Digital Request Form v2.0 merupakan platform resmi UPT Teknologi Informasi & Komunikasi untuk pengelolaan alokasi SDM dan transparansi pengerjaan software secara presisi.
              </p>
          </div>
          <!-- Kolom 2: Navigasi -->
          <div>
              <h3 class="text-slate-100 font-medium mb-6 text-sm tracking-wide">Navigasi</h3>
              <ul class="space-y-4 text-sm text-slate-400">
                  <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                  <li><a href="#" class="hover:text-white transition-colors">Panduan Sistem</a></li>
                  <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                  <li><a href="#" class="hover:text-white transition-colors">Hubungi Kami</a></li>
              </ul>
          </div>
          <!-- Kolom 3: Tautan Terkait -->
          <div>
              <h3 class="text-slate-100 font-medium mb-6 text-sm tracking-wide">Tautan Terkait</h3>
              <ul class="space-y-4 text-sm text-slate-400">
                  <li><a href="#" class="hover:text-white transition-colors">Website Universitas</a></li>
                  <li><a href="#" class="hover:text-white transition-colors">Portal Akademik</a></li>
                  <li><a href="#" class="hover:text-white transition-colors">Helpdesk TIK</a></li>
              </ul>
          </div>
      </div>
      <!-- Bottom Bar -->
      <div class="border-t border-slate-800/60 pt-8 pb-12 flex flex-col md:flex-row items-center justify-between gap-4">
          <p class="text-xs text-slate-500">
              &copy; {{ date('Y') }} UPT Teknologi Informasi & Komunikasi. Hak cipta dilindungi.
          </p>
          <div class="flex items-center gap-8 text-xs text-slate-500">
              <a href="#" class="hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
              <a href="#" class="hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
          </div>
      </div>
  </div>
  ```