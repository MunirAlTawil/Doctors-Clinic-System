<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Clinic Settings</h2>
    </x-slot>

    <div class="ui-page max-w-3xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="ui-card p-6 space-y-4">
            @csrf
            <h3 class="ui-card-title">Financial Settings</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Profit %</label>
                <input type="number" step="0.01" min="0" max="100" name="admin_profit_percent" value="{{ $setting->admin_profit_percent }}" class="ui-input">
            </div>
            <button class="ui-btn-primary">Save</button>
        </form>
    </div>
</x-app-layout>
