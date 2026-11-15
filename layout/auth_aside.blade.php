{{--
    GP247-EC sign-in / sign-up side panel: orange brand panel with the store name,
    a short tagline and the four service promises. Hidden on phones.
--}}
<aside class="relative hidden md:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-brand-500 to-brand-700 p-8 text-white">
    <div>
        <a href="{{ gp247_route_front('front.home') }}" class="flex items-center gap-2.5">
            <span class="flex h-10 w-10 items-center justify-center rounded bg-white p-1">
                <img src="{{ gp247_file(gp247_store_info(key: 'logo', default: null)) }}" alt="{{ gp247_store_info(key: 'name', default: null) }}" class="max-h-full w-auto" />
            </span>
            <span class="text-lg font-extrabold">{{ gp247_store_info(key: 'name', default: null) }}</span>
        </a>
        <p class="mt-8 text-2xl font-extrabold leading-snug">{{ trans('Templates/GP247EC::lang.ec.hello') }} <span>👋</span></p>
        <p class="mt-3 text-sm leading-relaxed text-brand-50">{{ trans('Templates/GP247EC::lang.ec.auth_tagline') }}</p>
    </div>
    <ul class="mt-10 space-y-3 text-sm">
        @foreach (['trust_shipping', 'trust_genuine', 'trust_return', 'trust_support'] as $ecKey)
            <li class="flex items-center gap-3">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/20 text-white">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="text-white">{{ trans('Templates/GP247EC::lang.ec.'.$ecKey) }}</span>
            </li>
        @endforeach
    </ul>
    <span class="pointer-events-none absolute -bottom-16 -end-16 h-56 w-56 rounded-full border-[28px] border-white/15"></span>
</aside>
