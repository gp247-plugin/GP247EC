{{--
    404 page: large code, short message and a link back home.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-ink-800">404</h1>
        <h2 class="text-2xl font-semibold text-ink-600 mt-4">{{ gp247_language_render('front.404') }}</h2>
        <p class="text-ink-500 mt-4 mb-8">{{ $msg ?? '' }}</p>
        <a href="{{ gp247_route_front('front.home') }}" class="btn-primary">
            {{ gp247_language_render('front.backhome') }}
        </a>
    </div>
</div>
@endsection
