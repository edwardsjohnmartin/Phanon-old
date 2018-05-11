@extends('layouts.app')

@section('content')
    <h1>Create Course</h1>
    {!! Form::open(['action' => 'CoursesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($modules) > 0)
            <div class="form-group">
                <label>Select which modules you want in the course</label>
                <select id="modules" name="modules[]" multiple class="form-control">
                    @foreach($modules as $module)
                        <option value="{{$module->id}}">{{$module->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No modules exist</p>
        @endif

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
            $(document).ready(function(){
                $('#modules').multiselect({
                    nonSelectedText: 'Select Module',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    buttonWidth: '400px'
                });
            });
        </script>
@endsection