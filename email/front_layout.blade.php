<!DOCTYPE html>
{{--
    GP247-EC mail layout for gp247/front mails (contact form): same look as
    email/shop_layout — orange header, white body, light-orange footer.
    Inline styles only.
--}}
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;font-family:Arial,Helvetica,sans-serif;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:4px;overflow:hidden;">
                <tr>
                    <td style="background-color:#ea6f1f;border-bottom:3px solid #c2560f;padding:20px 32px;color:#ffffff;font-size:18px;font-weight:bold;">
                        {{ $storeTitle ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 32px;color:#3f3f46;font-size:14px;line-height:1.6;">
                        @yield('main')
                    </td>
                </tr>
                <tr>
                    <td align="center" style="background-color:#fff8f1;padding:14px 32px;color:#9a4410;font-size:12px;">
                        &copy;{{ date('Y') }} {{ $storeTitle ?? '' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
