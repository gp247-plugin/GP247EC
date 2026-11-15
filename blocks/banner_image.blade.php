{{--
    GP247-EC home hero — marketplace layout: the "banner" slider takes two thirds,
    a side column holds two promo tiles (the store's "banner-store" image when it
    has one, and a "hot deals" tile linking to all products). On phones only the
    slider shows. Slides keep the click-tracking route and drag/swipe behaviour of
    the default slider (gp247ecBannerSlider in js/app.js).
--}}
@php
    $banners = $modelBanner->start()->setType('banner')->getData();
    $ecStoreBanner = $modelBanner->start()->setType('banner-store')->getData()->first();
@endphp
@if ($banners->count())
<section class="container-x pt-3 sm:pt-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 sm:gap-3">
        <div
            x-data="gp247ecBannerSlider({{ $banners->count() }}, {{ gp247ec_is_rtl() ? 'true' : 'false' }})"
            class="relative lg:col-span-2 overflow-hidden rounded bg-ink-900 text-white select-none touch-pan-y group"
            :class="dragging ? 'cursor-grabbing' : 'cursor-grab'"
            @mousedown="dragStart($event)" @mousemove="dragMove($event)" @mouseup="dragEnd()" @mouseleave="dragEnd()"
            @touchstart="dragStart($event)" @touchmove="dragMove($event)" @touchend="dragEnd()"
            data-testid="storefront-home-banner"
        >
            <div class="flex" :style="trackStyle">
                @foreach ($banners as $banner)
                    <div class="w-full shrink-0 relative aspect-[2/1]">
                        @if ($banner->html)
                            <div class="absolute inset-0 flex items-center p-6 sm:p-10">
                                {!! gp247_html_render($banner->html) !!}
                            </div>
                            @if ($banner->url)
                                <a href="{{ gp247_route_front('front.banner.click', ['id' => $banner->id]) }}" target="{{ $banner->target }}" @click="linkClick($event)" class="btn-primary absolute bottom-5 start-6 sm:start-10">
                                    {{ gp247_language_quickly('front.banner.click_link', 'Click link') }}
                                </a>
                            @endif
                        @else
                            @if ($banner->url)
                                <a href="{{ gp247_route_front('front.banner.click', ['id' => $banner->id]) }}" target="{{ $banner->target }}" @click="linkClick($event)" class="block w-full h-full">
                                    <img src="{{ gp247_file($banner->image) }}" alt="{{ $banner->name }}" class="w-full h-full object-cover" draggable="false" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                </a>
                            @else
                                <img src="{{ gp247_file($banner->image) }}" alt="{{ $banner->name }}" class="w-full h-full object-cover" draggable="false" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($banners->count() > 1)
                <button type="button" @click="index = (index - 1 + count) % count" class="absolute inset-y-0 start-0 my-auto h-12 w-8 bg-black/40 text-white opacity-0 group-hover:opacity-100 transition flex items-center justify-center" aria-label="Previous">
                    <svg class="w-5 h-5 rtl-flip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button" @click="index = (index + 1) % count" class="absolute inset-y-0 end-0 my-auto h-12 w-8 bg-black/40 text-white opacity-0 group-hover:opacity-100 transition flex items-center justify-center" aria-label="Next">
                    <svg class="w-5 h-5 rtl-flip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
                <div class="absolute bottom-3 start-1/2 -translate-x-1/2 flex gap-1.5" role="tablist" aria-label="{{ gp247_language_quickly('front.banner', 'Banner') }}">
                    <template x-for="i in count" :key="i">
                        <button type="button" @click="index = i - 1" class="h-1.5 rounded-full transition-all" :class="index === i - 1 ? 'w-6 bg-brand-400' : 'w-3 bg-white/60'" :aria-selected="index === i - 1" role="tab" :aria-label="'Slide ' + i"></button>
                    </template>
                </div>
            @endif
        </div>

        {{-- WHY absolute: the side tiles must take the slider's height, not push the row taller with their image's natural height. --}}
        <div class="relative hidden lg:block">
        <div class="absolute inset-0 grid grid-rows-2 gap-3">
            @if ($ecStoreBanner && $ecStoreBanner->image)
                <a href="{{ $ecStoreBanner->url ? gp247_route_front('front.banner.click', ['id' => $ecStoreBanner->id]) : gp247_route_front('product.all') }}" class="relative block min-h-0 overflow-hidden rounded bg-ink-900">
                    <img src="{{ gp247_file($ecStoreBanner->image) }}" alt="{{ $ecStoreBanner->name }}" class="w-full h-full object-cover opacity-80 hover:opacity-100 hover:scale-105 transition duration-300" loading="lazy">
                </a>
            @else
                <div class="min-h-0 rounded bg-brand-700 p-6 flex flex-col justify-center">
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-100">{{ gp247_store_info(key: 'name', default: null) }}</p>
                    <p class="mt-2 text-lg font-bold text-white clamp-2">{{ gp247_language_quickly('front.welcome_message', 'Welcome') }}</p>
                </div>
            @endif
            <a href="{{ gp247_route_front('product.all') }}" class="relative flex min-h-0 flex-col justify-center overflow-hidden rounded bg-gradient-to-br from-brand-500 to-brand-800 p-6 text-white hover:from-brand-600 transition">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-100">{{ trans('Templates/GP247EC::lang.ec.sale') }}</span>
                <span class="mt-1 text-2xl font-extrabold">{{ trans('Templates/GP247EC::lang.ec.deals') }}</span>
                <span class="mt-3 inline-flex w-max items-center gap-1 rounded-sm bg-white px-3 py-1.5 text-xs font-bold text-brand-700">
                    {{ trans('Templates/GP247EC::lang.ec.view_all') }}
                    <svg class="w-3.5 h-3.5 rtl-flip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </span>
                <svg class="absolute -end-6 -bottom-6 w-32 h-32 text-white/10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2L3 14h7l-1 8 10-12h-7z"/></svg>
            </a>
        </div>
        </div>
    </div>
</section>
@endif
