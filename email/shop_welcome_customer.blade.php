@extends('GP247TemplatePath::GP247EC.email.shop_layout')

@section('main')
<h1 style="box-sizing:border-box;color:#18181b;font-size:19px;font-weight:bold;margin-top:0;text-align:center">{{$title}}</h1>
<p>Hi {{$first_name}} {{$last_name}},</p>
<p>Welcome to my site!</p>
@endsection
