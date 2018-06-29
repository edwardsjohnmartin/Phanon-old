<div id="ideTestCode" class="hidden">
    <label>Test Code</label>
    <textarea id="test_code" class="code">{{$test_code}}</textarea>
</div>

@section('scripts')
    @component('scriptbundles/python-tests')
    @endcomponent
@endsection