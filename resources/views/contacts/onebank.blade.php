@if(isset($bank_data[$bank->id]['date']))
    {{$bank_data[$bank->id]['date']}}
@endif
@if(isset($bank_data[$bank->id]['statusText']))

    @if($bank_data[$bank->id]['statusText']['text']=='ПРОВЕРКА ВЫПОЛНЕНА. ДУБЛЕЙ НЕТ')
        <p style="color: green;">
            {{$bank_data[$bank->id]['statusText']['text']}}<br>
        </p>
    @else
    {{-- тут условие для банка --}}
        @if($bank_data[$bank->id]['status']=='fail')
            <div class="p-2" style="max-width: 400px" >

                <a class="btn btn-danger mb-2" data-toggle="collapse" href="#error{{$bank->id}}" role="button" aria-expanded="false" aria-controls="error{{$bank->id}}">
                    {{$bank_data[$bank->id]['status']}} API
                </a>
                <div  class="collapse" id="error{{$bank->id}}">
                    <div class="card card-body">
                       <pre>
                           {!! $bank_data[$bank->id]['message']  !!}
                       </pre>
                    </div>
                </div>
            </div>

        @else
            <p class="text-danger">
                {{$bank_data[$bank->id]['message']}}
            </p>
        @endif

    @endif

@endif

