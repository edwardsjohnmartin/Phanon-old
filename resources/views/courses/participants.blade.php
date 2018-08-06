@extends('layouts.app')

@section("navButtons")
    <a class="flow" href="{{url('flow/' . $course->id)}}">Course Flow</a>
@endsection

@section('content')
    <div class="container">
        <h1>{{$course->name}} Participants</h1>

        <div >
            <label  for="roleSelect">Select which role to add users as</label>
            <select id="roleSelect">
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="userSearchbox">Search for users to add to the course</label>
            <input type="text" id="userSearchbox" onkeyup="myFunction()" placeholder="Enter users name">
            
            <ul id="userList" class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item">{{$user->name}}
                    @if($course->users->contains($user))
                        <span>{{$course->getUsersRole($user->id)->name}}</span>
                        <button onclick="removeUserFromCourse(this, {{$user->id}})" class="pull-right">Remove</button>
                    @else
                        <span></span>
                        <button onclick="addUserToCourse(this, {{$user->id}})" class="pull-right">Add</button>
                    @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @section('scripts')
        <script>
            function myFunction() {
                var input, filter, ul, li, i, name;

                input = document.getElementById("userSearchbox");
                filter = input.value.toUpperCase();
                ul = document.getElementById("userList");
                li = ul.getElementsByTagName("li");

                for (i = 0; i < li.length; i++) {
                    name = li[i].innerText;
                    if (name.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }

            function addUserToCourse(btn, user_id) {
                var role_id = document.getElementById("roleSelect").value;
                var url = "{{url('/courses/' . $course->id . '/adduser')}}" + "/" + user_id;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        role_id: role_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        console.log(data);
                        updateListItem(btn, user_id);
                    }
                });
            }

            function removeUserFromCourse(btn, user_id) {
                var url = "{{url('/courses/' . $course->id . '/removeuser')}}" + "/" + user_id;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        console.log(data);
                        updateListItem(btn, user_id);
                    }
                });
            }

            function updateListItem(btn, user_id) {
                if(btn.innerText == "Add"){
                    btn.innerText = "Remove";
                    btn.onclick = function() { removeUserFromCourse(btn, user_id) };
                    var roleSelect = document.getElementById("roleSelect");
                    btn.parentElement.getElementsByTagName("span")[0].innerText = roleSelect.options[roleSelect.selectedIndex].text;
                } else if(btn.innerText == "Remove"){
                    btn.innerText = "Add";
                    btn.onclick = function() { addUserToCourse(btn, user_id) };
                    btn.parentElement.getElementsByTagName("span")[0].innerText = "";
                }
            }
        </script>
    @endsection
@endsection
