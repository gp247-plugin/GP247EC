{{--
    Forgot-password form.

    Variables:
    - $viewCaptcha: captcha markup rendered by the controller (may be empty)
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full py-2 sm:py-6">
<div class="card mx-auto grid max-w-4xl overflow-hidden md:grid-cols-2">
    @include($GP247TemplatePath.'.layout.auth_aside')
    <div class="p-6 sm:p-10">
    <h1 class="mb-6 border-b-2 border-brand-500 pb-3 text-xl font-extrabold uppercase tracking-wide text-ink-900">{{ gp247_language_render('customer.password_forgot') }}</h1>

    @if (session('status'))
        <div class="rounded-sm border-s-4 border-emerald-600 bg-emerald-50 text-emerald-700 text-sm px-4 py-3 mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ gp247_route_front('customer.password_email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="label">{{ gp247_language_render('customer.email') }}</label>
            <input type="email" class="input @error('email') input-error @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {!! $viewCaptcha ?? '' !!}

        <button type="submit" class="btn-primary btn-lg w-full">{{ gp247_language_render('action.submit()') }}</button>
    </form>
</div>
</div>
</div>
@endsection
