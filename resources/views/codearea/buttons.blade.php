@php
             if(!isset($previous_item_id)){
                 $previous_item_id = -1;
             }
             if(!isset($next_item_id)){
                 $next_item_id = -1;
             }
@endphp
<div id="ideButtons">
    @if($previous_item_id > 0)
    <button id="btnPrevious" title="Go to previous. (Ctrl+P)" type="button" class="btn btn-default previous"
        data-id="{{$previous_item_id}}" onclick="window.location='{{url("code/".$item_type."/".$previous_item_id)}}';"
        data-url="{{url("code/".$item_type."/".$previous_item_id)}}">
        Previous
    </button>
    @endif
    <button id="btnRunCode" title="Run and Save code. (Ctrl+Enter)" type="button" class="btn btn-default run currentStep"
        data-item-type="{{$item_type}}"
        data-item-id="{{$item_id}}"
        data-save-url="{{url('code/' . $item_type . '/save')}}">
        Run
    </button>

    <button id="btnReset" title="Discard changes and go back to starting code. (Ctrl+R)" type="button" class="btn btn-default reset"
        data-reset-code="{{$reset_code}}">
        Reset
    </button>
    @if($next_item_id > 0)
    <button id="btnNext" title="Go to next. (Ctrl+N)" type="button" class="btn btn-default next{{$has_solution? "":" disabled"}}"
        data-id="{{$next_item_id}}" onclick="window.location='{{url("code/".$item_type."/".$next_item_id)}}';"
        data-url="{{url("code/".$item_type."/".$next_item_id)}}">
        Next
    </button>
    @endif

</div>

@section("scripts-end")
@parent
<script>
    makeResetButton("btnReset");
</script>
@endsection
