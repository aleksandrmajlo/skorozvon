@extends('layouts.admin')
@section('title', 'Настройки')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">Настройки для операторов</h1>
        <setting-admin></setting-admin>
        <shipping-permission></shipping-permission>
    </div>
@endsection
