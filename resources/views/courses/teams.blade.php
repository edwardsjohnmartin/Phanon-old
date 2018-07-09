@extends('layouts.app')

@section('scripts')
    @component('scriptbundles.create-edit-form')
    @endcomponent

    @component('scriptbundles.bootstrap-multiselect')
    @endcomponent
@endsection

@section('content')
    <h1>{{$course->name}} Teams</h1>

    @can('team.create')
        {!! Form::open(['id' => 'createTeam', 'action' => 'TeamsController@createTeam', 'method' => 'POST']) !!}
            <div class="form-group">
                <label>Select which students to add to a team</label>
                <select id="students" name="students[]" multiple class="form-control">
                    @foreach($students as $student)
                        <option value="{{$student->id}}">{{$student->name}}</option>
                    @endforeach
                </select>
            </div>

            <input name="course_id" value="{{$course->id}}" type="hidden")>

            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}

        <script type="text/javascript">
            makeMultiSelect('students', 'Select Students');
        </script>
    @endcan

    @component('partials.teamsTable', ['teams' => $course->teams])
    @endcomponent
@endsection
