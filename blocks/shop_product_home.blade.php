{{--
    GP247-EC newest-products shelf: white panel, dense 2/3/4/6-column grid with
    1px gutters (marketplace look). Bundle/group products carry a kind ribbon;
    single products need none.
--}}
@if (class_exists(\GP247\Shop\Models\ShopProduct::class))
@php
    $productsNew = $modelProduct->start()->getProductLatest()->setlimit(gp247_config('product_top'))->getData();
@endphp
@if ($productsNew->count())
<section class="container-x pt-3 sm:pt-4" data-testid="storefront-home-products">
    <div class="shelf overflow-hidden">
        <div class="shelf-head">
            <h2 class="section-title">{{ gp247_language_render('front.products_new') }}</h2>
            <a href="{{ gp247_route_front('product.all') }}" class="shelf-more">{{ trans('Templates/GP247EC::lang.ec.view_all') }} &rsaquo;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-px bg-ink-100">
            @foreach ($productsNew as $productNew)
                @php
                    [$badgeKey, $badgeClass] = match ((int) $productNew->kind) {
                        GP247_PRODUCT_GROUP => ['front.products_group', 'badge-group'],
                        GP247_PRODUCT_BUILD => ['front.products_bundle', 'badge-bundle'],
                        default => [null, null],
                    };
                @endphp
                <div class="relative bg-white">
                    @if ($badgeKey)
                        <span class="{{ $badgeClass }} absolute bottom-[42%] start-0 z-10 rounded-none rounded-e uppercase">{{ gp247_language_render($badgeKey) }}</span>
                    @endif
                    @livewire('gp247-shop-front::product-card', ['productId' => $productNew->id], key('product-card-'.$productNew->id))
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
