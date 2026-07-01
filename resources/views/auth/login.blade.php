<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="status-message">
            {{ session('status') }}
        </div>
    @endif

    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-subtitle">Sign in to access your projects</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-input"
                   placeholder="name@example.com"
                   required
                   autofocus
                   autocomplete="username">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   class="form-input"
                   placeholder="Enter your password"
                   required
                   autocomplete="current-password">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="remember" class="form-checkbox">
                <span class="form-checkbox-label">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-forgot">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary">
            Log in
        </button>
    </form>
</x-guest-layout>
