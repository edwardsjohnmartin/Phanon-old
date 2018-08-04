@php
    $teams = $project->teams;
@endphp 
<h1>Teams for {{$project->name}}</h1>
    @if(count($teams) > 0)
        <ul class="teamsList">
            @foreach($teams as $team)
                <li>
                    <span class="name">{{$team->name}}</span>
                    <ul class="members">
                        @foreach($team->members as $member)
                            <li class="member">{{$member->name}}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    @else
        <p>There are no teams to show</p>
        <button class="teams" onclick="displayTeamsForm({{$project->id}})"
                    tooltip="Show teams for this project">Assign Random Teams</button>
    @endif

