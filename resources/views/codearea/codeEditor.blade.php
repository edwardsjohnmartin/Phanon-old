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
    if($item_type == 'exercise'){
        $next_item_id = $item->nextExercise()->id;
        $previous_item_id = $item->previous_exercise_id;
        $is_completed = $item->isCompleted();
    } else {
        $next_item_id = -1;
        $previous_item_id = -1;
        $is_completed = false;
    }
    
    $messages = [];

    if($is_completed){
        array_push($messages, "Your solution was loaded.|load");
    } else if(!is_null($item) and $initial_editor_code != $item->start_code){
        array_push($messages, "Your latest code was loaded.|load");
    }
@endphp

@component('codearea.controls', [
    'item_type' => $item_type,
    'item' => $item,
    'messages' => $messages,
    'previous_item_id' => $previous_item_id,
    'next_item_id' => $next_item_id,
    'is_completed' => $is_completed
])
@endcomponent

@component('codearea.mainEditor', ['initial_editor_code' => $initial_editor_code])
@endcomponent
