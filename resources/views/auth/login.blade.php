@extends('layout')

@section('pageTitle')
    Login
@endsection

@section('style')
    div.content {width: 450px; margin: 50px auto; padding: 20px;}
    .content div div {margin-bottom: 30px; }

    button {color: white; padding: 10px; background-color: darkgoldenrod}
@endsection

@section('content')
<?php // https://github.com/orchidsoftware/fortify/blob/master/src/AuthServiceProvider.php ?>

<div style="margin: 10px auto 20px; width: 500px;">
    <div style="font-weight: bold;">
        <div class="card-header">{{ __('Login') }}</div>
    </div>
    <div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label style="line-height: 30px;">{{ __('E-Mail Address') }}
                    <input id="email"
                           type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           autofocus
                           style="padding: 10px; width: 450px"
                    >
                </label>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <label style="line-height: 30px;">{{ __('Password') }}
                    <input id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="current-password"
                            style="padding: 10px; width: 450px"
                    >
                </label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; padding-right: 50px">
                <label class="form-check-label" for="remember">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember Me') }}
                </label>
                <div>
                    <span><a href="/register" style="position: relative; top: 12px; font-size: 12px; padding-right: 15px">noch nicht registriert?</a></span>
                    <button type="submit" class="btn btn-primary" style="display: inline-block; padding-left: 30px; padding-right: 30px;">
                        {{ __('Login') }}
                    </button>
                    @if (false && Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}-->
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
