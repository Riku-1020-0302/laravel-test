<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('customers.show', $task->customer) }}" class="text-xs text-gray-500 hover:text-gray-700">← {{ $task->customer->name }}</a>
            <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">タスク編集</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-6 p-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="content" value="タスク内容 *" />
                        <textarea id="content" name="content" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $task->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <x-input-label for="due_date" value="期限" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                                          :value="old('due_date', $task->due_date?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="status" value="ステータス" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (\App\Enums\TaskStatus::options() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $task->status->value) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <x-primary-button>更新する</x-primary-button>
                        <a href="{{ route('customers.show', $task->customer) }}" class="text-sm text-gray-600 hover:text-gray-900">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>