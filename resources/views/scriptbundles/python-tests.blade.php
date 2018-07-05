{{-- This must have the id given, or else the scripts will not run    --}}
<script type="text/x-python" id="pythonTestCode" >
    {{-- src="{{ asset('tests/python.py')}}" --}}
    @php
    include("tests/python.py") // file must be included to work
    @endphp
</script>
