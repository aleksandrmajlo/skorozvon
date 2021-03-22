{{--<div class="d-none">--}}
{{--    {{$bank_data[$bank->id]['value']}}--}}
{{--</div>--}}
@if(isset($bank_data[$bank->id]['date']))
    {{$bank_data[$bank->id]['date']}}
@endif
{{--@if(isset($bank_data[$bank->id]['status']))--}}
{{--    <p class="text-danger">--}}
{{--        {{$bank_data[$bank->id]['status']}}--}}
{{--    </p>--}}
{{--@endif--}}
@if(isset($bank_data[$bank->id]['statusText']))
    <p class="text-danger">
        {{$bank_data[$bank->id]['statusText']['text']}}<br>
{{--        {{$bank_data[$bank->id]['statusText']['type']}}--}}
    </p>
@endif
{{--@if(isset($bank_data[$bank->id]['message']))--}}
{{--    <p class="text-danger">--}}
{{--        {{$bank_data[$bank->id]['message']}}--}}
{{--    </p>--}}
{{--@endif--}}
