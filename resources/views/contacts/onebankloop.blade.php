<div class="d-none">
    {{$data_banks[$contact->id][$bank->id]['value']}}
</div>

@if(isset($data_banks[$contact->id][$bank->id]['date']))
    {{$data_banks[$contact->id][$bank->id]['date']}}
@endif
@if(isset($data_banks[$contact->id][$bank->id]['statusText']))
    @if($data_banks[$contact->id][$bank->id]['statusText']['text']=='ПРОВЕРКА ВЫПОЛНЕНА. ДУБЛЕЙ НЕТ')
        <p style="color: green;">
            {{$data_banks[$contact->id][$bank->id]['statusText']['text']}}<br>
        </p>
    @else
        @if($data_banks[$contact->id][$bank->id]['status']=='fail')

            <div class="p-2" style="max-width: 300px" >
                <a class="btn btn-danger mb-2 text-nowrap "  data-toggle="collapse" href="#error{{$contact->id}}{{$bank->id}}" role="button" aria-expanded="false" aria-controls="error{{$contact->id}}{{$bank->id}}">
                    {{$data_banks[$contact->id][$bank->id]['status']}} API
                </a>
                <div  class="collapse" id="error{{$contact->id}}{{$bank->id}}">
                    <div class="card card-body">
                    <pre>
                           {!! $data_banks[$contact->id][$bank->id]['message'] !!}
                    </pre>
                    </div>
                </div>
            </div>
        @else
            <p class="text-danger">
                {{$data_banks[$contact->id][$bank->id]['message']}}<br>
            </p>
        @endif
    @endif

@endif


