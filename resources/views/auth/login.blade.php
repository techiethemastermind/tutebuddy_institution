@extends('layouts.app')

@section('content')

@push('after-styles')

<style type="text/css">
    [dir=ltr] .login-bg {
        background: url({{ asset('assets/img/backgrounds/tutebuddy-bg.jpg') }});
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content login-bg">
    <div class="pt-32pt pt-sm-64pt pb-32pt">
        <div class="page-section container page__container">
            <div class="col-lg-6 p-0 mx-auto">

                <div class="logo text-center mb-32pt">
                    <img src="{{ asset('/assets/img/logos/tutebuddy-logo-full.png') }}" class="w-50" alt="logo">
                </div>

                <form method="POST" action="{{ route('login') }}" class="card card-body p-5">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">{{ __('E-Mail') }}:</label>
                        <input id="email" name="email" type="text"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Your email address ...">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">{{ __('Password') }}:</label>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Your first and last name ...">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <p class="text-right">
                            <a href="/password/reset" class="small">Forgot your password?</a>
                        </p>
                    </div>
                    <input type="hidden" name="prefix" value="{{ $prefix }}">
                    <button class="btn btn-primary">Login</button>
                    <input type="hidden" name="recaptcha_v3" id="recaptcha_v3">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@endsection