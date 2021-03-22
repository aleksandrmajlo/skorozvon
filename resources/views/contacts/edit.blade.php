@extends('layouts.admin')
@section('title', 'КАРТОЧКА КОНТАКТА')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">КАРТОЧКА КОНТАКТА</h1>

        <div class="row">
            <div class="col-md-12">
              @include('contacts.one')
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h2>Логирование событий</h2>
                <p ><span class="text-bold">{{$contact->created_at}} {{$contact->user->fio}}</span> добавил контакт</p>
                <contact-log id="{{$contact->id}}"></contact-log>
            </div>
        </div>

    </div>
@endsection
