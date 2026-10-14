@use('App\Enums\TaskStatus')

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('customers.index') }}" class="text-xs text-gray-500 hover:text-gray-700">← 顧客一覧</a>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">{{ $customer->name }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customers.edit', $customer) }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    編集
                </a>
                <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                      onsubmit="return confirm('この顧客と、紐づくタスクをすべて削除します。よろしいですか？')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>削除</x-danger-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- 顧客情報 --}}
                <div class="h-fit border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900">顧客情報</h3>
                    </div>
                    <dl class="divide-y divide-gray-100 px-6 text-sm">
                        <div class="py-3">
                            <dt class="text-xs text-gray-500">会社名</dt>
                            <dd class="mt-1 text-gray-900">{{ $customer->company_name ?: '—' }}</dd>
                        </div>
                        <div class="py-3">
                            <dt class="text-xs text-gray-500">メールアドレス</dt>
                            <dd class="mt-1 text-gray-900">
                                @if ($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $customer->email }}</a>
                                @else — @endif
                            </dd>
                        </div>
                        <div class="py-3">
                            <dt class="text-xs text-gray-500">電話番号</dt>
                            <dd class="mt-1 text-gray-900">
                                @if ($customer->phone)
                                    <a href="tel:{{ $customer->phone }}" class="text-indigo-600 hover:text-indigo-800">{{ $customer->phone }}</a>
                                @else — @endif
                            </dd>
                        </div>
                        <div class="py-3">
                            <dt class="text-xs text-gray-500">登録日</dt>
                            <dd class="mt-1 text-gray-900">{{ $customer->created_at->format('Y/m/d') }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- タスク --}}
                <div class="overflow-hidden border border-gray-200 bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900">タスク</h3>
                        <span class="text-xs text-gray-500">{{ $tasks->count() }}件</span>
                    </div>

                    {{-- 追加フォーム --}}
                    <form method="POST" action="{{ route('customers.tasks.store', $customer) }}"
                          class="grid grid-cols-1 gap-4 border-b border-gray-100 bg-gray-50 px-6 py-5 sm:grid-cols-6">
                        @csrf
                        <div class="sm:col-span-6">
                            <x-input-label for="content" value="タスク内容" />
                            <x-text-input id="content" name="content" type="text" class="mt-1 block w-full"
                                          :value="old('content')" placeholder="例：見積書を送付する" required />
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="due_date" value="期限" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                                          :value="old('due_date')" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="status" value="ステータス" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (TaskStatus::options() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', TaskStatus::Todo->value) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                        <div class="flex items-end sm:col-span-2">
                            <x-primary-button class="w-full justify-center">タスクを追加</x-primary-button>
                        </div>
                    </form>

                    {{-- 一覧 --}}
                    <ul class="divide-y divide-gray-100">
                        @forelse ($tasks as $task)
                            @php($done = $task->status === TaskStatus::Done)
                            <li class="flex items-start justify-between gap-4 px-6 py-4">
                                <div class="min-w-0">
                                    <p class="whitespace-pre-line break-words text-sm {{ $done ? 'text-gray-400 line-through' : 'text-gray-900' }}">{{ $task->content }}</p>
                                    <p class="mt-1 text-xs {{ $task->isOverdue() ? 'font-medium text-red-600' : 'text-gray-500' }}">
                                        期限：{{ $task->due_date?->format('Y/m/d') ?? '未設定' }}
                                        @if ($task->isOverdue()) （期限切れ） @endif
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <x-status-badge :status="$task->status" />
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-sm text-gray-600 hover:text-gray-900">編集</a>
                                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                          onsubmit="return confirm('このタスクを削除しますか？')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">削除</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-10 text-center text-sm text-gray-500">タスクはまだありません。</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>