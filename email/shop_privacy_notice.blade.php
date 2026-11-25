{{--
    Emails of personal data requests — GP247-EC port of gp247/shop email/shop_privacy_notice.blade.php
    (EC orange button, EC email layout). One view for every message; the words are labels under
    email.privacy.<message>.* that the owner edits on the Languages screen.

    Variables: $title, $body (escaped here); $button, $url (one link, or none).
--}}
@extends('GP247TemplatePath::GP247EC.email.shop_layout')

@section('main')
<h1 style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#18181b;font-size:19px;font-weight:bold;margin-top:0;text-align:left">{{ $title }}</h1>
<p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;font-size:16px;line-height:1.5em;margin-top:0;text-align:left">{{ $body }}</p>
@if (!empty($url) && !empty($button))
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:30px auto;padding:0;text-align:center;width:100%">
    <tbody>
        <tr>
            <td align="center" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
                <a href="{{ $url }}" class="button button-primary" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#fb8a3c;border-top:10px solid #fb8a3c;border-right:18px solid #fb8a3c;border-bottom:10px solid #fb8a3c;border-left:18px solid #fb8a3c" target="_blank">{{ $button }}</a>
            </td>
        </tr>
    </tbody>
</table>
<p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;font-size:12px;line-height:1.5em;margin-top:0;text-align:left;word-break:break-all">{{ $url }}</p>
@endif
@endsection
