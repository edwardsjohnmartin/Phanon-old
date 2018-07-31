<div id="ideMainEditor">
    @component('codearea.codeWindow', [
        'initial_editor_code' => $initial_editor_code,
        'role' => $role,
        'item' => $item
    ])
    @endcomponent
    
    @component('codearea.outputWindows')
    @endcomponent
</div>
