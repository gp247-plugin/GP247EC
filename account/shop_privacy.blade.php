{{--
    "My data" — GP247-EC port of gp247/shop account/shop_privacy.blade.php: the customer types
    their password (and their two-step code when they use one), the shop prepares a package of
    everything it holds about them, the page asks for progress every few seconds and reloads when
    it is ready; the download is a button (POST). Below: delete my account. Reached only on a
    platform that has the route (the menu link checks it); EC colours (ink / brand / accent).

    Variables (same as the package view):
    - $available (bool), $needsCode (bool)
    - $package (array{status, expires_at, next_at, downloads_left})
    - $erasure (array{status, runs_at}|null), $waitDays (int)
--}}
@php
    $view = gp247_shop_process_view($GP247TemplatePath, 'account.shop_layout');
@endphp
@extends($view)

@section('block_main_profile')
<div class="max-w-xl space-y-4" data-testid="customer-privacy-page">
    @if (session('privacy_success'))
        <div class="rounded-sm border-s-4 border-emerald-600 bg-emerald-50 text-emerald-700 text-sm px-4 py-3" role="status">{{ session('privacy_success') }}</div>
    @endif
    @if (session('privacy_error'))
        <div class="rounded-sm border-s-4 border-accent-600 bg-accent-50 text-accent-700 text-sm px-4 py-3" role="alert" data-testid="customer-privacy-error">{{ session('privacy_error') }}</div>
    @endif

    <p class="text-sm text-ink-600">{{ gp247_language_render('customer.privacy.export_intro') }}</p>

    @if (!$available)
        <p class="text-sm text-ink-600" data-testid="customer-privacy-unavailable">{{ gp247_language_render('customer.privacy.unavailable') }}</p>
    @elseif ($package['status'] === null)
        <form method="POST" action="{{ gp247_route_front('customer.privacy_export') }}" class="space-y-4" data-testid="customer-privacy-export-form">
            @csrf
            <div>
                <label class="label" for="privacy-password">{{ gp247_language_render('customer.privacy.password') }}</label>
                <input id="privacy-password" type="password" name="password" class="input" autocomplete="current-password" required data-testid="customer-privacy-password">
            </div>
            @if ($needsCode)
                <div>
                    <label class="label" for="privacy-code">{{ gp247_language_render('customer.privacy.code') }}</label>
                    <input id="privacy-code" type="text" name="code" class="input" autocomplete="one-time-code" inputmode="numeric" required data-testid="customer-privacy-code">
                </div>
            @endif
            <button class="btn-primary" type="submit" data-testid="customer-privacy-export-submit">{{ gp247_language_render('customer.privacy.export_button') }}</button>
        </form>
    @else
        <div id="privacy-package" data-status="{{ $package['status'] }}" data-status-url="{{ gp247_route_front('customer.privacy_export_status') }}" data-testid="customer-privacy-package">
            <p class="text-sm text-ink-600" id="privacy-package-text">
                @if ($package['status'] === 'building')
                    {{ gp247_language_render('customer.privacy.building') }}
                @elseif ($package['status'] === 'ready')
                    {{ gp247_language_render('customer.privacy.ready', ['at' => $package['expires_at'], 'count' => $package['downloads_left']]) }}
                @else
                    {{ gp247_language_render('customer.privacy.again_at', ['at' => $package['next_at']]) }}
                @endif
            </p>
            @if ($package['status'] === 'ready')
                <form method="POST" action="{{ gp247_route_front('customer.privacy_export_download') }}" class="mt-3">
                    @csrf
                    <button class="btn-primary" type="submit" data-testid="customer-privacy-download">{{ gp247_language_render('customer.privacy.download') }}</button>
                </form>
            @endif
        </div>
    @endif

    {{-- Delete my account --}}
    <div class="border-t border-ink-100 mt-6 pt-4" data-testid="customer-privacy-delete">
        <h2 class="text-base font-bold text-ink-900 mb-2">{{ gp247_language_render('customer.privacy.delete_title') }}</h2>
        @if ($erasure)
            <p class="text-sm text-ink-600" data-testid="customer-privacy-delete-pending">
                {{ $erasure['status'] === 'on_hold'
                    ? gp247_language_render('customer.privacy.delete_on_hold')
                    : gp247_language_render('customer.privacy.delete_scheduled', ['date' => (string) $erasure['runs_at']]) }}
            </p>
            <form method="POST" action="{{ gp247_route_front('customer.privacy_delete_cancel') }}" class="mt-2">
                @csrf
                <button class="btn-primary" type="submit" data-testid="customer-privacy-delete-cancel">{{ gp247_language_render('customer.privacy.cancel_button') }}</button>
            </form>
        @else
            <p class="text-sm text-ink-600">
                {{ $waitDays > 0
                    ? gp247_language_render('customer.privacy.delete_intro', ['days' => $waitDays])
                    : gp247_language_render('customer.privacy.delete_intro_now') }}
            </p>
            <form method="POST" action="{{ gp247_route_front('customer.privacy_delete') }}" class="space-y-4 mt-2" data-testid="customer-privacy-delete-form">
                @csrf
                <div>
                    <label class="label" for="privacy-delete-password">{{ gp247_language_render('customer.privacy.password') }}</label>
                    <input id="privacy-delete-password" type="password" name="password" class="input" autocomplete="current-password" required data-testid="customer-privacy-delete-password">
                </div>
                @if ($needsCode)
                    <div>
                        <label class="label" for="privacy-delete-code">{{ gp247_language_render('customer.privacy.code') }}</label>
                        <input id="privacy-delete-code" type="text" name="code" class="input" autocomplete="one-time-code" inputmode="numeric" required data-testid="customer-privacy-delete-code">
                    </div>
                @endif
                <button class="btn-outline" type="submit" data-testid="customer-privacy-delete-submit">{{ gp247_language_render('customer.privacy.delete_button') }}</button>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var box = document.getElementById('privacy-package');
        if (!box || box.getAttribute('data-status') !== 'building') {
            return;
        }
        var failed = @json(gp247_language_render('customer.privacy.failed'));
        function ask() {
            fetch(box.getAttribute('data-status-url'), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (body) {
                    var state = body && body.data ? body.data : {};
                    if (state.status === 'building') {
                        setTimeout(ask, 3000);
                    } else if (state.status === 'ready') {
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
