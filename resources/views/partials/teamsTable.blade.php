<div id="teamsTable">
    @if(count($teams) > 0)
        <table class="table">
            <tr>
                <th>Team Name</th>
                <th>Members</th>
            </tr>
            @foreach($teams as $team)
                <tr>
                    <td>{{$team->name}}</td>
                    <td>
                        @foreach($team->members as $member)
                            {{$member->name}}, 
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p>There are no teams to show</p>
    @endif
</div>
