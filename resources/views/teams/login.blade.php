@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Team Member Login</div>

                    <div class="panel-body">
                        @component('teams.loginform',['errors'=>$errors,'url' => '/teams/login'])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
