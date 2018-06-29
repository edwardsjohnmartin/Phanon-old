<div id="ideTestCode" class="hidden">
    <label for="test_code">Test Code</label>
    <textarea id="test_code" class="code">{{$test_code}}</textarea>
</div>

@section('scripts')
@parent
    @component('scriptbundles/python-tests')
    @endcomponent
@endsection