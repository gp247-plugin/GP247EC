{{--
    GP247-EC cart (Livewire CartManager): marketplace cart — one panel per store
    with a light column header (product · quantity · subtotal), square qty
    steppers, and a store footer with its own checkout form; a summary panel on
    the right. Keeps the component contract: updateQty / removeItem / clearCart,
    and one checkout.prepare form per store (a multi-vendor cart checks out each
    store separately — ShopCartController::prepareCheckout reads one store_id).
--}}
<div>
    @if($errorMessage)
        <div class="mb-3 flex items-start justify-between gap-3 rounded-sm border-s-4 border-accent-600 bg-accent-50 px-4 py-3 text-sm text-accent-700">
            <span>{!! e($errorMessage) !!}</span>
            <button type="button" class="shrink-0" wire:click="$set('errorMessage', null)" aria-label="Close">&times;</button>
        </div>
    @endif

    @if($successMessage)
        <div class="mb-3 flex items-start justify-between gap-3 rounded-sm border-s-4 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <span>{!! e($successMessage) !!}</span>
            <button type="button" class="shrink-0" wire:click="$set('successMessage', null)" aria-label="Close">&times;</button>
        </div>
    @endif

    @if($count == 0)
        <div class="card flex flex-col items-center gap-4 py-16 text-center" data-testid="storefront-cart-empty">
            <span class="flex h-24 w-24 items-center justify-center rounded-full bg-ink-100">
                <svg class="w-12 h-12 text-ink-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3h2l2.4 12.4a2 2 0 0 0 2 1.6h8.6a2 2 0 0 0 2-1.6L22 8H6"/><circle cx="9" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>
            </span>
            <p class="text-base font-semibold text-ink-700">{!! gp247_language_render('cart.cart_empty') !!}</p>
            <a href="{{ gp247_route_front('product.all') }}" class="btn-primary">{{ trans('Templates/GP247EC::lang.ec.continue_shopping') }}</a>
        </div>
    @else
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-4 items-start">
            <div class="space-y-4">
                @foreach($itemsByStore as $storeId => $storeItems)
                    @php
                        $processedItems = gp247_cart_process_data($storeItems);
                        $storeSubtotal  = collect($processedItems)->sum('process_product_price_subtotal');
                    @endphp
                    <div class="card overflow-hidden" data-testid="storefront-cart-store">
                        <div class="flex items-center justify-between gap-3 border-b border-ink-100 px-4 py-3">
                            <span class="flex items-center gap-2 text-sm font-bold text-ink-900">
                                <span class="flex h-7 w-7 items-center justify-center rounded-sm bg-brand-100 text-brand-600">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1.5-5h15L21 9M3 9v10a1 1 0 001 1h16a1 1 0 001-1V9M3 9h18M9 13h6"/></svg>
                                </span>
                                {{ gp247_store_info(key: 'name', storeId: $storeId) }}
                            </span>
                            <span class="text-xs text-ink-500">{{ count($processedItems) }} {{ gp247_language_quickly('cart.items', 'items') }}</span>
                        </div>

                        <div class="hidden sm:grid grid-cols-[minmax(0,1fr)_130px_120px_32px] gap-4 border-b border-ink-200 bg-ink-50 px-4 py-2 text-[11px] font-bold uppercase tracking-wide text-ink-500">
                            <span>{{ gp247_language_render('product.name') }}</span>
                            <span class="text-center">{{ gp247_language_render('product.quantity') }}</span>
                            <span class="text-end">{{ gp247_language_quickly('cart.subtotal', 'Subtotal') }}</span>
                            <span></span>
                        </div>

                        <div class="divide-y divide-ink-100">
                            @foreach($processedItems as $row)
                                <div class="grid grid-cols-[minmax(0,1fr)_32px] sm:grid-cols-[minmax(0,1fr)_130px_120px_32px] items-center gap-x-4 gap-y-3 px-4 py-4" data-testid="storefront-cart-line">
                                    <div class="flex gap-3 min-w-0">
                                        <a href="{{ $row['process_product_url'] }}" class="shrink-0">
                                            <img src="{{ gp247_file($row['process_product_image']) }}" alt="{{ $row['process_product_name'] }}" class="h-20 w-20 rounded-sm border border-ink-100 bg-white object-contain">
                                        </a>
                                        <div class="min-w-0">
                                            <a href="{{ $row['process_product_url'] }}" class="clamp-2 text-sm text-ink-900 hover:text-brand-600">{{ $row['process_product_name'] }}</a>
                                            <p class="mt-1 text-xs text-ink-500">
                                                {{ gp247_language_render('product.sku') }}: <span class="text-ink-700">{{ $row['process_product_sku'] }}</span>
                                                {!! $row['process_product_display_vendor'] !!}
                                            </p>
                                            @if ($row['process_attributes'])
                                            <p class="mt-1 flex flex-wrap gap-1">
                                                @foreach ($row['process_attributes'] as $opt)
                                                    <span class="rounded-sm bg-ink-100 px-1.5 py-0.5 text-[11px] text-ink-600">{{ $opt['name'] }}: {!! $opt['value'] !!}</span>
                                                @endforeach
                                            </p>
                                            @endif
                                        </div>
                                    </div>

                                    <button type="button" class="row-span-2 sm:row-span-1 sm:order-last flex h-8 w-8 items-center justify-center self-start sm:self-center rounded-sm text-ink-400 hover:bg-accent-50 hover:text-accent-600"
                                        wire:click="removeItem('{{ $row['process_cart_id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:confirm="{{ e(gp247_language_quickly('cart.confirm_remove', 'Remove this item from the cart?')) }}"
                                        aria-label="{{ gp247_language_render('action.remove') ?? 'Remove' }}"
                                        data-testid="storefront-cart-remove">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                                    </button>

                                    <div class="flex items-center justify-between gap-3 sm:contents">
                                        <div class="flex h-9 w-max items-stretch sm:justify-self-center rounded-sm border border-ink-300 bg-white">
                                            <button type="button" class="w-9 text-ink-600 hover:bg-ink-100" wire:click="updateQty('{{ $row['process_cart_id'] }}', {{ max(0, $row['process_qty'] - 1) }})" wire:loading.attr="disabled" aria-label="Decrease quantity">&minus;</button>
                                            <input type="number"
                                                class="w-12 border-x border-ink-300 text-center text-sm font-semibold outline-none"
                                                value="{{ gp247_qty_format($row['process_qty']) }}"
                                                min="0"
                                                step="{{ gp247_qty_decimal_enabled() ? '0.01' : '1' }}"
                                                wire:change="updateQty('{{ $row['process_cart_id'] }}', $event.target.value)"
                                                wire:loading.attr="disabled"
                                                data-testid="storefront-cart-qty">
                                            <button type="button" class="w-9 text-ink-600 hover:bg-ink-100" wire:click="updateQty('{{ $row['process_cart_id'] }}', {{ $row['process_qty'] + 1 }})" wire:loading.attr="disabled" aria-label="Increase quantity">+</button>
                                        </div>
                                        <span class="price text-base sm:text-end">{{ gp247_currency_render($row['process_product_price_subtotal']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 border-t border-ink-200 bg-ink-50 px-4 py-3">
                            <button type="button" class="text-xs font-semibold text-ink-500 hover:text-accent-600 sm:me-auto w-max"
                                wire:click="clearCart"
                                wire:loading.attr="disabled"
                                wire:confirm="{{ e(gp247_language_quickly('cart.confirm_clear', 'Clear all items from the cart?')) }}"
                                data-testid="storefront-cart-clear">
                                {{ gp247_language_quickly('cart.clear_cart', 'Clear cart') }}
                            </button>
                            <span class="text-sm text-ink-600">{{ gp247_language_quickly('cart.total', 'Total') }}:
                                <span class="price text-lg ms-1">{{ gp247_currency_render($storeSubtotal) }}</span>
                            </span>
                            <form action="{{ gp247_route_front('checkout.prepare') }}" method="POST">
                                @csrf
                                <input type="hidden" name="store_id" value="{{ $storeId }}">
                                @foreach($processedItems as $row)
                                    <input type="hidden" name="qty-{{ $row['process_cart_id'] }}" value="{{ $row['process_qty'] }}">
                                @endforeach
                                <button type="submit" class="btn-buy w-full sm:w-auto sm:min-w-[180px]" data-testid="storefront-cart-checkout">
                                    {{ gp247_language_render('cart.checkout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="card overflow-hidden lg:sticky-top">
                <div class="ec-bar px-4 py-3">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-ink-900">{{ trans('Templates/GP247EC::lang.ec.order_summary') }}</h2>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-ink-600">{{ gp247_language_quickly('cart.total', 'Total') }}</span>
                        <span class="price text-xl">{{ gp247_currency_render(collect($itemsByStore)->flatMap(fn ($storeItems) => gp247_cart_process_data($storeItems))->sum('process_product_price_subtotal')) }}</span>
                    </div>
                    <p class="mt-3 rounded-sm bg-brand-50 px-3 py-2 text-xs text-brand-700">{{ gp247_language_quickly('cart.checkout_per_vendor_hint', 'Each vendor is checked out separately.') }}</p>
                    <a href="{{ gp247_route_front('product.all') }}" class="btn-outline btn-sm mt-4 w-full">{{ trans('Templates/GP247EC::lang.ec.continue_shopping') }}</a>
                    <p class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-ink-500">
                        <svg class="w-3.5 h-3.5 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                        {{ trans('Templates/GP247EC::lang.ec.secure_checkout') }}
                    </p>
                </div>
            </aside>
        </div>
    @endif

    <div wire:loading.flex class="w-full justify-center py-2">
        <svg class="animate-spin w-6 h-6 text-brand-500" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
    </div>
</div>
