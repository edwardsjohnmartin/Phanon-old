    <h1>Edit Lesson</h1>
    {!! Form::open(['route' => array('lesson.modify',$lesson->id)]) !!}
        {{Form::hidden('lesson_id',$lesson->id)}}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $lesson->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
