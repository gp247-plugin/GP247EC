@extends('GP247TemplatePath::GP247EC.email.shop_layout')

@section('main')
<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background-color:#fff8f1;border-left:4px solid #fb8a3c;">
    <tr>
        <td style="padding:16px 18px;font-size:14px;line-height:1.7;color:#3f3f46;">
            <div style="font-size:16px;font-weight:bold;color:#18181b;margin-bottom:6px;">{{ gp247_language_render('order.id') }}: <span style="color:#c2560f;">{{ $orderID }}</span></div>
            <div><span style="color:#71717a;">{{ gp247_language_render('order.full_name') }}:</span> {{ $toname }}</div>
            <div><span style="color:#71717a;">{{ gp247_language_render('order.address') }}:</span> {{ $address }}</div>
            <div><span style="color:#71717a;">{{ gp247_language_render('order.phone') }}:</span> {{ $phone }}</div>
            <div><span style="color:#71717a;">{{ gp247_language_render('order.currency') }}:</span> {{ $currency }}</div>
            @if (!empty($comment))
            <div><span style="color:#71717a;">{{ gp247_language_render('order.order_note') }}:</span> {{ $comment }}</div>
            @endif
        </td>
    </tr>
</table>

<h3 style="margin:28px 0 10px;font-size:14px;text-transform:uppercase;letter-spacing:.5px;color:#18181b;">{{ gp247_language_render('order.order_detail') }}</h3>
<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:13px;">
    <tr style="background-color:#fff8f1;color:#9a4410;border-bottom:2px solid #fb8a3c;">
        <td style="padding:8px 10px;font-weight:bold;">{{ gp247_language_render('email.order.sort') }}</td>
        <td style="padding:8px 10px;font-weight:bold;">{{ gp247_language_render('email.order.sku') }}</td>
        <td style="padding:8px 10px;font-weight:bold;">{{ gp247_language_render('email.order.name') }}</td>
        <td style="padding:8px 10px;font-weight:bold;" align="right">{{ gp247_language_render('email.order.price') }}</td>
        <td style="padding:8px 10px;font-weight:bold;" align="center">{{ gp247_language_render('email.order.qty') }}</td>
        <td style="padding:8px 10px;font-weight:bold;" align="right">{{ gp247_language_render('email.order.total') }}</td>
    </tr>
    @foreach ($orderDetail as $key => $item)
    <tr style="border-bottom:1px solid #e4e4e7;">
        <td style="padding:10px;color:#71717a;">{{ $key + 1 }}</td>
        <td style="padding:10px;color:#71717a;">{{ $item['sku'] }}</td>
        <td style="padding:10px;color:#18181b;">{{ $item['name'] }}
            @if ($item['linkDownload'])
                <br><a href="{{ $item['linkDownload'] }}" style="color:#c2560f;font-weight:bold;">Download</a>
            @endif
        </td>
        <td style="padding:10px;" align="right">{{ $item['price'] }}</td>
        <td style="padding:10px;" align="center">{{ rtrim(rtrim(number_format($item['qty'], 2, '.', ''), '0'), '.') }}</td>
        <td style="padding:10px;font-weight:bold;color:#18181b;" align="right">{{ $item['total'] }}</td>
    </tr>
    @endforeach
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:12px;font-size:14px;">
    <tr>
        <td style="padding:4px 10px;color:#71717a;" align="right">{{ gp247_language_render('order.subtotal') }}</td>
        <td style="padding:4px 10px;width:130px;" align="right">{{ $subtotal }}</td>
    </tr>
    <tr>
        <td style="padding:4px 10px;color:#71717a;" align="right">{{ gp247_language_render('order.shipping') }}</td>
        <td style="padding:4px 10px;" align="right">{{ $shipping }}</td>
    </tr>
    <tr>
        <td style="padding:4px 10px;color:#71717a;" align="right">{{ gp247_language_render('order.discount') }}</td>
        <td style="padding:4px 10px;" align="right">{{ $discount }}</td>
    </tr>
    <tr>
        <td style="padding:10px;border-top:2px solid #fb8a3c;font-size:16px;font-weight:bold;color:#18181b;" align="right">{{ gp247_language_render('order.total') }}</td>
        <td style="padding:10px;border-top:2px solid #fb8a3c;font-size:18px;font-weight:bold;color:#be123c;" align="right">{{ $total }}</td>
    </tr>
</table>
@endsection
