@extends('layout')

@section('pageTitle')
    Registrieren
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
        <div class="card-header">Registrierung</div>
    </div>
    <div>
        <form
            role="form"
            method="POST"
            data-controller="form"
            data-turbo="{{ var_export(Str::startsWith(request()->path(), config('platform.prefix'))) }}"
            data-action="form#submit"
            data-form-button-animate="#button-login"
            data-form-button-text="{{ __('Loading...') }}"
            action="{{ route('register') }}">
            @csrf

            <div>
                <label style="line-height: 30px;">
                    Name<br>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        style="padding: 10px; width: 450px">
                </label>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <br><strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label style="line-height: 30px;">
                    E-Mail-Adresse
                    <input
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        style="padding: 10px; width: 450px">
                </label>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <br><strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label style="line-height: 30px;">
                    Passwort
                    <input
                        type="password"
                        name="password"
                        style="padding: 10px; width: 450px">
                </label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <br><strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <label style="line-height: 30px;">
                    Passwort wiederholen
                    <input
                        type="password"
                        name="password_confirmation"
                        style="padding: 10px; width: 450px">
                </label>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <br><strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; padding-right: 50px">
                <div>&nbsp;</div>
                <div>
                    <span><a href="/login" style="position: relative; top: 12px; font-size: 12px; padding-right: 15px">bereits registriert?</a></span>
                    <button type="submit" class="btn btn-primary" style="display: inline-block; padding-left: 30px; padding-right: 30px;">
                        {{ __('Register') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
