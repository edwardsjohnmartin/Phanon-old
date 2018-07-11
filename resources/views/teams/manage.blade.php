@extends('layouts.app')

@section('content')
    <div>
        @if(count($members) > 0)
            <h3>Logged In Team Members</h3>
            <table class="table">
                <tr>
                    <th>Member Name</th>
                    <th></th>
                </tr>
                @foreach($members as $member)
                    <tr>
                        <td>{{$member->name}}</td>
                        <td>
                            {!! Form::open(['action' => ['TeamsController@logout', $member->id], 'method' => 'POST']) !!}
                                {{Form::submit('Log Out', ['class' => 'btn btn-default'])}}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h3>There is no one else logged in currently</h3>
        @endif
    </div>

    <a href="{{url('/teams/login')}}" class="btn btn-primary">Log In Team Member</a>
@endsection
