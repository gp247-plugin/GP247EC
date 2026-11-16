{{--
    Product detail page: gallery, buy box (price, attributes, quantity, add to
    cart), description, plugin hook `shop_product_detail_bottom`, related products.

    Variables:
    - $product, $productRelation, $title
--}}
@extends($GP247TemplatePath.'.layout')

{{--
    JSON-LD Product/BreadcrumbList: this screen supplies
    the commerce-specific data ($product, $breadcrumbs); the actual JSON-LD
    building/escaping stays owned by gp247/front (SeoMeta + jsonld_* partials)
    so this package never duplicates that logic.
--}}
@push('jsonld')
    @php
        $jsonldProduct = [
            'name'         => $product->name,
            'url'          => request()->url(),
            'price'        => (string) $product->getFinalPrice(),
            'currency'     => gp247_currency_code(),
            'availability' => $product->allowSale() ? 'InStock' : 'OutOfStock',
            'imageUrl'     => gp247_file($product->getImage()),
            'description'  => $product->description,
            // Identifiers, promotion end and the id rating resolvers are asked with;
            // WHY method_exists: an older gp247/shop has no promotionValidUntil().
            'sku'             => $product->sku,
            'brand'           => $product->brand->name ?? null,
            'gtin12'          => $product->upc,
            'gtin13'          => $product->ean ?: $product->jan,
            'mpn'             => $product->mpn,
            'priceValidUntil' => method_exists($product, 'promotionValidUntil') ? $product->promotionValidUntil() : null,
            'productId'       => (string) $product->id,
        ];
        $breadcrumbItems = array_map(
            fn ($item) => ['name' => $item['title'], 'url' => $item['url'] ?: null],
            $breadcrumbs ?? []
        );
    @endphp
    @include($GP247TemplatePath.'.common.jsonld_product')
    @include($GP247TemplatePath.'.common.jsonld_breadcrumb')
@endpush

