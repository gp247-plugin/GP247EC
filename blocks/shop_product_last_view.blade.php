{{-- GP247-EC "recently viewed" side panel: compact list with thumbnail, name and view time. --}}
@if (class_exists(\GP247\Shop\Models\ShopProduct::class))
@php
    $arrProductsLastView = [];
    $lastView = empty(\Cookie::get('productsLastView')) ? [] : json_decode(\Cookie::get('productsLastView'), true);
    if ($lastView) {
        arsort($lastView);
    }
    if ($lastView && count($lastView)) {
        $lastView = array_slice($lastView, 0, gp247_config('product_viewed'), true);
        $productsLastView = $modelProduct->start()->getProductFromListID(array_keys($lastView))->getData();
        foreach ($lastView as $pId => $time) {
            foreach ($productsLastView as $product) {
                if ($product['id'] == $pId) {
                    $product['timelastview'] = $time;
                    $arrProductsLastView[] = $product;
                }
            }
        }
    }
@endphp
@if (!empty($arrProductsLastView))
<div class="shelf">
    <div class="shelf-head">
        <h3 class="panel-title">{{ gp247_language_render('front.products_last_view') }}</h3>
    </div>
    <div class="divide-y divide-ink-100">
        @foreach ($arrProductsLastView as $productLastView)
            <a href="{{ $productLastView->getUrl() }}" class="flex gap-3 px-3 py-2.5 group hover:bg-brand-50">
                <img src="{{ gp247_file($productLastView->getThumb()) }}" alt="{{ $productLastView->name }}" class="w-14 h-14 rounded-sm border border-ink-100 object-contain shrink-0 bg-white" loading="lazy">
                <div class="min-w-0">
                    <p class="text-[13px] text-ink-800 clamp-2 group-hover:text-brand-600">{{ $productLastView->name }}</p>
                    <time datetime="{{ gp247_datetime_to_date($productLastView['timelastview'], 'Y-m-d H:i:s') }}" class="text-[11px] text-ink-400">
                        {{ gp247_datetime_to_date($productLastView['timelastview'], 'Y-m-d H:i:s') }}
                    </time>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
@endif
