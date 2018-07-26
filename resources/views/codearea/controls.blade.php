<div id="ideControls" class="row">
    @component('codearea.buttons', [
        'role' => $role,
        'item_type' => $item_type,
        'item' => $item,
        'previous_item_id' => $previous_item_id,
        'next_item_id' => $next_item_id,
        'is_completed' => $is_completed
    ])
    @endcomponent

    @component('codearea.messages')
        @if(isset($messages))
            @foreach($messages as $message_parts)
                @php
                    $parts = explode('|',$message_parts);
                    $message = $parts[0];
                    $css_class = count($parts) > 1 ? $parts[1] : "";
                @endphp
                <span class="popup {{$css_class}}">
                    {{$message}}
                </span>
            @endforeach
        @endif
    @endcomponent
</div>
