<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">顧客編集</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6 p-6">
                    @csrf
                    @method('PUT')
                    @include('customers._form', [
                        'submitLabel' => '更新する',
                        'cancelUrl'   => route('customers.show', $customer),
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>