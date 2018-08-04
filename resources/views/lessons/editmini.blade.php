    <h1>Edit Lesson</h1>
    {!! Form::open(['route' => array('lesson.updateSimple',$lesson->id)]) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $lesson->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        {{Form::hidden('_mode', 'simple')}}
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
