@extends('layouts.app')

@section('content')
    <div>
        <h1>Partner Assignments</h1>
    </div>

    <div>
        <h3>Project: {{$project->name}}</h3>
    </div>

    <div>
        <label>Course</label>
        <p>{{$course->name}}</p>

        <label>Course Students</label>
        <ul>
            @foreach($students as $student)
                <li>{{$student->name}}</li>
            @endforeach
        </ul>

        <label>Course Teams</label>
        <table class="table">
            <tr>
                <th>Team Name</th>
                <th>Team Members</th>
                <th>Assigned To This Project</th>
            </tr>
            @foreach($course->teams as $team)
            <tr>
                <td>{{$team->name}}</td>
                <td>
                    @foreach($team->members as $member)
                        {{$member->name}}, 
                    @endforeach
                </td>
                <td>
                    <input type="checkbox" disabled @if(array_key_exists($project->id, $team->projects()->select('id', 'name')->get()->keyBy('id')->toArray())) checked @endif />
                </td>       
            </tr>
            @endforeach
            <tr>
                <td>
                    <button>Make New Team</button>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
        
    </script>
@endsection
