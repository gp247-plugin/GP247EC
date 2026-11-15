{{--
    Wishlist/compare buttons on the product detail page, for
    GP247\Shop\Front\Livewire\ProductActions. Same
    wire:click contract as the product card: toggleList('wishlist'|'compare') — adds, or removes when already in the list.
--}}
<div class="flex flex-wrap items-center gap-x-5 gap-y-2">
    {{-- $product can be null if it was deleted/deactivated between page load and a Livewire round-trip — keep the root tag outside @if so Livewire always has one root element --}}
    @if ($product)
        @if (gp247_config('product_use_button_wishlist'))
        {{-- Active (already in the list): one red for both, same as the product card; the heart also fills --}}
        <button type="button" wire:click="toggleList('wishlist')" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 text-sm transition {{ $inWishlist ? 'is-active text-accent-600 font-semibold' : 'text-ink-600 hover:text-brand-600' }}" aria-pressed="{{ $inWishlist ? 'true' : 'false' }}" data-testid="storefront-product-detail-wishlist">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 3 5 6.5 5c1.9 0 3.4 1 5.5 3 2.1-2 3.6-3 5.5-3C21 5 23.5 8.5 21.5 12.5 19 16.65 12 21 12 21z"/></svg>
            {{ gp247_language_render('action.add_to_wishlist') }}
        </button>
        @endif
        @if (gp247_config('product_use_button_compare'))
        <button type="button" wire:click="toggleList('compare')" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 text-sm transition {{ $inCompare ? 'is-active text-accent-600 font-semibold' : 'text-ink-600 hover:text-brand-600' }}" aria-pressed="{{ $inCompare ? 'true' : 'false' }}" data-testid="storefront-product-detail-compare">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 3l4 4-4 4M7 21l-4-4 4-4M3 7h14M21 17H7"/></svg>
            {{ gp247_language_render('action.add_to_compare') }}
        </button>
        @endif
    @endif
</div>
