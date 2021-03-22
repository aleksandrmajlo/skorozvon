<div class="d-none">
    {{$data_banks[$contact->id][$bank->id]['value']}}
</div>
@if(isset($data_banks[$contact->id][$bank->id]['date']))
    {{$data_banks[$contact->id][$bank->id]['date']}}
@endif
{{--@if(isset($data_banks[$contact->id][$bank->id]['status']))--}}
{{--    <p class="text-danger">--}}
{{--        {{$data_banks[$contact->id][$bank->id]['status']}}--}}
{{--    </p>--}}
{{--@endif--}}
@if(isset($data_banks[$contact->id][$bank->id]['statusText']))
    <p class="text-danger">
        {{$data_banks[$contact->id][$bank->id]['statusText']['text']}}<br>
{{--        {{$data_banks[$contact->id][$bank->id]['statusText']['type']}}--}}
    </p>
@endif
{{--@if(isset($data_banks[$contact->id][$bank->id]['message']))--}}
{{--    <p class="text-danger">--}}
{{--        {{$data_banks[$contact->id][$bank->id]['message']}}--}}
{{--    </p>--}}
{{--@endif--}}
