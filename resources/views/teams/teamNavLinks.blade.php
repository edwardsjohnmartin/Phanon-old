@php
    if(session()->exists('members')){
        $members = session('members');
        $count = count($members);
    } else {
        $members = null;
        $count = 0;
    }
@endphp

<li class="dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
        Team @if(count($members) > 0)({{$count}} members logged in) @endif
        <span class="caret"></span>
    </a>

    <ul class="dropdown-menu">
        @if($count > 0)
            @foreach($members as $member)
                <li><a class="dropdown-item">{{$member->name}}</a></li>
            @endforeach
            <li role="separator" class="divider"></li>
        @endif
        <li><a class="dropdown-item" href="{{url('/teams/login')}}">Log In Team Member</a></li>
        <li><a class="dropdown-item" href="{{url('/teams/manage')}}">Manage Team</a></li>
    </ul>
</li>
