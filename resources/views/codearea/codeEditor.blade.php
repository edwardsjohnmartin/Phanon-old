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
    if(isset($latest_user_code)){
        $start_code = $latest_user_code;
        $messages[] = "user code loaded";
    }
@endphp

@component('codearea.controls', ['item_type' => $item_type, 'item_id' => $item_id, 'reset_code' => $reset_code,'messages' => $messages])
@endcomponent

@component('codearea.mainEditor', ['start_code' => $start_code])
@endcomponent
