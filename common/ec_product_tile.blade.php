{{--
    GP247-EC static product tile — same look as the Livewire product card, but
    plain markup (no Livewire component per tile) for shelves that render many
    products at once, such as the category tabs on the home page. The buy action
    links to the product page, where options and quantity are chosen.
    Expects $product (ShopProduct).
--}}
@php
    $ecPercent = (int) ($product->kind != GP247_PRODUCT_GROUP ? $product->getPercentDiscount() : 0);
@endphp
<article class="product-card group h-full">
    <a href="{{ $product->getUrl() }}" class="relative block aspect-square overflow-hidden bg-white">
        <img src="{{ gp247_file($product->getThumb()) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-2 group-hover:scale-[1.04] transition duration-300" loading="lazy">
        @if ($ecPercent > 0)
            <span class="badge-sale absolute top-0 start-0 rounded-none rounded-ee px-2 py-1 text-xs">-{{ $ecPercent }}%</span>
        @endif
    </a>
    <div class="flex flex-1 flex-col border-t border-ink-100 px-2.5 pt-2 pb-2.5">
        <a href="{{ $product->getUrl() }}" class="clamp-2 min-h-[2.5rem] text-[13px] leading-5 text-ink-800 hover:text-brand-700">{{ $product->name }}</a>
        <div class="mt-auto">
            {!! $product->showPrice() !!}
        </div>
    </div>
</article>
