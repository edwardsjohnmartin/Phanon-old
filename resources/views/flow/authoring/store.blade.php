@extends('layouts.app')

@section('content')
    <div>
        <pre>
            {{print_r($all, true)}}
        </pre>
    </div>
@endsection
