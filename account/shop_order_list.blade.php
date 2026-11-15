{{--
    Customer order history table.

    Variables:
    - $statusOrder: status id => label map
    - $orders: the customer's orders
--}}
@php
    $view = gp247_shop_process_view($GP247TemplatePath, 'account.shop_layout');
@endphp
@extends($view)

@section('block_main_profile')
@if (count($orders) == 0)
    <div class="card p-10 text-center text-ink-500">{{ gp247_language_render('front.no_item') }}</div>
@else
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-[11px] uppercase tracking-wide border-b border-ink-200">
                <tr>
                    <th class="text-start px-4 py-3">No.</th>
                    <th class="text-start px-4 py-3">ID</th>
                    <th class="text-end px-4 py-3">{{ gp247_language_render('order.total') }}</th>
                    <th class="text-start px-4 py-3">{{ gp247_language_render('order.order_status') }}</th>
                    <th class="text-start px-4 py-3">{{ gp247_language_render('common.created_at') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @php $n = 0; @endphp
                @foreach ($orders as $order)
                    @php $n++; @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $n }}</td>
                        <td class="px-4 py-3 font-mono text-xs">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-end font-semibold">{{ number_format($order->total) }}</td>
                        <td class="px-4 py-3"><span class="badge-brand">{{ $statusOrder[$order->status] }}</span></td>
                        <td class="px-4 py-3 text-ink-500">{{ $order->created_at }}</td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ gp247_route_front('customer.order_detail', ['id' => $order->id]) }}" class="text-brand-600 font-medium">{{ gp247_language_render('order.detail') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
