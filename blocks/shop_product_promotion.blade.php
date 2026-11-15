{{--
    GP247-EC deals shelf: black header bar with a lightning mark and crimson
    "sale" label, then a horizontally scrolling row of product cards. The
    discount tag is drawn by the card itself.
--}}
@if (class_exists(\GP247\Shop\Models\ShopProduct::class))
@php
    $promotionProducts = $modelProduct->start()->getProductPromotion()->getData();
    // WHY the nearest real end date: the countdown must reflect when a shown
    // promotion actually stops — no countdown when none of them has an end date.
    $ecDealEnd = $promotionProducts->count()
        ? \GP247\Shop\Models\ShopProductPromotion::whereIn('product_id', $promotionProducts->pluck('id'))
            ->where('status_promotion', 1)
            ->where('date_end', '>', now())
            ->min('date_end')
: null;
    $ecDealEndMs = $ecDealEnd ? \Illuminate\Support\Carbon::parse($ecDealEnd)->getTimestampMs() : null;
@endphp
@if ($promotionProducts->count())
<section class="container-x pt-3 sm:pt-4" data-testid="storefront-home-promotion">
    <div class="overflow-hidden rounded border border-ink-200/70 bg-white">
        <div class="flex items-center justify-between gap-3 ec-bar px-3 sm:px-4 py-3">
            <h2 class="flex items-center gap-2 text-base sm:text-lg font-extrabold uppercase italic tracking-wide text-ink-900">
                <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2L3 14h7l-1 8 10-12h-7z"/></svg>
                {{ gp247_language_quickly('front.promotion_products', 'Promotion products') }}
                <span class="badge-sale not-italic">{{ trans('Templates/GP247EC::lang.ec.sale') }}</span>
            </h2>
            @if ($ecDealEndMs)
                <div x-data="gp247ecCountdown({{ $ecDealEndMs }})" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-ink-500 me-auto ms-2" data-testid="storefront-home-promotion-countdown">
                    <span>{{ trans('Templates/GP247EC::lang.ec.ends_in') }}</span>
                    <template x-if="d > 0">
                        <span class="rounded-sm bg-accent-600 px-1.5 py-0.5 font-bold text-white"><span x-text="d"></span> {{ trans('Templates/GP247EC::lang.ec.days') }}</span>
                    </template>
                    <span class="rounded-sm bg-ink-800 px-1.5 py-0.5 font-bold text-white" x-text="h">00</span>:
                    <span class="rounded-sm bg-ink-800 px-1.5 py-0.5 font-bold text-white" x-text="m">00</span>:
                    <span class="rounded-sm bg-ink-800 px-1.5 py-0.5 font-bold text-white" x-text="s">00</span>
                </div>
            @endif
            <a href="{{ gp247_route_front('product.all') }}" class="text-xs sm:text-sm font-semibold text-brand-700 hover:text-brand-800">{{ trans('Templates/GP247EC::lang.ec.view_all') }} &rsaquo;</a>
        </div>
        <div class="flex gap-px overflow-x-auto no-scrollbar snap-x bg-ink-100">
            @foreach ($promotionProducts as $product)
                <div class="snap-start shrink-0 w-[46%] sm:w-[31%] md:w-[24%] lg:w-[16.666%] bg-white">
                    @livewire('gp247-shop-front::product-card', ['productId' => $product->id], key('product-card-'.$product->id))
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
