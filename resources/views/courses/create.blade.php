@extends('layouts.app')

@section('content')
    <h1>Create Course</h1>
    {!! Form::open(['action' => 'CoursesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        <div class="form-group">
            <label>Modules you have created</label>
            @if(count($unused_modules) == 0 and count($used_modules) == 0)
                <p>You have not created any modules</p>
            @else
                @if(count($unused_modules) > 0)
                    <div class="form-group">
                        <label>Select which modules you want in the course</label>
                        <select id="unused_modules" name="unused_modules[]" multiple class="form-control">
                            @foreach($unused_modules as $unused_module)
                                <option value="{{$unused_module->id}}">{{$unused_module->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <p>No modules exist</p>
                @endif

                @if(count($used_modules) > 0)
                    <div class="form-group">
                        <label>Modules used in other courses</label>
                        <table class="table">
                            <tr>
                                <th>Module</th>
                                <th>Course</th>
                                <th>Clone</th>
                            </tr>
                            <tr>
                            @foreach($used_modules as $used_module)
                                <td>{{$used_module->name}}</td>
                                <td>{{$used_module->course->name}}</td>
                                <td><a href="" class="btn btn-default">Clone Module</a></td>
                            @endforeach
                            </tr>
                        </table>
                    </div>
                 @endif
            @endif
        </div>

        <div class="form-group">
            <label>Modules other people have created</label>
            @if(count($other_modules) > 0)
                <table class="table">
                    <tr>
                        <th>Module</th>
                        <th>Author</th>
                        <th>Clone</th>
                    </tr>
                    @foreach($other_modules as $other_module)
                        <tr>
                            <td>{{$other_module->name}}</td>
                            <td>{{$other_module->user->name}}</td>
                            <td><a href="" class="btn btn-default">Clone Module</a></td>
                        </tr>
                    @endforeach
                </table>
            @else
                <p>There are no other modules</p>
            @endif
        </div>

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#unused_modules').multiselect({
                nonSelectedText: 'Select Module',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>
@endsection