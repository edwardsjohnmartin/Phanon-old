@component('scriptbundles.codemirror')
@endcomponent

@component('scriptbundles.sculpt')
@endcomponent

@component('scriptbundles.codeeditor')
@endcomponent

@component('codearea2.controls', ['item_type' => $item_type, 'item_id' => $item_id])
@endcomponent

@component('codearea2.mainEditor', ['start_code' => $start_code])
@endcomponent