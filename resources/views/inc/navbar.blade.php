@php
             use Illuminate\Support\Facades\Route;
             //$path = Route::getFacadeRoute()->current()->uri();
             $path = Route::currentRouteName();
             //print_r($path);
             $paths = [
             "sandbox" => "SandBox",
             "courses" =>"Courses",
             "concepts" =>"Concepts",
             "modules" =>"Modules",
             "lessons" =>"Lessons",
             "exercises" =>"Exercises",
             "projects" =>"Projects"
             ];
             $currController = explode('.',$path)[0];
@endphp

<nav class="navbar navbar-inverse">
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
            <a class="navbar-brand" href="{{e(url('/'))}}">
                {{e(config('app.name', 'Phanon'))}}

            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @php
             $selectedPage = "";
             foreach($paths as $pth => $name){
                 $selectedPage = $currController == $pth ? "class='active'": "";
                 echo "
                <li $selectedPage>
                    <a href='".e(url("/$pth"))."'>$name</a>
                </li>";
             }
             @endphp
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links --><?php if(Auth::guest()): ?>
                <li>
                    <a href="<?php echo e(route('login')); ?>">Login</a>
                </li>
                <li>
                    <a href="<?php echo e(route('register')); ?>">Register</a>
                </li><?php else: ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <?php echo e(Auth::user()->name); ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if(auth()->check() && auth()->user()->hasRole('Admin')): ?>
                        <li>
                            <a href="<?php echo e(url('/users')); ?>">
                                <i class="fa fa-btn fa-unlock"></i>Admin
                            </a>
                        </li><?php endif; ?>
                        <li>
                            <a href="<?php echo e(url('/dashboard')); ?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('logout')); ?>"
                                onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo e(csrf_field()); ?>
                            </form>
                        </li>
                    </ul>
                </li><?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
