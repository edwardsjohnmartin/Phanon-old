@php
    if(!isset($team_id)) $team_id = 0;
@endphp
<form class="form-horizontal" method="POST" action="{{url($url)}}">
    {{csrf_field()}}
    @if($team_id > 0)
    <input type="hidden" name="teamid" value="{{$team_id}}" />
    @endif
    <div class="form-group{{$errors->has('email') ? ' has-error' : ''}}">
        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="{{old('email')}}" required autofocus />

            @if($errors->has('email'))
            <span class="help-block">
                <strong>{{$errors->first('email')}}</strong>
            </span>
            @endif
        </div>
    </div>

    <div class="form-group{{$errors->has('password') ? ' has-error' : ''}}">
        <label for="password" class="col-md-4 control-label">Password</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control" name="password" required />

            @if($errors->has('password'))
            <span class="help-block">
                <strong>{{$errors->first('password')}}</strong>
            </span>
            @endif
        </div>
    </div>
    {{-- Not sure why you need the team member login needs to remmeber a team member.
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" {{old('remember')? 'checked' : ''}} /> Remember Me
                </label>
            </div>
        </div>
    </div>
     --}}
    <div class="form-group">
        <div class="col-md-8 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Login
            </button>
    {{-- Not sure why you need the team member login needs to allow forget password links.

            <a class="btn btn-link" href="{{route('password.request')}}">
                Forgot Your Password?
            </a>
            --}}
        </div>
    </div>
</form>