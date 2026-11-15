{{--
    Confirmation mail to the visitor. Deliberately generic: the address was typed by
    an anonymous visitor, so nothing they wrote is echoed back — the contact form
    must not become a way to send chosen text to anyone.

    Variables (pre-rendered by ContactMailer): $storeTitle, $intro
--}}
@extends('GP247TemplatePath::GP247EC.email.front_layout')

@section('main')
<p style="margin:0;">{{ $intro }}</p>
@endsection
