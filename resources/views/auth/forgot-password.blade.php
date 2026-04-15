<x-guest-layout>
    <x-slot name="title">Reset password</x-slot>
    <div class="mb-8">
        <h1 class="ui-auth-heading">Reset password</h1>
        <p class="ui-auth-sub">Enter your email address and we will send you a link to choose a new password.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-end">
            <x-primary-button>
                Email password reset link
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
