{{-- GP247-EC newsletter band: orange gradient strip with an inline dark-orange submit button. --}}
<section class="container-x pt-3 sm:pt-4">
    <div class="flex flex-col md:flex-row md:items-center gap-4 rounded bg-gradient-to-r from-brand-600 via-brand-500 to-brand-400 px-5 py-6 sm:px-8">
        <div class="flex-1">
            <h2 class="text-lg font-extrabold uppercase tracking-wide text-white">{{ gp247_language_render('subscribe.title') }}</h2>
            <p class="mt-1 text-sm text-brand-50">{{ gp247_language_render('subscribe.subscribe_des') }}</p>
        </div>
        <form method="post" action="{{ gp247_route_front('front.subscribe') }}" class="flex w-full md:w-[440px]">
            @csrf
            <input type="email" name="subscribe_email" required placeholder="{{ gp247_language_render('subscribe.subscribe_email') }}" class="h-11 w-full min-w-0 rounded-s bg-white px-4 text-sm text-ink-900 outline-none" data-testid="storefront-subscribe-email">
            <button type="submit" class="h-11 shrink-0 rounded-e bg-brand-900 px-5 text-sm font-bold text-white hover:bg-brand-800 transition" data-testid="storefront-subscribe-submit">{{ gp247_language_render('subscribe.action') }}</button>
        </form>
    </div>
</section>
