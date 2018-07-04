<div id="ideButtons">
    <button id="btnRunCode" type="button" class="btn btn-default run" 
        data-item-type="{{$item_type}}" 
        data-item-id="{{$item_id}}"
        data-save-url="{{url('code/' . $item_type . '/save')}}"
    >Save and Run</button>

    <button id="btnReset" type="button" class="btn btn-default"
        data-reset-code="{{$reset_code}}"
    >Reset</button>
</div>

<script>
    makeResetButton("btnReset");
</script>
