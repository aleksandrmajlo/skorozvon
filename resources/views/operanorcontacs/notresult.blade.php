@extends('layouts.operator')
@section('title', 'Контакты')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">Ничего не найдено</h1>
        <div class="row">
            <div class="col-md-12">
                <p class="text-danger">
                    По Вашему запросу <strong>{{ app('request')->input('q') }}</strong> ничего не найдено.
                </p>
            </div>
        </div>
    </div>
@endsection


