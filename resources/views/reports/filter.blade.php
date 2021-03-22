<div class="row my-5">
    <div class="col-md-12">
        <h2 class="mb-3">СФОРМИРОВАТЬ ОТЧЁТ</h2>
    </div>
    <div class="col-md-12">
        <form class="form-inline" action="{{$filter_url}}" method="get">
            <div class="form-group mx-sm-3 mb-2">
                <label class="pr-2 font-weight-bold">Начало</label>
                <datepicker-wrap value="{{request()->get('date_start')}}" name="date_start"></datepicker-wrap>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label class="pr-2 font-weight-bold">Конец</label>
                <datepicker-wrap value="{{request()->get('date_end')}}" name="date_end"></datepicker-wrap>
            </div>
            @if(isset($operators))
                <div class="form-group mx-sm-3 mb-2">
                    <select name="user_id" class="form-control">
                        <option value="" disabled selected>Выбрать оператора</option>
                        @if($operators)
                            @foreach($operators as $operator)
                                <option
                                    @if((int)\Request()->get('user_id')==$operator->id)
                                    selected="selected"
                                    @endif
                                    value="{{$operator->id}}">
                                    {{$operator->fio}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif
            <div class="form-group mx-sm-3 mb-2">
                <label class="pr-2 font-weight-bold">ПРАВОВАЯ ФОРМА</label>
                <select name="type" class="form-control">
                    <option value="all">ЛЮБАЯ</option>
                    <option @if(request()->get('type')=='ip') selected @endif value="ip">ИП</option>
                    <option @if(request()->get('type')=='ooo') selected @endif value="ooo">ООО</option>
                </select>
            </div>
            @foreach($banks as $bank)
                @if(isset($bank_config_all[$bank->id]))
                    <div class="form-group mx-sm-3 mb-2">
                        <label class="pr-2 font-weight-bold">{{$bank->name}}</label>
                        <select name="bank_{{$bank->id}}" class="form-control">
                            <option selected disabled>Выбрать</option>
                            @foreach($bank_config_all[$bank->id]['statusText'] as $key=>$value)
                                @if($value['inReport'])
                                    <option @if(request()->get('bank_'.$bank->id)==$key) selected @endif
                                    value="{{$key}}">{{$value['text']}}</option>
                                @endif

                            @endforeach
                        </select>
                    </div>
                @endif
            @endforeach
            @if(Route::is('reports-filter'))
                <a class="btn btn-outline-info mr-4" href="/reports">Очистить</a>
            @endif
            <button type="submit" class="btn btn-primary mb-2">Сформировать отчет</button>
        </form>
    </div>
    @if(isset($count)&&$count>0)
        <div class="col-md-12">
            <h3 class="my-3">РЕЗУЛЬТАТ ОТЧЁТА: {{$count}}</h3>
        </div>
    @endif
    <div class="col-md-12">
        <hr class="new1">
    </div>
</div>
