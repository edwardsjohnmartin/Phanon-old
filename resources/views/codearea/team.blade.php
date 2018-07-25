<?php
$members = $team->members; // argh this is a method but not a method!!
$loggedMembers = [];
if(session()->has('members')){
    $loggedMembers = session('members');
}else{

}
foreach($members as $member){
    $member->isLoggedIn = false;
    foreach($loggedMembers as $logmember){
        if($logmember->id == $member->id){
            $member->isLoggedIn = true;
        }
    }
}

//@endphp
?>
<div id="teamMembers" class="teams">
    <meta name="_token" content="{{csrf_token()}}" />
    <ul>
        @foreach($members as $member)
            @if(Auth::user()->id == $member->id)
                <li class="activeMember" id="member_{{$member->id}}">You</li>
            @else
                <li id="member_{{$member->id}}" data-member-id="{{$member->id}}">
                {{$member->name}}
                @if($member->isLoggedIn)
                    <a class="logout"
                        onclick="return handleLogin(this,{{$member->id}})"
                        href="{{url(" teams/logout")}}/{{$member->id}}">Logout
                    </a>
                @else
                    <a class="login"
                        onclick="return handleLogin(this,{{$member->id}})"
                        href="{{url(" teams/login")}}">
                        Login
                    </a>
                @endif
            </li>
            @endif
        @endforeach
    </ul>
    <div id="loginModal"></div>
</div>
@section('scripts-end')
@parent
<script>
    function handleLogin(lnk,memId) {
        var own = $(lnk);
        if (own.hasClass("login")) {
            loadLoginModal(memId);
        } else if (own.hasClass("logout")) {
            logMemberOut(memId); 
        } else {
            alert("oopsy");
        }
        return false;
    }
    function toggleUserLogin(memId, hasLoggedIn) {
        var par = $("#member_" + memId);
        var lnk = par.find("a");

        var oldHref = lnk.attr("href");
        oldHref = oldHref.substr(0,
            oldHref.lastIndexOf("teams/log") + 9);

        if (hasLoggedIn) {
            // set link to Logout
            lnk.text("Logout");
            lnk.removeClass("login").addClass("logout");
            lnk[0].click = "return logMemberOut(" + memId + ")";
            lnk.attr("href", oldHref + "out/" + memId);
        } else {
                // set link to Login
            lnk.text("Login");
            lnk.removeClass("logout").addClass("login");
            lnk[0].click = "return loadLoginModal(" + memId + ")";
            lnk.attr("href", oldHref + "in");
        }
    }

    function logMemberOut(memId) {
        $.ajax({
            url: "{{url('/teams/logout')}}/" + memId + "?noredirect=true"
            , method: 'POST'
            , cache: false
            , data: { "_token": $("input[name=_token]").val() }
            , success: function (resp) {
                var hadSuccess = false;
                $.each(resp, function (i, mess) {
                    if (mess.type == "success") {
                        addPopup(mess.message, "success");
                    } else {
                        addPopup(mess.message, "error");
                    }
                });
                toggleUserLogin(memId, false);
            }
            , error: function () {
                alert("oopsy");
            }
        });

        return false;
    }

    function loadLoginModal(memId) {
        $.ajax({
            url: "{{url('teams/loginform')}}"
            , data: { "url": '/teams/login?noredirect=true' }
            , success: function (resp) {
                $("#loginModal").html(resp);
                $("#loginModal form").on('submit', overriddenLogin);
                $("#loginModal").attr("data-member-id", memId);
            }, error: function () {
                alert("oops");
            }
        })

        return false;
    }

    function overriddenLogin(evt) {
        evt.preventDefault(); // stop the form from officially submitting,
        // return false did not work below.
        var frm = $(this);

        $.ajax({
            url: "{{url('/teams/login')}}?noredirect=true"
            , method: 'POST'
            , cache: false
            , data: frm.serialize()
            //    {
            //    "_token": '"' + $("input[name=_token]").val() + '"'
            //    , "email": '"' + $("#email").val() + '"'
            //    , "password": '"' + $("#password").val()+ '"'
            //}
            , success: function (resp) {
                var hadSuccess = false;
                $.each(resp, function (i, mess) {
                    if (mess.type == "success") {
                        addPopup(mess.message, "success");
                    } else {
                        addPopup(mess.message, "error");
                    }
                });
                var memId = $("#loginModal").attr("data-member-id");
                toggleUserLogin(memId, true);
                $("#loginModal").empty();
            }
            , error: function () {
                alert("oopsy");
            }
        });

        return false;
    }

</script>
@endsection