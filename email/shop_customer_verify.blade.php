@extends('GP247TemplatePath::GP247EC.email.shop_layout')

@section('main')
<h1 style="box-sizing:border-box;color:#18181b;font-size:19px;font-weight:bold;margin-top:0;text-align:left">{{$title}}</h1>
<p style="box-sizing:border-box;color:#52525b;font-size:16px;line-height:1.5em;margin-top:0;text-align:left">{{$reason_sendmail}}</p>
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" style="box-sizing:border-box;margin:30px auto;padding:0;text-align:center;width:100%">
    <tbody>
    <tr>
        <td align="center" style="box-sizing:border-box">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="box-sizing:border-box">
                <tbody>
                <tr>
                    <td align="center" style="box-sizing:border-box">
                        <table border="0" cellpadding="0" cellspacing="0" style="box-sizing:border-box">
                            <tbody>
                            <tr>
                                <td style="box-sizing:border-box">
                                    <a href="{{$url_verify}}" class="button button-primary" style="box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#f97316;border-top:10px solid #f97316;border-right:18px solid #f97316;border-bottom:10px solid #f97316;border-left:18px solid #f97316" target="_blank">{{$button}}</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
    </table>
    <p style="box-sizing:border-box;color:#52525b;font-size:16px;line-height:1.5em;margin-top:0;text-align:left">
    {{$note_sendmail}}
    </p>
    <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="box-sizing:border-box;border-top:1px solid #edeff2;margin-top:25px;padding-top:25px">
    <tbody>
    <tr>
        <td style="box-sizing:border-box">
            <p style="box-sizing:border-box;color:#52525b;line-height:1.5em;margin-top:0;text-align:left;font-size:12px">{{$note_access_link}}</p>
        </td>
    </tr>
    </tbody>
</table>
@endsection
