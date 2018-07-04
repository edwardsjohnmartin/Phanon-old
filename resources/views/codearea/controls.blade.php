<div id="ideControls" class="row">
    @component('codearea.buttons', ['item_type' => $item_type, 'item_id' => $item_id, 'reset_code' => $reset_code])
    @endcomponent

    @component('codearea.messages')
    @endcomponent
</div>
