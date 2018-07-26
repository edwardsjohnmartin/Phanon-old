@php
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

@endphp
<div id="teamMembers" class="teams">
    <meta name="_token" content="{{csrf_token()}}" />
    <ul>
        @foreach($members as $member)
            @if(Auth::user()->id == $member->id)
        <li class="member activeMember" id="member_{{$member->id}}">You</li>
        @else
        <li class="member{{$member->isLoggedIn?" activeMember":""}}" id="member_{{$member->id}}" data-member-id="{{$member->id}}">
            {{$member->name}}
                @if($member->isLoggedIn)
            <a class="logout"
                onclick="return handleLogin(this,{{$member->id}})"
                href="{{url(" teams/logout")}}/{{$member->id}}" >Logout
            </a>
            @else
            <a class="login"
                onclick="return handleLogin(this,{{$member->id}})"
                href="{{url(" teams/login")}}" >Login
            </a>
            @endif
        </li>
        @endif
        @endforeach
    </ul>
    <div id="loginModal">
        <div class="messageControls">
            <a href="#" onclick="return hideLoginForm();" class="closer">X</a>
        </div>
        <div id="modalContent"></div>
    </div>
</div>
@section('scripts-end')
@parent
<script>
    
/**
 * handle the team login links on Project IDE
 * @param {any} lnk owning link that is clicked
 * @param {any} memId team member id for verification.
 * @returns false to cancel navigation behavior
 */
function handleLogin(lnk, memId) {
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

/**
 * Toggle whether the Login Popup shows for team login.
 * @param {any} memId team member id to get correct member from list
 * @param {any} hasLoggedIn true if the member has logged in and we need to allow logout
 * @returns false to cancel navigation behavior
 */
function toggleUserLogin(memId, hasLoggedIn) {
    var par = $("#member_" + memId);
    var lnk = par.find("a");

    var oldHref = lnk.attr("href");
    oldHref = oldHref.substr(0,
        oldHref.lastIndexOf("teams/log") + 9);

    if (hasLoggedIn) {
        // set link to Logout
        par.addClass("activeMember");
        lnk.text("Logout");
        lnk.removeClass("login").addClass("logout");
        lnk[0].click = "return logMemberOut(" + memId + ")";
        lnk.attr("href", oldHref + "out/" + memId);
    } else {
        // set link to Login
        par.removeClass("activeMember");
        lnk.text("Login");
        lnk.removeClass("logout").addClass("login");
        lnk[0].click = "return loadLoginModal(" + memId + ")";
        lnk.attr("href", oldHref + "in");
    }
}

/**
 * logs a member out from a team
 * Can only log out a member who is not the primary member
 * @param {any} memId team member id of who needs logged out.
 * @returns false to cancel navigation behavior
 */
function logMemberOut(memId) {
    $.ajax({
        url: "{{url('/teams/logout')}}/" + memId + "?noredirect=true"
        , method: 'POST'
        , cache: false
        , data: { "_token": $("input[name=_token]").val() }
        , success: function (mess) {
            if (mess.type == "success") {
                addPopup(mess.message, "success");
                var realId = mess.userid;
                toggleUserLogin(realId, false);
            } else {
                addPopup(mess.message, "error");
            }
        }
        , error: function () {
            alert("oopsy");
        }
    });

    return false;
}

/**
 * Behavior to show the team member login form.
 */
function showLoginForm() {
    $("#loginModal").show();

}
/**
 * Behavior to hide the team member login form.
 */
function hideLoginForm() {
    $("#loginModal").hide(400);
}

/**
 * Request the login form view and display it in the modal area.
 * @param {any} memId team member id that needs to log in.
 * @returns false to cancel navigation behavior
 */
function loadLoginModal(memId) {
    $.ajax({
        url: "{{url('teams/loginform')}}"
        , data: {
            "url": "{{url('teams/loginform')}}?noredirect=true}}",
                "teamid": {{ $team-> id }}
        }
        , success: function (resp) {
            $("#modalContent").html(resp);
            $("#modalContent form").on('submit', overriddenLogin);
            $("#loginModal").attr("data-member-id", memId);
            showLoginForm();
        }
        , error: function () {
             alert("oops");
        }
    });

return false;
    }


/**
 * Overrides the login form behavior to allow AJAX login
 *  -- This will keep the need from reloading the page to log in.
 * @param evt document event for form submission
 */
function overriddenLogin(evt) {
    evt.preventDefault(); // stop the form from officially submitting,
    // return false did not work below.
    var frm = $(this);

    $.ajax({
        url: "{{url('/teams/login')}}?noredirect=true"
        , method: 'POST'
        , cache: false
        , data: frm.serialize()
        , success: function (mess) {
            if (mess.type == "success") {
                addPopup(mess.message, "success");
                // update visuals
                var memId = $("#loginModal").attr("data-member-id");
                var retId = mess.userid;
                toggleUserLogin(retId, true);
                // clear form
                $("#modalContent").empty();
                hideLoginForm();
            } else {
                addPopup(mess.message, "error");
            }
        }
        , error: function () {
            alert("oopsy");
        }
    });

    return false;
}

</script>
@endsection
