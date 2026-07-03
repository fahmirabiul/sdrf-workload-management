<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="text-sm text-emerald-700 bg-emerald-50 rounded-lg px-4 py-3 mb-6">
            {{ session('status') }}
        </div>
    @endif

    <h1 class="text-2xl font-semibold text-[#1e293b] tracking-tight">Welcome back</h1>
    <p class="text-sm text-[#64748b] mt-1 mb-8">Sign in to your account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="text-sm font-medium text-[#1e293b]">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="name@example.com"
                   required
                   autofocus
                   autocomplete="username"
                   class="block w-full h-11 px-4 mt-1.5 text-[15px] text-[#1e293b] bg-white border border-[#e2e8f0] rounded-lg placeholder:text-[#94a3b8] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-150 @error('email') border-[#dc2626] @enderror">
            @error('email')
                <p class="text-sm text-[#dc2626] mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-5" x-data="{ show: false }">
            <label for="password" class="text-sm font-medium text-[#1e293b]">Password</label>
            <div class="relative mt-1.5">
                <input id="password"
                       x-bind:type="show ? 'text' : 'password'"
                       name="password"
                       placeholder="Enter your password"
                       required
                       autocomplete="current-password"
                       class="block w-full h-11 px-4 pr-12 text-[15px] text-[#1e293b] bg-white border border-[#e2e8f0] rounded-lg placeholder:text-[#94a3b8] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-150 @error('password') border-[#dc2626] @enderror">
                <button type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#64748b] hover:text-[#1e293b] transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 rounded"
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
                <p class="text-sm text-[#dc2626] mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between mt-6">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-[#e2e8f0] text-primary focus:ring-primary/30">
                <span class="text-sm text-[#64748b]">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm font-medium text-primary hover:text-primary-light hover:underline transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full h-12 mt-6 bg-primary text-white text-[15px] font-semibold rounded-lg hover:bg-primary-light transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
            Sign In
        </button>

        <!-- Divider -->
        <div class="my-6 flex items-center">
            <div class="h-px flex-1 bg-[#e2e8f0]"></div>
            <span class="px-3 text-xs font-medium text-[#94a3b8] uppercase tracking-wider">or continue with</span>
            <div class="h-px flex-1 bg-[#e2e8f0]"></div>
        </div>

        <!-- Social Logins -->
        <div class="space-y-3">
            <button type="button"
                    class="w-full h-11 px-4 flex items-center justify-center text-[14px] font-medium text-[#1e293b] bg-white border border-[#e2e8f0] rounded-lg hover:bg-[#f8fafc] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2.5" viewBox="0 0 256 262">
                    <path fill="#4285f4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622l38.755 30.023l2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"/>
                    <path fill="#34a853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055c-34.523 0-63.824-22.773-74.269-54.25l-1.531.13l-40.298 31.187l-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"/>
                    <path fill="#fbbc05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82c0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602z"/>
                    <path fill="#eb4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0C79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"/>
                </svg>
                Continue with Google
            </button>

            <button type="button"
                    class="w-full h-11 px-4 flex items-center justify-center text-[14px] font-medium text-[#1e293b] bg-white border border-[#e2e8f0] rounded-lg hover:bg-[#f8fafc] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2.5" viewBox="0 0 256 256">
                    <path fill="#1877f2" d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/>
                    <path fill="#fff" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z"/>
                </svg>
                Continue with Facebook
            </button>
        </div>
    </form>
</x-guest-layout>
