@extends('layout')
@section('pageTitle')
    Willkommen
@endsection

@section('style')
    div.content {width: 450px; margin: 50px auto; padding: 20px;}
    .content div div {margin-bottom: 30px; }

    .content a {color: white; padding: 10px; background-color: darkgoldenrod}
    .content a:hover {color: white; background-color: chocolate}
@endsection

@section('content')
<div style="margin: 10px auto 20px; width: 500px;">
    <div style="font-weight: bold;">
        Willkommen
    </div>
    <div>
        <a href="/login">Login</a>
        <a href="/register">Registrieren</a>
    </div>
</div>
@endsection
