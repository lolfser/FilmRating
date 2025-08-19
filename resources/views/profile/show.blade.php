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
    <?php https://github.com/jasminetracey/lara8auth/blob/main/resources/views/show.blade.php ?>
    @include('profile.partials.profile-form', ['user' => $user, 'errors' => []])
    @include('profile.partials.password-change-form', ['user' => $user, 'errors' => []])
@endsection
