@section('scripts')
    @parent
    @component('scriptbundles.codemirror')
    @endcomponent

    @component('scriptbundles.skulpt')
    @endcomponent

    @component('scriptbundles.codeeditor')
    @endcomponent
@endsection

@php
    $messages = [];
    $reset_code = $start_code;

    // make sure needed variables exists to be passed down.
    if(!isset($has_solution)) $has_solution = false;
    if(!(isset($previous_item_id))) $previous_item_id = -1;
    if(!(isset($next_item_id))) $next_item_id = -1;
    if(isset($latest_user_code)){
        $start_code = $latest_user_code;
        if($has_solution)
            $messages[] = "user solution loaded";
        else
            $messages[] = "user code loaded";
    }
@endphp

@component('codearea.controls', ['item_type' => $item_type, 'item_id' => $item_id, 'reset_code' => $reset_code,'messages' => $messages,
        "previous_item_id"=> $previous_item_id, "next_item_id" => $next_item_id,'has_solution' => $has_solution])
@endcomponent

@component('codearea.mainEditor', ['start_code' => $start_code])
@endcomponent
