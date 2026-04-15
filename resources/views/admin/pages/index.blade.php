<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Pages Content Management</h2>
    </x-slot>

    <div class="ui-page max-w-6xl">
        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Slug</th>
                        <th>Title</th>
                        <th>Published</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->slug }}</td>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->is_published ? 'Yes' : 'No' }}</td>
                            <td>{{ $page->updated_at }}</td>
                            <td>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="ui-btn-secondary text-sm">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No page content records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
