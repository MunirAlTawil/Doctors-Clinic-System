<x-guest-layout>
    <x-slot name="title">Log in</x-slot>
    <div class="mb-8">
        <h1 class="ui-auth-heading">Log in</h1>
        <p class="ui-auth-sub">Sign in to your dashboard — appointments, notifications, and role tools.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-4">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <x-input-label for="password" :value="'Password'" />
            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5 block">
            <label for="remember_me" class="inline-flex cursor-pointer items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500/40" name="remember">
                <span class="ms-2 text-sm text-slate-600">Remember me</span>
            </label>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            @if (Route::has('password.request'))
                <a class="text-center text-sm text-slate-600 underline decoration-slate-300 underline-offset-2 transition hover:text-cyan-800 sm:text-left" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            <x-primary-button class="w-full sm:w-auto sm:min-w-[8rem]">
                Log in
            </x-primary-button>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-2 sm:grid-cols-2">
            <button type="button" id="quick-admin-login" class="ui-btn-secondary text-sm">
                Quick admin login
            </button>
            <button type="button" id="quick-doctor-login" class="ui-btn-secondary text-sm">
                Quick doctor login
            </button>
            <a href="{{ route('register') }}" class="ui-btn-secondary col-span-1 text-center text-sm sm:col-span-2">
                Create an account
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            document.getElementById('quick-admin-login')?.addEventListener('click', function () {
                email.value = 'admin@clinic.local';
                password.value = 'password';
            });

            document.getElementById('quick-doctor-login')?.addEventListener('click', function () {
                email.value = 'doctor@clinic.local';
                password.value = 'password';
            });
        });
    </script>
</x-guest-layout>
