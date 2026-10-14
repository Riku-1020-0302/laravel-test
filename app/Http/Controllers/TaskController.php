<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Customer;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const MESSAGES = [
        'required'   => ':attribute を入力してください。',
        'max.string' => ':attribute は :max 文字以内で入力してください。',
        'date'       => ':attribute は正しい日付で入力してください。',
        'enum'       => ':attribute の値が正しくありません。',
    ];

    /** 顧客詳細画面からの追加 */
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        $this->authorize('update', $customer);

        $customer->tasks()->create($this->validated($request));

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'タスクを追加しました。');
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $task->load('customer');

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update($this->validated($request));

        return redirect()
            ->route('customers.show', $task->customer_id)
            ->with('success', 'タスクを更新しました。');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $customerId = $task->customer_id;
        $task->delete();

        return redirect()
            ->route('customers.show', $customerId)
            ->with('success', 'タスクを削除しました。');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'content'  => ['required', 'string', 'max:1000'],
            'due_date' => ['nullable', 'date'],
            'status'   => ['required', Rule::enum(TaskStatus::class)],
        ], self::MESSAGES, [
            'content'  => 'タスク内容',
            'due_date' => '期限',
            'status'   => 'ステータス',
        ]);
    }
}