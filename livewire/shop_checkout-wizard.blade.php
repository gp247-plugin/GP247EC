{{--
    CheckoutWizard Livewire component view: numbered stepper and one panel per
    step. Steps 1-3 keep every wire:model/wire:click
    directive unchanged (address/shipping/payment). Step 4 ("confirm") stays a
    plain <form method="POST"> to gp247_route_front('order.add') with only
    @csrf — addOrder() reads all order data from session, not the request
    body, so the Livewire component never renders that step reactively
.
--}}
<div wire:loading.class="opacity-60" class="relative">

    {{-- Step indicator --}}
    @php
        $ecSteps = [
            'address'  => gp247_language_render('cart.checkout'),
            'shipping' => gp247_language_render('cart.shipping_method'),
            'payment'  => gp247_language_render('cart.payment_method'),
            'confirm'  => gp247_language_render('checkout.page_title'),
        ];
        $ecStepIndex = array_search($step, array_keys($ecSteps), true);
    @endphp
    <ol class="card mb-4 flex items-center gap-2 px-4 py-4 sm:px-6 text-xs sm:text-sm" data-testid="checkout-stepper">
        @foreach ($ecSteps as $s => $label)
        @php
            $i = $loop->index;
            $active = ($step === $s);
            $done = $ecStepIndex !== false && $i < $ecStepIndex;
        @endphp
        <li class="flex items-center gap-2 {{ $loop->last ? '' : 'flex-1' }}">
            <span class="step-dot {{ $active ? 'border-brand-500 bg-brand-500 text-white' : ($done ? 'border-brand-300 bg-brand-100 text-brand-700' : 'border-ink-300 bg-white text-ink-400') }}">
                @if ($done)
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                @else
                    {{ $i + 1 }}
                @endif
            </span>
            <span class="hidden sm:inline whitespace-nowrap {{ $active ? 'font-bold text-ink-900' : 'text-ink-500' }}">{{ $label }}</span>
            @if (!$loop->last)<span class="h-0.5 flex-1 {{ $done ? 'bg-brand-300' : 'bg-ink-200' }}"></span>@endif
        </li>
        @endforeach
    </ol>

    {{-- Loading overlay --}}
    <div wire:loading class="absolute inset-0 bg-white/50 z-10 rounded"></div>

    {{-- ═══ STEP 1 — ADDRESS ═══ --}}
    @if ($step === 'address')
    <div class="card overflow-hidden p-5 sm:p-6" id="cw-address">
        <div class="-mx-5 -mt-5 sm:-mx-6 sm:-mt-6 mb-5 ec-bar px-5 sm:px-6 py-3"><h2 class="text-sm font-bold uppercase tracking-wide text-ink-900">{{ gp247_language_render('cart.checkout') }}</h2></div>

        @if ($customer && count($customer->addresses ?? []))
        <div class="mb-5">
            <label class="label">{{ gp247_language_render('cart.change_address') }}</label>
            <select class="input" wire:change="selectAddress($event.target.value)">
                <option value="">{{ gp247_language_render('cart.change_address') }}</option>
                @foreach ($customer->addresses as $addr)
                <option value="{{ $addr->id }}" @selected($address_process == $addr->id)>
                    {{ $addr->first_name.' '.$addr->last_name.', '.trim($addr->city.' '.$addr->district.' '.($addr->ward ?? '').' '.$addr->address1.' '.$addr->address2.' '.$addr->address3) }}
                </option>
                @endforeach
                <option value="new" @selected($address_process === 'new')>{{ gp247_language_render('cart.add_new_address') }}</option>
            </select>
        </div>
        @endif

        @if ($errors->any())
        <div class="rounded-sm border-s-4 border-accent-600 bg-accent-50 text-accent-700 text-sm px-4 py-3 mb-5">
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="label">{{ gp247_language_render('cart.first_name') }} <span class="text-accent-600">*</span></label>
                <input type="text" wire:model="first_name" class="input @error('first_name') input-error @enderror">
                @error('first_name')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @if ($fieldConfig['last_name']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.last_name') }} @if ($fieldConfig['last_name']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="last_name" class="input @error('last_name') input-error @enderror">
                @error('last_name')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['name_kana']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.first_name_kana') }} @if ($fieldConfig['name_kana']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="first_name_kana" class="input @error('first_name_kana') input-error @enderror">
                @error('first_name_kana')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label">{{ gp247_language_render('cart.last_name_kana') }} @if ($fieldConfig['name_kana']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="last_name_kana" class="input @error('last_name_kana') input-error @enderror">
                @error('last_name_kana')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            <div>
                <label class="label">{{ gp247_language_render('cart.email') }} <span class="text-accent-600">*</span></label>
                <input type="email" wire:model="email" class="input @error('email') input-error @enderror">
                @error('email')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @if ($fieldConfig['phone']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.phone') }} @if ($fieldConfig['phone']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="phone" class="input @error('phone') input-error @enderror">
                @error('phone')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['country']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.country') }} @if ($fieldConfig['country']['required'])<span class="text-accent-600">*</span>@endif</label>
                <select wire:model="country" class="input @error('country') input-error @enderror">
                    <option value="">--</option>
                    @foreach ($countries as $code => $name)
                    <option value="{{ $code }}" @selected($country === $code)>{{ $name }}</option>
                    @endforeach
                </select>
                @error('country')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['postcode']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.postcode') }} @if ($fieldConfig['postcode']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="postcode" class="input @error('postcode') input-error @enderror">
                @error('postcode')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            {{-- City / district precede address1 to match the canonical address order
                 (city -> district -> address1/2/3). Both default OFF via fieldConfig. --}}
            @if ($fieldConfig['city']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.city') }} @if ($fieldConfig['city']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="city" class="input @error('city') input-error @enderror">
                @error('city')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['district']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.district') }} @if ($fieldConfig['district']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="district" class="input @error('district') input-error @enderror">
                @error('district')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['ward']['show'] ?? false)
            <div>
                <label class="label">{{ gp247_language_render('cart.ward') }} @if ($fieldConfig['ward']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="ward" class="input @error('ward') input-error @enderror" data-testid="checkout-address-ward">
                @error('ward')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['address1']['show'])
            <div class="sm:col-span-2">
                <label class="label">{{ gp247_language_render('cart.address1') }} @if ($fieldConfig['address1']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="address1" class="input @error('address1') input-error @enderror">
                @error('address1')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['address2']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.address2') }} @if ($fieldConfig['address2']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="address2" class="input @error('address2') input-error @enderror">
                @error('address2')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['address3']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.address3') }} @if ($fieldConfig['address3']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="address3" class="input @error('address3') input-error @enderror">
                @error('address3')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            @if ($fieldConfig['company']['show'])
            <div>
                <label class="label">{{ gp247_language_render('cart.company') }} @if ($fieldConfig['company']['required'])<span class="text-accent-600">*</span>@endif</label>
                <input type="text" wire:model="company" class="input @error('company') input-error @enderror">
                @error('company')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            <div class="sm:col-span-2">
                <label class="label">{{ gp247_language_quickly('cart.comment', 'Comment') }}</label>
                <textarea wire:model="comment" class="input" rows="3"></textarea>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button wire:click="nextStep" wire:loading.attr="disabled" class="btn-primary" type="button">
                {{ gp247_language_quickly('cart.next', 'Next') }} &rsaquo;
            </button>
        </div>
    </div>
    @endif

    {{-- ═══ STEP 2 — SHIPPING ═══ --}}
    @if ($step === 'shipping')
    <div class="card overflow-hidden p-5 sm:p-6" id="cw-shipping">
        <div class="-mx-5 -mt-5 sm:-mx-6 sm:-mt-6 mb-5 ec-bar px-5 sm:px-6 py-3"><h2 class="text-sm font-bold uppercase tracking-wide text-ink-900">{{ gp247_language_render('cart.shipping_method') }}</h2></div>

        @if ($errors->has('shippingMethod'))
        <div class="rounded-sm border-s-4 border-accent-600 bg-accent-50 text-accent-700 text-sm px-4 py-3 mb-4">{{ $errors->first('shippingMethod') }}</div>
        @endif
        @if (!empty($shippingRateError))
        <div class="rounded-sm border-s-4 border-accent-600 bg-accent-50 text-accent-700 text-sm px-4 py-3 mb-4" data-testid="checkout-shipping-rate-error">{{ $shippingRateError }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($shippingPlugins as $key => $info)
            <label for="sm_{{ $key }}" class="flex items-center gap-3 rounded-sm border border-ink-300 px-4 py-3 cursor-pointer hover:border-brand-400 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-500">
                <input
                    type="radio"
                    name="shippingMethodChoice"
                    id="sm_{{ $key }}"
                    class="accent-brand-600"
                    wire:click="selectShipping('{{ e($key) }}')"
                    data-testid="checkout-shipping-rate-{{ $key }}"
                    @checked($shippingMethod === $key)
                >
                <span class="text-sm">
                    {{ $info['title'] ?? $key }}
                    @if (!empty($info['value']))<span class="text-ink-400"> &ndash; {{ $info['text'] ?? $info['value'] }}</span>@endif
                    @if (!empty($info['eta_max']))<span class="block text-xs text-ink-400">{{ gp247_language_render('cart.shipping_eta_days', ['min' => $info['eta_min'] ?? $info['eta_max'], 'max' => $info['eta_max']]) }}</span>@endif
                </span>
            </label>
            @empty
            <p class="text-sm text-ink-400">{{ gp247_language_quickly('cart.no_shipping_method', 'No shipping method available.') }}</p>
            @endforelse
        </div>

        <div class="flex justify-between mt-6">
            <button wire:click="prevStep" class="btn-ghost" type="button">&lsaquo; {{ gp247_language_quickly('action.back', 'Back') }}</button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="btn-primary" type="button">
                {{ gp247_language_quickly('cart.next', 'Next') }} &rsaquo;
            </button>
        </div>
    </div>
    @endif

    {{-- ═══ STEP 3 — PAYMENT ═══ --}}
    @if ($step === 'payment')
    <div class="card overflow-hidden p-5 sm:p-6" id="cw-payment">
        <div class="-mx-5 -mt-5 sm:-mx-6 sm:-mt-6 mb-5 ec-bar px-5 sm:px-6 py-3"><h2 class="text-sm font-bold uppercase tracking-wide text-ink-900">{{ gp247_language_render('cart.payment_method') }}</h2></div>

        @if ($errors->has('paymentMethod'))
        <div class="rounded-sm border-s-4 border-accent-600 bg-accent-50 text-accent-700 text-sm px-4 py-3 mb-4">{{ $errors->first('paymentMethod') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($paymentPlugins as $key => $info)
            {{-- WHY the note sits outside the <label>: it is instructions to read, not part of the
                 click target. Only the selected method shows it; selectPayment() re-renders server-side
. Plain text, always escaped; a non-string note is ignored. --}}
            <div>
            <label for="pm_{{ $key }}" class="flex items-center gap-3 rounded-sm border border-ink-300 px-4 py-3 cursor-pointer hover:border-brand-400 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-500">
                <input
                    type="radio"
                    name="paymentMethodChoice"
                    id="pm_{{ $key }}"
                    class="accent-brand-600"
                    wire:click="selectPayment('{{ e($key) }}')"
                    @checked($paymentMethod === $key)
                >
                <span class="text-sm">{{ $info['title'] ?? $key }}</span>
            </label>
            @if ($paymentMethod === $key && is_string($info['note'] ?? null) && trim($info['note']) !== '')
            <p class="mt-2 rounded-sm bg-brand-50 px-4 py-3 text-sm text-ink-700 leading-relaxed" data-testid="checkout-payment-note-{{ $key }}">{{ trim($info['note']) }}</p>
            @endif
            </div>
            @empty
            <p class="text-sm text-ink-400">{{ gp247_language_quickly('cart.no_payment_method', 'No payment method available.') }}</p>
            @endforelse
        </div>

        <div class="flex justify-between mt-6">
            <button wire:click="prevStep" class="btn-ghost" type="button">&lsaquo; {{ gp247_language_quickly('action.back', 'Back') }}</button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="btn-primary" type="button">
                {{ gp247_language_quickly('cart.next', 'Next') }} &rsaquo;
            </button>
        </div>
    </div>
    @endif

    {{-- ═══ STEP 4 — CONFIRM ═══ --}}
    @if ($step === 'confirm')
    <div class="space-y-5" id="cw-confirm">
        <h2 class="rounded border border-brand-200 border-b-2 border-b-brand-500 bg-gradient-to-r from-brand-50 to-white px-5 py-3 text-sm font-bold uppercase tracking-wide text-ink-900">{{ gp247_language_render('checkout.page_title') }}</h2>

        <div class="card p-5">
            <h3 class="mb-3 border-b border-ink-100 pb-2 text-xs font-bold uppercase tracking-wide text-brand-700">{{ gp247_language_render('cart.checkout') }}</h3>
            <p class="text-sm text-ink-700 leading-relaxed">
                {{ $first_name }} {{ $last_name }}<br>
                {{ $city ? $city.', ' : '' }}{{ $district ? $district.', ' : '' }}{{ ($ward ?? '') ? $ward.', ' : '' }}{{ $address1 }}{{ $address2 ? ', '.$address2 : '' }}{{ $address3 ? ', '.$address3 : '' }}<br>
                {{ $country }} {{ $postcode }}<br>
                {{ $email }} / {{ $phone }}
            </p>
        </div>

        @if ($paymentMethod !== '' && isset($paymentPlugins[$paymentMethod]))
        <div class="card p-5" data-testid="checkout-confirm-payment">
            <h3 class="mb-3 border-b border-ink-100 pb-2 text-xs font-bold uppercase tracking-wide text-brand-700">{{ gp247_language_render('cart.payment_method') }}</h3>
            <p class="text-sm font-medium text-ink-700">{{ $paymentPlugins[$paymentMethod]['title'] ?? $paymentMethod }}</p>
            @if (is_string($paymentPlugins[$paymentMethod]['note'] ?? null) && trim($paymentPlugins[$paymentMethod]['note']) !== '')
            <p class="mt-2 text-sm text-ink-700 leading-relaxed" data-testid="checkout-confirm-payment-note">{{ trim($paymentPlugins[$paymentMethod]['note']) }}</p>
            @endif
        </div>
        @endif

        @if ($processedItems && count($processedItems))
        {{-- WHY: render per-line price + attributes from gp247_cart_process_data()
             (provided as $processedItems), the single source shared with the
             cart-manager view. CartItem has no price property and keeps options
             as raw "name__add_price" strings, so the old per-line expression
             produced 0 and the attribute leaked "Xanh__100.00". Process data
             gives the effective price (incl. option surcharge) and a rendered
             attribute value, keeping Σ(line) == subtotal
. --}}
        <div class="card p-5">
            <h3 class="mb-3 border-b border-ink-100 pb-2 text-xs font-bold uppercase tracking-wide text-brand-700">{{ gp247_language_render('cart.cart_title') }}</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-start text-[11px] uppercase tracking-wide text-ink-500 bg-ink-100">
                        <th class="px-2 py-2 font-semibold">{{ gp247_language_quickly('cart.product', 'Product') }}</th>
                        <th class="px-2 py-2 font-semibold text-end">{{ gp247_language_quickly('cart.qty', 'Qty') }}</th>
                        <th class="px-2 py-2 font-semibold text-end">{{ gp247_language_quickly('cart.price', 'Price') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($processedItems as $row)
                    <tr>
                        <td class="py-2">
                            {{ $row['process_product_name'] }}
                            @if ($row['process_attributes'])
                            @foreach ($row['process_attributes'] as $opt)
                            <br><span class="text-xs text-ink-400">{{ $opt['name'] }}: {!! $opt['value'] !!}@if (!empty($opt['slug'])) <span class="text-ink-300" data-testid="storefront-confirm-attribute-slug">({{ $opt['slug'] }})</span>@endif</span>
                            @endforeach
                            @endif
                        </td>
                        <td class="py-2 text-right">{{ gp247_qty_format($row['process_qty']) }}</td>
                        <td class="py-2 text-right price">{{ gp247_currency_render($row['process_product_price_subtotal']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Total-method zone (coupon/point) + order totals — checkout total contract
. A template overriding this
             view only needs these two @include points to support every total plugin. --}}
        @include('GP247TemplatePath::GP247EC.partials.checkout_total_methods')
        @include('GP247TemplatePath::GP247EC.partials.order_totals')

        {{-- Place order form (non-Livewire POST — addOrder() reads from session, not request body). --}}
        <form method="POST" action="{{ gp247_route_front('order.add') }}">
            @csrf
            {{-- WHY: addOrder() checks request()->all() is truthy before reading session data.
                 A bare @csrf is sufficient — the _token key makes $data non-empty. --}}
            <div class="flex justify-between">
                <button wire:click="prevStep" class="btn-ghost" type="button">&lsaquo; {{ gp247_language_quickly('action.back', 'Back') }}</button>
                <button type="submit" class="btn-buy btn-lg min-w-[200px]">
                    {!! gp247_language_quickly('checkout.place_order', 'Place order') !!}
                </button>
            </div>
        </form>
    </div>
    @endif

</div>
