{{--
    GP247-EC "top picks by category" shelf: one tab per top category (up to 5),
    each showing its latest products as static tiles (common/ec_product_tile).
    Tabs switch client-side with Alpine; every tab is server-rendered so the
    products stay crawlable. Categories without products are left out.
--}}
@if (class_exists(\GP247\Shop\Models\ShopProduct::class) && class_exists(\GP247\Shop\Models\ShopCategory::class))
@php
    $ecTabs = [];
    foreach ($modelCategory->start()->getCategoryRoot()->getCategoryTop()->getData()->take(5) as $ecCat) {
        $ecItems = $modelProduct->start()->getProductToCategory([$ecCat->id])->setLimit(6)->setSort(['created_at', 'desc'])->getData();
        if ($ecItems->count()) {
            $ecTabs[] = ['category' => $ecCat, 'products' => $ecItems];
        }
    }
@endphp
@if (count($ecTabs))
<section class="container-x pt-3 sm:pt-4" x-data="{ tab: 0 }" data-testid="storefront-home-category-tabs">
    <div class="shelf overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center gap-2 border-b border-ink-100 px-3 sm:px-4 pt-3 md:pt-0">
            <h2 class="section-title shrink-0 md:py-3">{{ trans('Templates/GP247EC::lang.ec.top_picks') }}</h2>
            <div class="flex gap-1 overflow-x-auto no-scrollbar md:ms-auto" role="tablist">
                @foreach ($ecTabs as $i => $ecTab)
                    <button type="button" role="tab" @click="tab = {{ $i }}" :aria-selected="(tab === {{ $i }}).toString()"
                        class="shrink-0 border-b-2 px-3 py-3 text-sm font-semibold transition"
:class="tab === {{ $i }} ? 'border-brand-500 text-brand-800' : 'border-transparent text-ink-500 hover:text-ink-900'">
                        {{ $ecTab['category']->name }}
                    </button>
                @endforeach
            </div>
        </div>
        @foreach ($ecTabs as $i => $ecTab)
            <div role="tabpanel" x-show="tab === {{ $i }}" @if ($i) x-cloak @endif>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-px bg-ink-100">
                    @foreach ($ecTab['products'] as $product)
                        <div class="bg-white">@include($GP247TemplatePath.'.common.ec_product_tile', ['product' => $product])</div>
                    @endforeach
                </div>
                <div class="border-t border-ink-100 py-2.5 text-center">
                    <a href="{{ $ecTab['category']->getUrl() }}" class="shelf-more">{{ trans('Templates/GP247EC::lang.ec.view_all') }} — {{ $ecTab['category']->name }} &rsaquo;</a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
@endif
