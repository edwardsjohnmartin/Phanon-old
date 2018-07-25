<?php
$members = $team->members; // argh this is a method but not a method!!
$loggedMembers = [];
if(session()->has('loggedInMembers')){
    $loggedMembers = session('loggedInMembers');
}
//@endphp
?>
<div id="teamMembers" class="teams">
    <ul>
        @foreach($members as $member)
        @if(Auth::user()->id == $member->id)
        <li>You</li>
        @else
        <li>
            {{$member->name}}
            <a onclick="return loadLoginModal()" href="{{url(" teams/login")}}">Login</a>
        </li>
        @endif
        @endforeach
    </ul>
    <div id="loginModal"></div>
</div>
@section('scripts-end')
@parent
<script>
    function loadLoginModal() {
        $.ajax({
            url: "{{url('teams/loginform')}}"
            , data: {"url": '/teams/login?noredirect=true'}
            , success: function (resp) {
                $("#loginModal").html(resp);
                $("#loginModal form").on('submit',overriddenLogin);
            }, error: function () {
                alert("oops");
            }
        })

        return false;
    }

    function overriddenLogin(evt) {

        $.ajax({
            url: '/teams/login?noredirect=true'
            , type: "POST"
            , data: form.serialize()
            , success: function (resp) {
                alert(resp);
            }
            , error: function () {

            }
        });

        return false;
    }

</script>
@endsection