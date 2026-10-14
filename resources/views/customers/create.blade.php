<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">顧客登録</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('customers.store') }}" class="space-y-6 p-6">
                    @csrf
                    @include('customers._form', [
                        'submitLabel' => '登録する',
                        'cancelUrl'   => route('customers.index'),
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>