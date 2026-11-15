{{--
    GP247-EC brand shelf: white panel, logo tiles in a 1px-gutter grid (two rows
    scroll sideways on phones). Each tile links to the brand's product listing.
--}}
@if (class_exists(\GP247\Shop\Models\ShopBrand::class) && isset($modelBrand))
@php
    $ecBrands = $modelBrand->start()->setLimit(12)->getData();
@endphp
@if ($ecBrands->count())
<section class="container-x pt-3 sm:pt-4" data-testid="storefront-home-brands">
    <div class="shelf overflow-hidden">
        <div class="shelf-head">
            <h2 class="section-title">{{ trans('Templates/GP247EC::lang.ec.shop_brands') }}</h2>
            <a href="{{ gp247_route_front('brand.all') }}" class="shelf-more">{{ trans('Templates/GP247EC::lang.ec.view_all') }} &rsaquo;</a>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-px bg-ink-100">
            @foreach ($ecBrands as $ecBrand)
                <a href="{{ $ecBrand->getUrl() }}" class="group flex flex-col items-center justify-center gap-2 bg-white px-3 py-5 hover:bg-brand-50 transition">
                    <span class="flex h-14 w-full items-center justify-center">
                        <img src="{{ gp247_file($ecBrand->getThumb()) }}" alt="{{ $ecBrand->name }}" class="max-h-full max-w-[120px] object-contain grayscale group-hover:grayscale-0 transition" loading="lazy">
                    </span>
                    <span class="text-xs font-semibold text-ink-700 group-hover:text-brand-700 clamp-1">{{ $ecBrand->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
