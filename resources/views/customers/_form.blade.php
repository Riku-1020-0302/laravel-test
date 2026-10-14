{{-- 変数: $customer, $submitLabel, $cancelUrl --}}
<div>
    <x-input-label for="name" value="顧客名 *" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                  :value="old('name', $customer->name)" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="company_name" value="会社名" />
    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full"
                  :value="old('company_name', $customer->company_name)" />
    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
</div>

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="email" value="メールアドレス" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                      :value="old('email', $customer->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="phone" value="電話番号" />
        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                      :value="old('phone', $customer->phone)" placeholder="03-1234-5678" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>
</div>

<div class="flex items-center gap-4 border-t border-gray-100 pt-6">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ $cancelUrl }}" class="text-sm text-gray-600 hover:text-gray-900">キャンセル</a>
</div>