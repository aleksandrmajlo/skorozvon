@extends('layouts.admin')
@section('title', 'Операторы')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">Операторы</h1>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if(count($operators))
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Оператор</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Email</th>
                                <th scope="col">Дата / Время</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($operators as $operator)
                                <tr>
                                    <td>{{$operator->fio}}</td>
                                    <td>
                                        @if($operator->status==1)
                                            Активный
                                            @else
                                            Заблокирован
                                        @endif
                                    </td>
                                    <td>
                                        {{$operator->email}}
                                    </td>
                                    <td>
                                        {{$operator->lastLoginAt()}}
                                    </td>
                                    <td>
                                        <a href="{{route('operators.edit',$operator->id)}}">управлять</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-auto">
                    <a class="btn btn-primary" href="{{route('operators.create')}}">Добавить оператора</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        {{ $operators->links() }}
                    </div>
                </div>
            </div>
        @else
            <p class="text-primary">Нету операторов</p>
            <a class="btn btn-primary" href="{{route('operators.create')}}">Добавить оператора</a>
        @endif
    </div>
@endsection
