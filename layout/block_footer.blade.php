{{--
    GP247-EC footer: a white trust strip (4 service promises), then a light
    four-column footer with an orange top rule (store, about/contact, useful links, follow us) and a
    bottom copyright row. Icons are inline SVG — the template loads no icon font.
--}}
@php
    $ecT = fn (string $key) => trans('Templates/GP247EC::lang.ec.'.$key);
    $ecTrust = [
        ['trust_shipping', '<path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
        ['trust_genuine', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>'],
        ['trust_return', '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>'],
        ['trust_support', '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1v-6h3zM3 19a2 2 0 0 0 2 2h1v-6H3z"/>'],
    ];
@endphp
<style>
    footer[role="contentinfo"] .gp247-footer-links__link { color: #3f3f46; }
    footer[role="contentinfo"] .gp247-footer-links__link:hover { color: #ea6f1f; }
</style>

<section class="mt-8 border-y border-ink-200 bg-white" aria-label="{{ $ecT('trust_support') }}">
    <div class="container-x grid grid-cols-2 lg:grid-cols-4 divide-ink-100 lg:divide-x rtl:lg:divide-x-reverse">
        @foreach ($ecTrust as [$ecKey, $ecIcon])
            <div class="flex items-center gap-3 px-2 py-4 lg:justify-center">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-500 text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ecIcon !!}</svg>
                </span>
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-ink-900">{{ $ecT($ecKey) }}</span>
                    <span class="block text-xs text-ink-500">{{ $ecT($ecKey.'_sub') }}</span>
                </span>
            </div>
        @endforeach
    </div>
</section>

<footer role="contentinfo" class="border-t-4 border-brand-500 bg-ink-50 text-ink-600 pb-20 md:pb-0">
    <div class="container-x grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 py-10">
        <div>
            <a href="{{ gp247_route_front('front.home') }}" class="flex items-center gap-2.5 mb-4">
                <span class="flex h-10 w-10 items-center justify-center rounded bg-white p-1">
                    <img src="{{ gp247_file(gp247_store_info(key: 'logo', default: null)) }}" alt="{{ gp247_store_info(key: 'name', default: null) }}" class="max-h-full w-auto" />
                </span>
                <span class="text-lg font-extrabold text-ink-900">{{ gp247_store_info(key: 'name', default: null) }}</span>
            </a>
            @if (gp247_store_info(key: 'time_active', default: null))
                <p class="text-sm leading-relaxed">{!! nl2br(e((string) gp247_store_info(key: 'time_active', default: null), false)) !!}</p>
            @endif
        </div>

        <div>
            <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-brand-550">{{ $ecT('contact') }}</h3>
            <ul class="space-y-2.5 text-sm">
                @if (gp247_store_info(key: 'address', default: null))
                    <li><span class="text-ink-500">{{ gp247_language_render('store.address') }}:</span> <span class="text-ink-800">{{ gp247_store_info(key: 'address', default: null) }}</span></li>
                @endif
                @if (gp247_store_info(key: 'long_phone', default: null))
                    <li><span class="text-ink-500">{{ gp247_language_render('store.hotline') }}:</span> <span class="font-semibold text-brand-600">{{ gp247_store_info(key: 'long_phone', default: null) }}</span></li>
                @endif
                @if (gp247_store_info(key: 'email', default: null))
                    <li><span class="text-ink-500">{{ gp247_language_render('store.email') }}:</span> <span class="text-ink-800">{{ gp247_store_info(key: 'email', default: null) }}</span></li>
                @endif
            </ul>
        </div>

        <div>
            <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-brand-550">{{ gp247_language_render('front.link_useful') }}</h3>
            <div class="text-sm"><x-gp247-front::footer-links /></div>
        </div>

        <div>
            <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-brand-550">{{ $ecT('follow_us') }}</h3>
            <div class="flex items-center gap-2">
                @foreach ([
                    'facebook_url' => ['Facebook', '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>'],
                    'twitter_url' => ['X', '<path d="M4 4l16 16M20 4L4 20"/>'],
                    'instagram_url' => ['Instagram', '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>'],
                    'youtube_url' => ['YouTube', '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0.46 5.33A2.78 2.78 0 0 0 3.4 19.3c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2.02A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><path d="M9.75 15.02V8.48L15.5 11.75z"/>'],
                ] as $ecCfg => [$ecLabel, $ecSvg])
                    @if (gp247_config($ecCfg))
                        <a target="_blank" rel="noopener" href="{{ gp247_config($ecCfg) }}" aria-label="{{ $ecLabel }}" class="flex h-9 w-9 items-center justify-center rounded border border-ink-300 bg-white text-ink-600 hover:border-brand-500 hover:bg-brand-500 hover:text-white transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ecSvg !!}</svg>
                        </a>
                    @endif
                @endforeach
            </div>
            @if (gp247_config('fanpage_url'))
                <a target="_blank" rel="noopener" href="{{ gp247_config('fanpage_url') }}" class="mt-4 inline-block text-sm text-ink-700 hover:text-brand-550">Fanpage</a>
            @endif
        </div>
    </div>

    <div class="bg-brand-550 text-brand-50">
        <div class="container-x flex flex-col md:flex-row items-center justify-between gap-2 py-4 text-xs">
            {{-- "Cookie settings": shown by the consent loader of gp247/front when the cookie banner is on
                 (source: gp247/front GP247Front/layout/block_footer.blade.php). WHY class_exists: older fronts have no banner. --}}
            @if (class_exists(\GP247\Front\TemplateComponents\ConsentBanner::class))
                <button type="button" class="font-semibold text-white hover:underline" data-gp247-consent-open data-testid="tracking-consent-footer-settings" hidden>{{ gp247_language_render('front.consent.settings') }}</button>
            @endif
            {{-- WHY: the "hide footer copyright" admin toggle gates both lines. --}}
            @if (!gp247_config('hidden_copyright_footer'))
                <p>&copy; {{ date('Y') }} {{ gp247_store_info(key: 'name', default: null) }}. All rights reserved.</p>
                <span>Power by <a href="{{ config('gp247.homepage') }}" class="font-semibold text-white hover:underline">{{ config('gp247.name') }} {{ config('gp247.sub-version') }}</a></span>
            @endif
        </div>
    </div>
</footer>
