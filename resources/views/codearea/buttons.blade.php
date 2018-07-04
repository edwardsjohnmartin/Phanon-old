<div id="ideButtons">
    <button id="btnPrevious" title="Go to previous." type="button" class="btn btn-default previous"
        data-reset-code="{{$reset_code}}"
    >Previous</button>

    <button id="btnRunCode" title="Run and Save code." type="button" class="btn btn-default run" 
        data-item-type="{{$item_type}}" 
        data-item-id="{{$item_id}}"
        data-save-url="{{url('code/' . $item_type . '/save')}}"
    >Run</button>

    <button id="btnReset" title="Discard changes and go back to starting code." type="button" class="btn btn-default reset"
        data-reset-code="{{$reset_code}}"
    >Reset</button>

    <button id="btnNext" title="Go to next"type="button" class="btn btn-default next disabled"
        data-reset-code="{{$reset_code}}"
    >Next</button>
</div>

@section("scripts-end")
@parent
<script>
    makeResetButton("btnReset");
</script>
@endsection
