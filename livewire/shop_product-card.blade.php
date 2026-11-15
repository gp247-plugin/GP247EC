{{--
    GP247-EC product card — dense marketplace tile: square image with a crimson
    discount tag, two-line name, crimson price + struck-through old price, and a
    full-width orange "add to cart" bar that slides in on hover (always shown on
    touch screens). Keeps the ProductCard component contract: toggleList('wishlist'
    |'compare') and addToCart('default').
--}}
<article class="product-card group h-full">
    {{-- Root tag stays outside @if: $product can be null after a Livewire round-trip
         (deleted/deactivated meanwhile) and Livewire needs one root element. --}}
    @if ($product)
    @php
        $ecPercent = (int) ($product->kind != GP247_PRODUCT_GROUP ? $product->getPercentDiscount() : 0);
    @endphp
    {{-- WHY the buttons sit next to the image link and not inside it: a control
         nested in an <a> is invalid HTML. --}}
    <div class="relative">
        <a href="{{ $product->getUrl() }}" class="block aspect-square overflow-hidden bg-white">
            <img src="{{ gp247_file($product->getThumb()) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-2 group-hover:scale-[1.04] transition duration-300" loading="lazy">
        </a>

        @if ($ecPercent > 0)
            <span class="badge-sale absolute top-0 start-0 rounded-none rounded-ee px-2 py-1 text-xs">-{{ $ecPercent }}%</span>
        @endif

        @if (gp247_config('product_use_button_wishlist') || gp247_config('product_use_button_compare'))
        <div class="qa absolute top-1.5 end-1.5 flex flex-col gap-1.5">
            @if (gp247_config('product_use_button_wishlist'))
            <button type="button" wire:click.prevent="toggleList('wishlist')" wire:loading.attr="disabled" class="flex h-8 w-8 items-center justify-center rounded-full bg-ink-900/80 text-white hover:bg-brand-500 transition{{ $inWishlist ? ' is-active !bg-accent-600' : '' }}" aria-pressed="{{ $inWishlist ? 'true' : 'false' }}" aria-label="{{ gp247_language_render('action.add_to_wishlist') }}" data-testid="storefront-product-card-wishlist">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 3 5 6.5 5c1.9 0 3.4 1 5.5 3 2.1-2 3.6-3 5.5-3C21 5 23.5 8.5 21.5 12.5 19 16.65 12 21 12 21z"/></svg>
            </button>
            @endif
            @if (gp247_config('product_use_button_compare'))
            <button type="button" wire:click.prevent="toggleList('compare')" wire:loading.attr="disabled" class="flex h-8 w-8 items-center justify-center rounded-full bg-ink-900/80 text-white hover:bg-brand-500 transition{{ $inCompare ? ' is-active !bg-accent-600' : '' }}" aria-pressed="{{ $inCompare ? 'true' : 'false' }}" aria-label="{{ gp247_language_render('action.add_to_compare') }}" data-testid="storefront-product-card-compare">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 3l4 4-4 4M7 21l-4-4 4-4M3 7h14M21 17H7"/></svg>
            </button>
            @endif
        </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col border-t border-ink-100 px-2.5 pt-2 pb-2.5">
        {!! $product->displayVendor() !!}
        <a href="{{ $product->getUrl() }}" class="clamp-2 min-h-[2.5rem] text-[13px] leading-5 text-ink-800 hover:text-brand-700">{{ $product->name }}</a>

        <div class="mt-auto">
            {!! $product->showPrice() !!}
        </div>

        @if ($product->allowSale() && gp247_config('product_use_button_add_to_cart'))
        <button type="button" wire:click="addToCart('default')" wire:loading.attr="disabled" class="mt-2 flex w-full items-center justify-center gap-1.5 rounded-sm border border-brand-500 py-1.5 text-xs font-bold text-brand-700 hover:bg-brand-500 hover:text-white transition" data-testid="storefront-product-card-add-to-cart">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.5 3h2l2.7 12.4a2 2 0 002 1.6h8.6a2 2 0 002-1.6L21 8H6"/></svg>
            {{ gp247_language_render('action.add_to_cart') }}
        </button>
        @endif
    </div>
    @endif
</article>
