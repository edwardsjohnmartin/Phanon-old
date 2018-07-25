@php
    use Illuminate\Support\Facades\Route;
    
    $appPath = Route::currentRouteName();
    $currController = explode('.', $appPath)[0];
    
    $paths = [
        "courses" =>"Courses|course",
        "concepts" =>"Concepts|concept",
        "modules" =>"Modules|module",
        "lessons" =>"Lessons|lesson",
        "exercises" =>"Exercises|exercise",
        "projects" =>"Projects|project"
    ];
@endphp

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{url('/')}}">
                {{config('app.name', 'Phanon')}}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li>@yield("navButtons")</li>
                <li>
                    <a class="sandbox" href="{{url('/sandbox')}}">Sandbox</a>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" role="button" aria-expanded="false">
                        Structure
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach($paths as $pathString)
                        @php
                        $pathParts = explode("|",$pathString);
                        $path = $pathParts[0];
                        $css = count($pathParts) > 1? $pathParts[1]: "";
                        @endphp
                        <li><a {{$css !="" ?"class=$css ":" "}} href="{{url('/' . strtolower($path))}}">{{$path}}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if(Auth::guest())
                    <li>
                        <a href="{{route('login')}}">Login</a>
                    </li>
                    <li>
                        <a href="{{route('register')}}">Register</a>
                    </li>
                @else
                    @component('teams.teamNavLinks')
                    @endcomponent

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{Auth::user()->name}} ({{Auth::user()->id}})
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @role('Admin')
                                <li>
                                    <a href="{{url('/users')}}">
                                        <i class="fa fa-btn fa-unlock"></i>Admin
                                    </a>
                                </li>
                            @endrole
                            <li>
                                <a href="{{url('/dashboard')}}">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{route('logout')}}"
                                    onclick="event.preventDefault(); 
                                            document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                                    {{csrf_field()}}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
