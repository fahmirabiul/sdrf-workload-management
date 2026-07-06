# [ANCHORS]
- [welcome.blade.php](file:///d:/code_personal/sdrf/resources/views/welcome.blade.php)

# [LOW-LEVEL INSTRUCTIONS]

## 1. Analisis Arsitektur
- **Bug Diagnostik**: Elemen preview card tidak muncul (opacity 0) karena bentrokan animasi CSS. Kelas `.animate-float` menimpa properti `animation` dari `.animate-fade-in`, sehingga efek `fadeIn` tidak pernah berjalan tetapi `opacity: 0` tetap berlaku.
- **Solusi**: Memisahkan kedua animasi tersebut dengan membungkus elemen preview card di dalam sebuah container baru yang bertugas menjalankan `animate-fade-in delay-300`, sedangkan card di dalamnya menjalankan `animate-float`.
- **Peningkatan Visual (SaaS Style)**:
  - Mengubah `.glow-effect::after` menjadi neon blur background gradient (`#6366f1` ke `#a855f7`) untuk efek backglow modern di belakang preview card.
  - Menambahkan radial overlay mask pada grid background (`grid-bg`) agar garis grid memudar (fade-out) secara halus di area tepi layar, menyerupai desain landing page SaaS premium.

## 2. Modifikasi Kode File Jangkar

### modify: [welcome.blade.php](file:///d:/code_personal/sdrf/resources/views/welcome.blade.php)

#### Langkah 2.1: Perbarui Gaya Backglow dan Masking Background Grid
Ubah kode dalam tag `<style>` pada bagian `.glow-effect` dan tambahkan masker gradien:
- **SEBELUM**:
  ```css
              .glow-effect {
                  position: relative;
              }
              .glow-effect::after {
                  content: '';
                  position: absolute;
                  inset: 0;
                  border-radius: inherit;
                  box-shadow: 0 0 25px rgba(99, 102, 241, 0.15);
                  opacity: 0;
                  transition: opacity 0.3s ease;
                  z-index: -1;
              }
              .glow-effect:hover::after {
                  opacity: 1;
              }
  ```
- **SESUDAH**:
  ```css
              .glow-effect {
                  position: relative;
              }
              .glow-effect::before {
                  content: '';
                  position: absolute;
                  inset: -2px;
                  border-radius: inherit;
                  background: linear-gradient(135deg, #6366f1, #a855f7, #3b82f6);
                  z-index: -2;
                  opacity: 0.12;
                  filter: blur(16px);
                  transition: opacity 0.3s ease;
              }
              .glow-effect::after {
                  content: '';
                  position: absolute;
                  inset: 0;
                  border-radius: inherit;
                  box-shadow: 0 0 35px rgba(99, 102, 241, 0.25);
                  opacity: 0;
                  transition: opacity 0.3s ease;
                  z-index: -1;
              }
              .glow-effect:hover::before {
                  opacity: 0.25;
              }
              .glow-effect:hover::after {
                  opacity: 1;
              }
              .radial-mask {
                  mask-image: radial-gradient(circle at center, black 60%, transparent 100%);
                  -webkit-mask-image: radial-gradient(circle at center, black 60%, transparent 100%);
              }
  ```

#### Langkah 2.2: Pasang Masking Grid pada Hero Section
Ubah kontainer terluar Hero Section (baris 153) untuk memasang masker radial:
- **SEBELUM**:
  ```html
  <section class="relative overflow-hidden grid-bg pt-16 pb-20 lg:pt-24 lg:pb-28 border-b border-slate-100">
  ```
- **SESUDAH**:
  ```html
  <section class="relative overflow-hidden grid-bg radial-mask pt-16 pb-20 lg:pt-24 lg:pb-28 border-b border-slate-100">
  ```

#### Langkah 2.3: Perbaiki Pembungkus Preview Card (Pemisahan Animasi)
Ubah pembungkus preview card di bawah tag `<!-- Right: Dashboard Preview Card -->` (mulai baris 234):
- **SEBELUM**:
  ```html
                          <!-- Right: Dashboard Preview Card -->
                          <div class="lg:col-span-7 mt-12 lg:mt-0">
                              <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100/80 overflow-hidden glow-effect animate-fade-in delay-300 animate-float">
                                  <!-- Top Bar (Mock OS / App Window) -->
  ```
- **SESUDAH**:
  ```html
                          <!-- Right: Dashboard Preview Card -->
                          <div class="lg:col-span-7 mt-12 lg:mt-0">
                              <div class="animate-fade-in delay-300">
                                  <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100/80 overflow-hidden glow-effect animate-float">
                                      <!-- Top Bar (Mock OS / App Window) -->
  ```
*(Catatan: Jangan lupa menambahkan tag penutup `</div>` cadangan di bagian bawah block Preview Card sebelum baris penutup `<!-- Right: Dashboard Preview Card -->` selesai).*
Contoh penutup:
- **SEBELUM**:
  ```html
                                      </div>
                                  </div>
                              </div>
                          </div>
  ```
- **SESUDAH**:
  ```html
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  ```
  *(Catatan: Sesuaikan struktur tag penutup untuk menyelaraskan container baru).*
