<div class="row my-5">
    <div class="col-md-12">
        <h2 class="mb-3">Фильтр контактов</h2>
    </div>
    <div class="col-md-12">
        <form class="form-inline" method="get">

            <div class="form-group mx-sm-3 mb-2">
                <label class="pr-2 font-weight-bold">Начало</label>
                <datepicker-wrap value="{{request()->get('start')}}" name="start"></datepicker-wrap>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label class="pr-2 font-weight-bold">Конец</label>
                <datepicker-wrap value="{{request()->get('end')}}" name="end"></datepicker-wrap>
            </div>

            <div class="form-group mx-sm-3 mb-2">
                <label class="pr-2 font-weight-bold">ПРАВОВАЯ ФОРМА</label>
                <select name="type" class="form-control">
                    <option value="all">ЛЮБАЯ</option>
                    <option @if(request()->get('type')=='ip') selected @endif value="ip">ИП</option>
                    <option @if(request()->get('type')=='ooo') selected @endif value="ooo">ООО</option>
                </select>
            </div>

            @if($banks)
                @foreach($banks as $bank)
                    @if(isset($bank_config_all[$bank->id]))
                        <div class="form-group mx-sm-3 mb-2">
                            <label class="pr-2 font-weight-bold">{{$bank->name}}</label>
                            <select name="bank_{{$bank->id}}" class="form-control">
                                <option selected disabled>Выбрать</option>
                                @foreach($bank_config_all[$bank->id]['statusText'] as $key=>$value)
                                    <option
                                        @if(request()->get('bank_'.$bank->id)==$key) selected @endif
                                    value="{{$key}}">{{$value['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endforeach
            @endif
            <button type="submit" class="btn btn-primary mb-2">Применить</button>
        </form>
    </div>
    @if(isset($count)&&$count>0)
        <div class="col-md-12">
            <h3 class="my-3">РЕЗУЛЬТАТ ПАРАМЕТРОВ ФИЛЬТРА: {{$count}}</h3>
        </div>
        @if(Auth::user()->ContactDownload ==1)
            <div class="col-md-12">
                @php
                  $url='';
                  foreach (request()->all() as $k=>$v){
                     if($v){
                         $url.=$k.'='.$v.'&';
                     }
                  }
                @endphp
               <a class="btn btn-primary" href="/contact-export?{{$url}}">Скачать</a>
            </div>
        @endif
    @endif
    <div class="col-md-12">
        <hr class="new1">
    </div>
</div>
