<div class="reportLoopItem">
    <span>{{$report->created_at}}</span>
    <span class="spacer pr-1 pl-1">/</span>
    <span>{{$report->user->fio}}</span>
    <span class="spacer pr-1 pl-1">/</span>
    <span>{{$report->contact->inn}}</span>
    <span class="spacer pr-1 pl-1">/</span>
    <span>{{$report->contact->organization}}</span>
    <span class="spacer pr-1 pl-1">/</span>
    <span>
      @if($report->status=='0')
            <a class="btn btn-outline-info show_report_erorr " href="#report_error_{{$report->id}}">Ошибка</a>
        @elseif(isset($bank_config_all[$report->bank_id]['statusText'][$report->status]))
            {{$bank_config_all[$report->bank_id]['statusText'][$report->status]['text']}}
        @endif
    </span>
    <span class="spacer pr-1 pl-1">/</span>
    <span>{{$report->bank->name}}</span>
</div>
@if($report->status=='0')
    <div id="report_error_{{$report->id}}" class="d-none">
                        <pre style="border: 2px solid">
                            {{$report->input}}
                        </pre>
    </div>
@endif

