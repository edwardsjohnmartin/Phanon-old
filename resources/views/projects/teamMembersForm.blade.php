 <h1>Available Students</h1>
{!! Form::open(['id' => 'assignRandomTeams', 'action' => 'TeamsController@assignRandomTeams', 'method' => 'POST']) !!}
    <ul class="toggleList">
        @foreach($students as $student)
        <li>
            <div class="toggleSwitchHolder">
            <label for="student_{{$student->id}}" >{{$student->name}}</label>
            <input id="student_{{$student->id}}" name="students[]" value="{{$student->id}}" type="checkbox" checked></div>
        </li>
        @endforeach
    </ul>
<input name="project_id" value="{{$project->id}}" type="hidden" )>
<input name="course_id" value="{{$course->id}}" type="hidden" )>
{{Form::hidden('version','modal')}}
{{Form::submit('Assign Teams', ['class' => 'btn btn-primary'])}}
{!! Form::close() !!}
<script>
    handleToggleSwitches(".toggleSwitchHolder input", "include", "exclude");
</script>