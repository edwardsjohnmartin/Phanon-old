@extends('layouts.app')

@section("navButtons")
    <a class="flow" href="{{url('flow/' . $course->id)}}">Course Flow</a>
@endsection

@section('scripts')
    @component('scriptbundles.create-edit-form')
    @endcomponent

    @component('scriptbundles.bootstrap-multiselect')
    @endcomponent
@endsection

@section('content')
    <h1>{{$course->name}} Teams Manage Page</h1>

    @can('team.create')
        <div>
            <table class="table">
                <tr>
                    <th>Concept</th>
                    <th>Module</th>
                    <th>Project</th>
                    <th>Teams Enabled</th>
                    <th>Actions</th>
                <tr>
                @foreach($projects as $project)
                    <tr>
                        <td>{{$project->module->concept->name}}</td>
                        <td>{{$project->module->name}}</td>
                        <td>{{$project->name}}</td>
                        <td>{{$project->teamsEnabled(true)}}</td>
                        <td><a class="btn" href="{{url('/projects/' . $project->id . '/teams')}}">View Project Teams</a></td>
                    </tr>
                @endforeach
            </table>
        </div>

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

    @component('teams.teamsTable', ['teams' => $course->teams])
    @endcomponent
@endsection
