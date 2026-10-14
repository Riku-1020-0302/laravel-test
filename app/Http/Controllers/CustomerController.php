<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private const MESSAGES = [
        'required'   => ':attribute を入力してください。',
        'email'      => ':attribute は正しいメールアドレス形式で入力してください。',
        'max.string' => ':attribute は :max 文字以内で入力してください。',
        'regex'      => ':attribute の形式が正しくありません。',
    ];

    /** 一覧（検索・ページネーション付き） */
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));

        $customers = $request->user()->customers()
            ->withCount([
                'tasks as open_tasks_count' => fn ($q) => $q->where('status', '!=', TaskStatus::Done->value),
            ])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('company_name', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'keyword'));
    }

    public function create(): View
    {
        return view('customers.create', ['customer' => new Customer()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customer = $request->user()->customers()->create($this->validated($request));

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', '顧客を登録しました。');
    }

    /** 詳細（タスク一覧＋追加フォーム） */
    public function show(Customer $customer): View
    {
        $this->authorize('view', $customer);

        $tasks = $customer->tasks()
            ->orderByRaw("status = 'done'")      // 完了は下へ
            ->orderByRaw('due_date is null')     // 期限なしは後ろへ
            ->orderBy('due_date')
            ->orderBy('id')
            ->get();

        return view('customers.show', compact('customer', 'tasks'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $this->authorize('update', $customer);

        $customer->update($this->validated($request));

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', '顧客情報を更新しました。');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);

        $customer->delete(); // タスクはFKのcascadeで一緒に削除

        return redirect()
            ->route('customers.index')
            ->with('success', '顧客を削除しました。');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-() ]+$/'],
        ], self::MESSAGES, [
            'name'         => '顧客名',
            'company_name' => '会社名',
            'email'        => 'メールアドレス',
            'phone'        => '電話番号',
        ]);
    }
}