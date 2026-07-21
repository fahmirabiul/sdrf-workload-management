<x-guest-layout>
    <div class="flex flex-col items-center w-full">
        <!-- A. Header (Di Luar Card) -->
        <div class="flex flex-col items-center">
            <!-- Ikon Logo -->
            <div class="bg-indigo-600 rounded-xl w-12 h-12 flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <!-- Judul Utama -->
            <h1 class="mt-4 text-2xl font-bold text-slate-900">FLOWCAST</h1>
            <!-- Subtitle -->
            <p class="text-sm font-medium text-slate-500">Workload Management System</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="w-full max-w-md text-sm text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3 mt-6">
                {{ session('status') }}
            </div>
        @endif

        <!-- B. Card Utama (Form Login) -->
        <div class="w-full max-w-md bg-white border border-slate-200/60 shadow-lg shadow-slate-200/40 rounded-2xl p-8 mt-6">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Selamat Datang Kembali</h2>
                <p class="text-sm text-slate-500">Masuk ke akun Anda untuk melanjutkan pengelolaan workload.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Form Group - Email/Username -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <!-- Mail Icon -->
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@perusahaan.ac.id"
                               required
                               autofocus
                               autocomplete="email"
                               class="block w-full h-11 pl-10 px-4 text-[15px] text-slate-900 bg-slate-50 border border-slate-200 rounded-lg placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150 @error('email') border-rose-500 @enderror">
                    </div>
                    @error('email')
                        <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Group - Kata Sandi (Password) -->
                <div class="mt-5" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <!-- Lock Icon -->
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password"
                               x-bind:type="show ? 'text' : 'password'"
                               name="password"
                               placeholder="........"
                               required
                               autocomplete="current-password"
                               class="block w-full h-11 pl-10 pr-10 px-4 text-[15px] text-slate-900 bg-slate-50 border border-slate-200 rounded-lg placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150 @error('password') border-rose-500 @enderror">
                        <button type="button"
                                @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 rounded"
                                :aria-label="show ? 'Hide password' : 'Show password'"
                                :aria-pressed="show">
                            <!-- Eye (show) -->
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <!-- Eye-off (hide) -->
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-5">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-slate-300 bg-white text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition-colors">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <!-- Tombol Masuk -->
                <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 px-6 py-3.5 mt-6 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-xl shadow-sm transition-all duration-150">
                    Masuk
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </button>

                <!-- Divider & Akses IT Support -->
                <hr class="my-6 border-slate-100">
                <p class="text-center text-sm text-slate-500">
                    Belum punya akun? <a href="#" class="text-indigo-600 font-bold hover:underline">Hubungi IT Support</a>
                </p>
            </form>
        </div>

        <!-- C. Footer (Di Luar Card) -->
        <div class="mt-8 text-center flex flex-col items-center">
            <div class="text-xs font-semibold text-slate-600 space-x-2">
                <a href="#" class="hover:text-slate-900 transition-colors">Status</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-slate-900 transition-colors">Dokumentasi</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-slate-900 transition-colors">Dukungan</a>
            </div>
            <p class="text-xs text-slate-500 mt-2">
                &copy; {{ date('Y') }} FLOWCAST. IT Workload Management &mdash; University IT Department.
            </p>
        </div>
    </div>
</x-guest-layout>
