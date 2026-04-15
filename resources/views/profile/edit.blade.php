<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">
            Profile
        </h2>
    </x-slot>

    <div class="ui-page max-w-5xl space-y-6 pb-12">
        <div class="ui-profile-block">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="ui-profile-block">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="ui-profile-block border-rose-100/80 ring-rose-100/30">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
