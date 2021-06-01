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
        <p class="text-danger">
            {{$data_banks[$contact->id][$bank->id]['message']}}<br>
            {{-- {{$data_banks[$contact->id][$bank->id]['statusText']['text']}}<br> --}}
        </p>
    @endif

@endif


