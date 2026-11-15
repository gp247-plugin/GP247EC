@php
/*
$layout_page = front_home
*/
@endphp

{{--
    Home page. block_main is intentionally empty: all home content comes from
    layout blocks (top / bottom positions), so site owners arrange it in admin
    → Layout block without editing this file.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main')
@endsection

@push('styles')
{{-- Your css style --}}
@endpush

@push('scripts')
{{-- //script here --}}
@endpush
