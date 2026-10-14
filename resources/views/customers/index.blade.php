<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">顧客一覧</h2>
            <a href="{{ route('customers.create') }}"
               class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold tracking-widest text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                ＋ 顧客を登録
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
            <x-flash-message />

            {{-- 検索 --}}
            <form method="GET" action="{{ route('customers.index') }}" class="flex items-center gap-2 px-4 sm:px-0">
                <x-text-input name="q" type="search" class="block w-full sm:w-80"
                              placeholder="名前・会社名・メールで検索" :value="$keyword" />
                <x-secondary-button type="submit">検索</x-secondary-button>
                @if ($keyword !== '')
                    <a href="{{ route('customers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">クリア</a>
                @endif
            </form>

            {{-- 一覧 --}}
            <div class="overflow-hidden border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold tracking-wider text-gray-500">
                                <th class="px-6 py-3">顧客名</th>
                                <th class="px-6 py-3">会社名</th>
                                <th class="px-6 py-3">メール</th>
                                <th class="px-6 py-3">電話</th>
                                <th class="px-6 py-3 text-center">未完了タスク</th>
                                <th class="px-6 py-3 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        <a href="{{ route('customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-800">
                                            {{ $customer->name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-700">{{ $customer->company_name ?: '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-700">{{ $customer->email ?: '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-700">{{ $customer->phone ?: '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if ($customer->open_tasks_count > 0)
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                {{ $customer->open_tasks_count }}件
                                            </span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('customers.show', $customer) }}" class="text-gray-600 hover:text-gray-900">詳細</a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="ml-3 text-gray-600 hover:text-gray-900">編集</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        {{ $keyword !== '' ? '該当する顧客が見つかりません。' : '顧客がまだ登録されていません。' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-4 sm:px-0">{{ $customers->links() }}</div>
        </div>
    </div>
</x-app-layout>