@section('block_main_content')
<div class="lg:col-span-12 w-full space-y-4">
    @php
        $galleryImages = collect([$product->getImage()])
            ->merge($product->images->map(fn ($image) => $image->getImage()))
            ->map(fn ($path) => gp247_file($path))
            ->values();
        $ecCanBuy = $product->kind != GP247_PRODUCT_GROUP && $product->allowSale() && gp247_config('product_use_button_add_to_cart');
        $ecDecimal = gp247_qty_decimal_enabled();
        $ecOutOfStock = $product->stock <= 0 && !gp247_config('product_buy_out_of_stock');
    @endphp

    {{-- Product panel: gallery | buy box --}}
    <div class="card grid grid-cols-1 lg:grid-cols-12 gap-6 p-3 sm:p-5">
        {{-- Gallery: main image + thumbnail strip (hover or click to switch) --}}
        <div class="lg:col-span-5" x-data="{ activeImage: 0, images: {{ $galleryImages->toJson() }} }">
            <div class="relative aspect-square overflow-hidden rounded-sm border border-ink-100 bg-white">
                <img :src="images[activeImage]" src="{{ $galleryImages->first() }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
            </div>
            @if ($galleryImages->count() > 1)
            <div class="mt-2 flex gap-2 overflow-x-auto no-scrollbar">
                @foreach ($galleryImages as $key => $imageUrl)
                <button type="button" @click="activeImage = {{ $key }}" @mouseenter="activeImage = {{ $key }}" class="h-16 w-16 shrink-0 overflow-hidden rounded-sm border-2 bg-white" :class="activeImage === {{ $key }} ? 'border-brand-500' : 'border-ink-100'">
                    <img src="{{ $imageUrl }}" alt="" class="w-full h-full object-contain">
                </button>
                @endforeach
            </div>
            @endif
            @if (gp247_config('product_use_button_wishlist') || gp247_config('product_use_button_compare'))
            <div class="mt-4">
                @livewire('gp247-shop-front::product-actions', ['productId' => $product->id], key('product-actions-'.$product->id))
            </div>
            @endif
        </div>

        {{-- Buy box --}}
        <form id="buy_block" action="{{ gp247_route_front('cart.add') }}" method="post" class="lg:col-span-7 flex flex-col">
            {{ csrf_field() }}
            <input type="hidden" name="product_id" id="product-detail-id" value="{{ $product->id }}">

            @if ($product->kind == GP247_PRODUCT_BUILD)
                <span class="badge-bundle mb-2 w-max uppercase">{{ gp247_language_render('front.products_bundle') }}</span>
            @elseif ($product->kind == GP247_PRODUCT_GROUP)
                <span class="badge-group mb-2 w-max uppercase">{{ gp247_language_render('front.products_group') }}</span>
            @endif

            <h1 class="text-xl sm:text-2xl font-semibold leading-snug text-ink-900" id="product-detail-name">{{ $product->name }}</h1>

            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ink-500">
                <span>SKU: <span id="product-detail-model" class="font-semibold text-ink-800">{{ $product->sku }}</span></span>
                @if (gp247_config('product_brand') && !empty($product->brand->name))
                    <span class="h-3 w-px bg-ink-300"></span>
                    <span>{{ gp247_language_render('product.brand') }}:
                        <span id="product-detail-brand"><a class="font-semibold text-brand-600 hover:underline" href="{{ $product->brand->getUrl() }}">{{ $product->brand->name }}</a></span>
                    </span>
                @endif
                @if (gp247_config('product_stock'))
                    <span class="h-3 w-px bg-ink-300"></span>
                    <span>{{ gp247_language_render('product.stock_status') }}:
                        <span id="stock_status" class="font-semibold {{ $ecOutOfStock ? 'text-accent-600' : 'text-emerald-600' }}">
                            @if ($ecOutOfStock)
                                {{ gp247_language_render('product.out_stock') }}
                            @else
                                {{ gp247_language_render('product.in_stock') }}
                            @endif
                        </span>
                    </span>
                @endif
            </div>

            {!! $product->displayVendor() !!}

            {{-- Price strip --}}
            <div class="mt-4 rounded-sm bg-gradient-to-r from-brand-50 to-white px-4 py-4" id="product-detail-price">
                {!! $product->showPriceDetail() !!}
            </div>

            <dl class="mt-4 grid grid-cols-[110px_minmax(0,1fr)] gap-y-3 text-sm">
                <dt class="text-ink-500">{{ gp247_language_render('product.category') }}</dt>
                <dd class="text-ink-800">
                    {{-- WHY: category names are text a user typed — print them with {{ }}, never glued into raw HTML. --}}
                    @foreach ($product->categories as $category)
                        <a class="hover:text-brand-600" href="{{ $category->getUrl() }}">{{ $category->getTitle() }}</a>@if (!$loop->last), @endif
                    @endforeach
                </dd>
                @if (gp247_config('product_available') && $product->date_available >= date('Y-m-d H:i:s'))
                    <dt class="text-ink-500">{{ gp247_language_render('product.date_available') }}</dt>
                    <dd class="text-ink-800"><span id="product-detail-available">{{ $product->date_available }}</span></dd>
                @endif
            </dl>

            @if (gp247_config('product_attribute'))
            <div id="product-detail-attr" class="mt-4">
                @if ($product->attributes())
                {!! $product->renderAttributeDetails() !!}
                @endif
            </div>
            @endif

            @if ($product->kind == GP247_PRODUCT_GROUP)
            <div class="mt-4">
                @php
                    $groups = $product->groups;
                @endphp
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-ink-500">{{ gp247_language_render('product.kind_group') }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($groups as $group)
                    <a target="_blank" href="{{ $group->product->getUrl() }}" class="h-16 w-16 overflow-hidden rounded-sm border border-ink-200 bg-white hover:border-brand-500">
                        {!! gp247_image_render($group->product->image) !!}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($product->kind == GP247_PRODUCT_BUILD)
            <div class="mt-4">
                @php
                    $builds = $product->builds;
                @endphp
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-ink-500">{{ gp247_language_render('product.kind_bundle') }}</p>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-4 rounded-sm border border-dashed border-brand-300 bg-brand-50/50 p-4">
                    @foreach ($builds as $key => $build)
                    @if ($key)
                    <span class="text-lg font-bold text-brand-500">+</span>
                    @endif
                    <a target="_blank" href="{{ $build->product->getUrl() }}" class="group flex w-20 flex-col items-center gap-1.5 text-center">
                        <span class="relative block h-16 w-16">
                            <span class="block h-16 w-16 overflow-hidden rounded-sm border border-ink-200 bg-white">{!! gp247_image_render($build->product->image) !!}</span>
                            <span class="absolute -top-1.5 -end-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-ink-900 text-brand-300 text-[10px] font-bold flex items-center justify-center">{{ gp247_qty_format($build->quantity) }}</span>
                        </span>
                        <span class="text-xs text-ink-600 clamp-2 group-hover:text-brand-600">{{ $build->product->getName() }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($ecCanBuy)
            <div class="mt-6 flex flex-wrap items-center gap-3" x-data="{ qty: 1, step: {{ $ecDecimal ? '0.01' : '1' }}, min: {{ $ecDecimal ? '0.01' : '1' }} }">
                <span class="w-[110px] text-sm text-ink-500">{{ gp247_language_render('product.quantity') }}</span>
                <div class="flex h-10 items-stretch rounded-sm border border-ink-300 bg-white">
                    <button type="button" @click="qty = Math.max(min, +(qty - step).toFixed(2))" class="w-10 text-lg text-ink-600 hover:bg-ink-100" aria-label="-">&minus;</button>
                    <input class="w-16 border-x border-ink-300 text-center text-sm font-semibold outline-none" name="qty" type="number" x-model.number="qty" value="1" min="{{ $ecDecimal ? '0.01' : '1' }}" max="9999" step="{{ $ecDecimal ? '0.01' : '1' }}" data-testid="storefront-product-detail-qty">
                    <button type="button" @click="qty = Math.min(9999, +(qty + step).toFixed(2))" class="w-10 text-lg text-ink-600 hover:bg-ink-100" aria-label="+">+</button>
                </div>
            </div>
            <div class="mt-5">
                <button class="btn-primary btn-lg w-full sm:w-auto sm:min-w-[280px]" type="submit" id="gp247-button-process" data-testid="storefront-product-detail-add-to-cart">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.5 3h2l2.7 12.4a2 2 0 002 1.6h8.6a2 2 0 002-1.6L21 8H6"/></svg>
                    {{ gp247_language_render('action.add_to_cart') }}
                </button>
            </div>
            @endif

            <div class="mt-auto pt-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 border-t border-ink-100 pt-4 text-[11px] text-ink-600">
                    @foreach (['trust_shipping', 'trust_genuine', 'trust_return', 'trust_support'] as $ecKey)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 13l4 4L19 7"/></svg>
                            {{ trans('Templates/GP247EC::lang.ec.'.$ecKey) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    {{-- Description --}}
    <div class="card overflow-hidden">
        <div class="ec-bar px-4 py-3">
            <h2 class="text-sm font-bold uppercase tracking-wide text-ink-900">{{ gp247_language_render('product.description') }}</h2>
        </div>
        <div class="p-4 sm:p-6 text-sm text-ink-700 leading-relaxed space-y-3 [&_a]:text-brand-600 [&_img]:rounded-sm [&_img]:max-w-full">
            {!! gp247_html_render($product->content) !!}
        </div>
    </div>

    {{--
        Storefront extension point: anything a plugin wants to show under the
        product body (reviews, Q&A, size guides…) renders here, via
        config('gp247-config.front.plugin_hooks'). Nothing registered => empty string.
    --}}
    {!! gp247_render_plugin_hook('shop_product_detail_bottom', ['product' => $product]) !!}

    @if ($productRelation->count())
    <div class="shelf overflow-hidden">
        <div class="shelf-head">
            <h2 class="section-title">{{ gp247_language_render('front.products_recommend') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-px bg-ink-100">
            @foreach ($productRelation as $productRel)
            <div class="bg-white">
                @livewire('gp247-shop-front::product-card', ['productId' => $productRel->id], key('product-card-'.$productRel->id))
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @include($GP247TemplatePath.'.layout.extra_blocks')
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
