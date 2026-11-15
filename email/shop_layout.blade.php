<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{{--
    GP247-EC transactional mail layout (shop): orange header,
    white body, dark footer. Table layout + inline styles only — mail clients
    ignore the storefront CSS. Colours: #ea6f1f header, #fb8a3c rules, #fff8f1 tints.
--}}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        @media only screen and (max-width: 600px) {
            .ec-inner { width: 100% !important; }
.ec-cell { padding: 24px 18px !important; }
        }
        @media only screen and (max-width: 500px) {
            .button { width: 100% !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;color:#3f3f46;">
    <tr>
        <td align="center" style="padding:24px 12px;">
            <table class="ec-inner" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background-color:#ffffff;border-radius:4px;overflow:hidden;">
                {{-- header --}}
                <tr>
                    <td style="background-color:#ea6f1f;border-bottom:3px solid #c2560f;padding:20px 32px;">
                        <a href="{{ gp247_route_front('front.home') }}" target="_blank" style="color:#ffffff;font-size:20px;font-weight:bold;text-decoration:none;letter-spacing:.3px;">{{ config('app.name') }}</a>
                    </td>
                </tr>
                {{-- main --}}
                <tr>
                    <td class="ec-cell" style="padding:32px;font-size:14px;line-height:1.6;color:#3f3f46;">
                        @yield('main')
                        <p style="margin:24px 0 0;font-size:14px;line-height:1.5em;color:#3f3f46;">
                            {{ gp247_language_render('email.msg_goodbye') }}<br>
                            <strong style="color:#18181b;">{{ config('app.name') }}</strong>
                        </p>
                    </td>
                </tr>
                {{-- footer --}}
                <tr>
                    <td align="center" style="background-color:#fff8f1;padding:16px 32px;">
                        <p style="margin:0;color:#9a4410;font-size:12px;line-height:1.5em;">&copy;{{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
