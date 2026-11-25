{{--
    Personal data pages that need no sign-in — GP247-EC port of gp247/shop
    screen/shop_privacy_request.blade.php. One view, five modes: form (shoppers without an account
    ask for, or ask to erase, their data), message, package (download after the email link),
    cancel and confirm (buttons, so mail scanners change nothing). EC colours (ink / accent / emerald).

    Variables: $mode, $title; form: $viewCaptcha, $honeypot; message/cancel: $message, $confirmUrl;
    package: $packageStatus, $statusUrl, $downloadUrl.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full">
    <h1 class="section-title mb-6">{{ $title }}</h1>
    <div class="card p-6 max-w-xl space-y-4" data-testid="customer-privacy-request-page">
        @if (session('privacy_success'))
            <div class="rounded-sm border-s-4 border-emerald-600 bg-emerald-50 text-emerald-700 text-sm px-4 py-3" role="status" data-testid="customer-privacy-request-sent">{{ session('privacy_success') }}</div>
        @endif

        @if ($mode === 'form')
            <p class="text-sm text-ink-600">{{ gp247_language_render('customer.privacy.guest_intro') }}</p>
            <form method="POST" action="{{ gp247_route_front('shop.privacy_request_post') }}" class="space-y-4" id="gp247-form-process" data-testid="customer-privacy-request-form">
                @csrf
                {{-- Anti-bot trap: hidden from people and screen readers, filled only by bots. --}}
                <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden" aria-hidden="true">
                    <label for="privacy-{{ $honeypot }}">{{ $honeypot }}</label>
                    <input type="text" id="privacy-{{ $honeypot }}" name="{{ $honeypot }}" value="" tabindex="-1" autocomplete="off">
                </div>
                <div>
                    <label class="label" for="privacy-email">{{ gp247_language_render('customer.privacy.guest_email') }}</label>
                    <input type="email" id="privacy-email" name="email" maxlength="150" required value="{{ old('email') }}"
                        class="input @error('email') input-error @enderror" data-testid="customer-privacy-request-email">
                    @error('email')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="privacy-kind">{{ gp247_language_render('customer.privacy.guest_kind') }}</label>
                    <select id="privacy-kind" name="kind" class="input" data-testid="customer-privacy-request-kind">
                        <option value="export" @selected(old('kind') !== 'erase')>{{ gp247_language_render('customer.privacy.guest_kind_export') }}</option>
                        <option value="erase" @selected(old('kind') === 'erase')>{{ gp247_language_render('customer.privacy.guest_kind_erase') }}</option>
                    </select>
                </div>
                {!! $viewCaptcha ?? '' !!}
                @error('captcha_field')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
                <button type="submit" class="btn-primary" id="gp247-button-process" data-testid="customer-privacy-request-submit">{{ gp247_language_render('customer.privacy.guest_submit') }}</button>
            </form>
        @elseif ($mode === 'package')
            <div id="privacy-package" data-status="{{ $packageStatus }}" data-status-url="{{ $statusUrl }}" data-testid="customer-privacy-request-package">
                <p class="text-sm text-ink-600" id="privacy-package-text">
                    {{ gp247_language_render($packageStatus === 'ready' ? 'customer.privacy.guest_ready' : ($packageStatus === 'building' ? 'customer.privacy.building' : 'customer.privacy.failed')) }}
                </p>
                @if ($packageStatus === 'ready')
                    <form method="POST" action="{{ $downloadUrl }}" class="mt-3">
                        @csrf
                        <button class="btn-primary" type="submit" data-testid="customer-privacy-request-download">{{ gp247_language_render('customer.privacy.download') }}</button>
                    </form>
                @endif
            </div>
        @elseif ($mode === 'cancel' || $mode === 'confirm')
            <p class="text-sm text-ink-600">{{ $message }}</p>
            <form method="POST" action="{{ $confirmUrl }}">
                @csrf
                @if ($mode === 'cancel')
                    <button class="btn-primary" type="submit" data-testid="customer-privacy-cancel-confirm">{{ gp247_language_render('customer.privacy.cancel_button') }}</button>
                @else
                    <button class="btn-primary" type="submit" data-testid="customer-privacy-request-confirm">{{ gp247_language_render('customer.privacy.guest_confirm_button') }}</button>
                @endif
            </form>
        @else
            <p class="text-sm text-ink-600" data-testid="customer-privacy-request-message">{{ $message }}</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var box = document.getElementById('privacy-package');
        if (!box || box.getAttribute('data-status') !== 'building' || !box.getAttribute('data-status-url')) {
            return;
        }
        var failed = @json(gp247_language_render('customer.privacy.failed'));
        function ask() {
            fetch(box.getAttribute('data-status-url'), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (body) {
                    var status = body && body.data ? body.data.status : null;
                    if (status === 'building') {
                        setTimeout(ask, 3000);
                    } else if (status === 'ready') {
                        window.location.reload();
                    } else {
                        document.getElementById('privacy-package-text').textContent = failed;
                    }
                })
                .catch(function () { setTimeout(ask, 6000); });
        }
        setTimeout(ask, 1500);
    })();
</script>
@endpush
