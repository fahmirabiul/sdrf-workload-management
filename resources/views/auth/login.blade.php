<x-guest-layout>
    <div class="flex flex-col items-center mb-6">
        <!-- Standalone Official Logo -->
        <a href="/">
            <img src="{{ asset('fr_logo.png') }}" alt="Logo" class="h-10 w-auto mb-4" />
        </a>
        <h1 class="text-center text-2xl font-bold text-white tracking-tight">Welcome back</h1>
        <p class="text-center text-sm text-slate-400 mt-1">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-800 rounded-lg px-4 py-3 mb-6">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email/Username -->
        <div>
            <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="name@example.com"
                   required
                   autofocus
                   autocomplete="username"
                   class="block w-full h-11 px-4 mt-1.5 text-[15px] text-white bg-slate-950/40 border border-slate-800 rounded-lg placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150 @error('email') border-rose-500 @enderror">
            @error('email')
                <p class="text-sm text-rose-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-5" x-data="{ show: false }">
            <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
            <div class="relative mt-1.5">
                <input id="password"
                       x-bind:type="show ? 'text' : 'password'"
                       name="password"
                       placeholder="Enter your password"
                       required
                       autocomplete="current-password"
                       class="block w-full h-11 px-4 pr-12 text-[15px] text-white bg-slate-950/40 border border-slate-800 rounded-lg placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150 @error('password') border-rose-500 @enderror">
                <button type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 rounded"
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
                <p class="text-sm text-rose-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between mt-6">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-800 bg-slate-950/40 text-indigo-600 focus:ring-indigo-500/30">
                <span class="text-sm text-slate-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm font-medium text-indigo-400 hover:text-indigo-300 hover:underline transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full inline-flex justify-center items-center px-6 py-3.5 mt-6 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-100 hover:shadow-lg transition-all duration-150">
            Sign In
        </button>
    </form>
</x-guest-layout>
