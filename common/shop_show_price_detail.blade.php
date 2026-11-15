{{-- GP247-EC detail price: big crimson final price, struck original and a crimson "-x%" tag on a tinted strip (the strip is drawn by screen/shop_product_detail). --}}
@switch($kind)
    @case(GP247_PRODUCT_GROUP)
        <span class="text-2xl font-bold text-brand-600">{!! gp247_language_render('product.price_group') !!}</span>
        @break
    @default
        @if ($price == $priceFinal)
            <span class="price text-3xl">{!! gp247_currency_render($price) !!}</span>
        @else
            <span class="flex flex-wrap items-center gap-3">
                <span class="price-old text-base">{!! gp247_currency_render($price) !!}</span>
                <span class="price text-3xl">{!! gp247_currency_render($priceFinal) !!}</span>
                @if ($price > 0)
                    <span class="badge-sale text-xs">-{{ (int) round(100 - $priceFinal / $price * 100) }}%</span>
                @endif
            </span>
        @endif
@endswitch
