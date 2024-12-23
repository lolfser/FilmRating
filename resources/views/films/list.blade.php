@extends('layout')
@section('pageTitle')
    Filme
@endsection
@section('style')
@endsection

@section('content')
<h1>Filmansicht aller Filme</h1>
@include('films.listtable')
@endsection
