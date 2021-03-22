@extends('layouts.operator')
@section('title', 'КАРТОЧКА КОНТАКТА')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">КАРТОЧКА КОНТАКТА</h1>
        <div class="row">
            <div class="col-md-12">
                @include('contacts.one')
            </div>
        </div>
    </div>
@endsection
