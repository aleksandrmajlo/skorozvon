@extends('layouts.admin')
@section('title', 'Контакты')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">Контакты</h1>
        {{--Загрузка--}}
        @include('contacts.upload')
        {{--Загрузка--}}
        @include('contacts.filter')
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">INN</th>
                            <th scope="col">PHONE</th>
                            <th scope="col" class="text-uppercase">organization</th>
{{--                            <th scope="col">DBL</th>--}}
                            @if($banks)
                                @foreach($banks as $bank)
                                    <th scope="col">{{$bank->shortname}}</th>
                                @endforeach
                            @endif
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>
                                    {{$contact->idbank}}</td>
                                <td>{{$contact->inn}}</td>
                                <td>{{$contact->phone}}</td>
                                <td>{{$contact->organization}}</td>
{{--                                <td></td>--}}
                                @if($banks)
                                    @foreach($banks as $bank)
                                        <td>
                                            @if(isset($data_banks[$contact->id])&&isset($data_banks[$contact->id][$bank->id]))
                                                @include('contacts.onebankloop')
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                                <td>
                                    <a href="/contacts/{{$contact->id}}/edit">
                                        <i class="fab fa-readme"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if(count($contacts)>0)
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        {{ $contacts->appends($_GET)->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
