{{-- GP247-EC listing price: crimson final price, struck-through original below it. --}}
<div class="mt-1.5 flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
@switch($kind)
    @case(GP247_PRODUCT_GROUP)
        <span class="text-sm font-bold text-brand-700">{!! gp247_language_render('product.price_group') !!}</span>
        @break
    @default
        @if ($price == $priceFinal)
            <span class="price text-base">{!! gp247_currency_render($price) !!}</span>
        @else
            <span class="price text-base">{!! gp247_currency_render($priceFinal) !!}</span>
            <span class="price-old">{!! gp247_currency_render($price) !!}</span>
        @endif
@endswitch
</div>
