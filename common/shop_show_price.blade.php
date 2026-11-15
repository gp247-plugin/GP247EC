{{-- GP247-EC listing price: crimson final price, struck-through original and a "-x%" tag.
     WHY the tag is here and not on the image: the image's top-start corner is left
     free for plugin labels (e.g. flash sale). --}}
<div class="mt-1.5 flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
@switch($kind)
    @case(GP247_PRODUCT_GROUP)
        <span class="text-sm font-bold text-brand-600">{!! gp247_language_render('product.price_group') !!}</span>
        @break
    @default
        @if ($price == $priceFinal)
            <span class="price text-base">{!! gp247_currency_render($price) !!}</span>
        @else
            <span class="price text-base">{!! gp247_currency_render($priceFinal) !!}</span>
            <span class="price-old">{!! gp247_currency_render($price) !!}</span>
            @if ($price > 0)
                <span class="badge-sale">-{{ (int) round(100 - $priceFinal / $price * 100) }}%</span>
            @endif
        @endif
@endswitch
</div>
