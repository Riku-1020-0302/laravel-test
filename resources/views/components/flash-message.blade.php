@if (session('success'))
    <div class="mx-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 sm:mx-0" role="status">
        {{ session('success') }}
    </div>
@endif