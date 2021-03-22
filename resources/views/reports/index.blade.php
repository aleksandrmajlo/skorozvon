@extends('layouts.admin')
@section('title', 'Отчеты')
@section('content')
    <div class="container">
        <h1 class="text-center mb-3">Отчеты</h1>
        @include('reports.filter')
        @if(count($reports)>0)
            <div class="row">
                <div class="col-md-12 mb-3">
                    @foreach($reports as $report)
                        @include('reports.item')
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        {{ $reports->appends($_GET)->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
