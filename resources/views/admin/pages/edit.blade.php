<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Edit Page: {{ $page->slug }}</h2>
    </x-slot>

    <div class="ui-page max-w-5xl">
        <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data" class="ui-card p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input name="title" class="ui-input" value="{{ old('title', $page->title) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Subtitle</label>
                <input name="subtitle" class="ui-input" value="{{ old('subtitle', $page->subtitle) }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Body</label>
                <textarea name="body" class="ui-input min-h-52">{{ old('body', $page->body) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" name="image" class="ui-input">
                @if($page->image_path)
                    <img src="{{ asset('storage/'.$page->image_path) }}" class="w-60 rounded-xl mt-3 border border-blue-100" alt="Page image">
                @endif
            </div>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published))>
                <span class="text-sm">Published</span>
            </label>
            <div class="flex gap-2">
                <button class="ui-btn-primary">Save</button>
                <a href="{{ route('admin.pages.index') }}" class="ui-btn-secondary">Back</a>
            </div>
        </form>
    </div>
</x-app-layout>
