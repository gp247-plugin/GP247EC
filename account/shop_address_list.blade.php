{{--
    Customer address list: one card per address with edit / delete actions.
    Delete posts to `customer.delete_address` with fetch() (no jQuery) and reloads
    the page on success.

    Variables:
    - $addresses: the customer's addresses
    - $title: page title
--}}
@php
    $view = gp247_shop_process_view($GP247TemplatePath, 'account.shop_layout');
@endphp
@extends($view)

@section('block_main_profile')
@if (count($addresses) == 0)
    <div class="card p-10 text-center text-ink-500">{{ gp247_language_render('front.no_item') }}</div>
@else
    <div class="grid sm:grid-cols-2 gap-4">
        @foreach ($addresses as $address)
            <div class="card p-4">
                <table class="w-full text-sm">
                    @if (gp247_config('customer_lastname'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.first_name') }}</td><td class="py-1 font-medium">{{ $address['first_name'] }}</td></tr>
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.last_name') }}</td><td class="py-1 font-medium">{{ $address['last_name'] }}</td></tr>
                    @else
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.name') }}</td><td class="py-1 font-medium">{{ $address['first_name'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_phone'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.phone') }}</td><td class="py-1 font-medium">{{ $address['phone'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_postcode'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.postcode') }}</td><td class="py-1 font-medium">{{ $address['postcode'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_city'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.city') }}</td><td class="py-1 font-medium">{{ $address['city'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_district'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.district') }}</td><td class="py-1 font-medium">{{ $address['district'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_ward'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.ward') }}</td><td class="py-1 font-medium">{{ $address['ward'] }}</td></tr>
                    @endif
                    <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.address1') }}</td><td class="py-1 font-medium">{{ $address['address1'] }}</td></tr>
                    @if (gp247_config('customer_address2'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.address2') }}</td><td class="py-1 font-medium">{{ $address['address2'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_address3'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.address3') }}</td><td class="py-1 font-medium">{{ $address['address3'] }}</td></tr>
                    @endif
                    @if (gp247_config('customer_country'))
                        <tr><td class="py-1 text-ink-500">{{ gp247_language_render('customer.country') }}</td><td class="py-1 font-medium">{{ $countries[$address['country']] ?? $address['country'] }}</td></tr>
                    @endif
                </table>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-ink-100">
                    <a title="{{ gp247_language_render('action.edit') }}" href="{{ gp247_route_front('customer.update_address', ['id' => $address->id]) }}" class="btn-icon btn-sm h-8 w-8">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                    </a>
                    <a href="#" title="{{ gp247_language_render('action.delete') }}" class="delete-address btn-icon btn-sm h-8 w-8 text-accent-600" data-id="{{ $address->id }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0l-1 14a2 2 0 01-2 2H7a2 2 0 01-2-2L4 6"/></svg>
                    </a>
                    @if ($address->id == customer()->user()->address_id)
                        <span class="badge-brand" title="{{ gp247_language_render('customer.address_default') }}">{{ gp247_language_render('customer.address_default') }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-address').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            if (!confirm("{{ e(gp247_language_render('action.delete_confirm')) }}")) {
                return;
            }
            var id = el.dataset.id;
            fetch("{{ gp247_route_front('customer.delete_address') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: 'id=' + encodeURIComponent(id) + '&_token={{ csrf_token() }}'
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.error == 0) {
                        window.location.reload();
                    }
                });
        });
    });
</script>
@endpush
