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
        <p class="text-danger">
            {{$bank_data[$bank->id]['message']}}
            {{-- {{$bank_data[$bank->id]['statusText']['text']}}<br> --}}
        </p>
    @endif

@endif

