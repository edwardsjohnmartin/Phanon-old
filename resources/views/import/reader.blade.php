@extends("layouts.app")

@section("content")
<dl>
    @foreach($lessons as $lesson)
    <dt>{{$lesson->name}}</dt>
    @foreach($lesson->tempExercises as $exercise)
    <dd>
        <dl>
            <dt>name</dt>
            <dd>{{$exercise->name}}</dd>
            <dt>prompt</dt>
            <dd>{{$exercise->prompt}}</dd>
            <dt>Pre Code</dt>
            <dd>
                <textarea>{{$exercise->pre_code}}</textarea>
            </dd>
            <dt>Starter Code</dt>
            <dd>
                <textarea>{{$exercise->starter_code}}</textarea>
            </dd>
            <dt>Test Code</dt>
            <dd>
                <textarea>{{$exercise->test_code}}</textarea>
            </dd>
        </dl>

    </dd>
    @endforeach
    @endforeach
</dl>
@endsection