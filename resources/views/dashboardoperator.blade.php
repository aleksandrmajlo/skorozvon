@extends('layouts.operator')
@section('title', 'Dashboard')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">МОЯ СТАТИСТИКА</div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Отправлено</th>
                                    <th scope="col">Открыто</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td>День</td>
                                    <td>{{$reportDateAll}}</td>
                                    <td>{{$reportDateAllOpen}}</td>
                                </tr>

                                <tr>
                                    <td>Неделя</td>
                                    <td>{{$reportWeekAll}}</td>
                                    <td>{{$reportWeekAllOpen}}</td>
                                </tr>

                                <tr>
                                    <td>Месяц</td>
                                    <td>{{$reportMonthAll}}</td>
                                    <td>{{$reportMonthAllOpen}}</td>
                                </tr>

                                <tr>
                                    <td>Всего</td>
                                    <td>{{$reportAll}}</td>
                                    <td>{{$reportAllOpen}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
