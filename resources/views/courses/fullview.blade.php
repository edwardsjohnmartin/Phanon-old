@extends('layouts.app')

@section('content')
    <div>
        <p>This is the full view</p>
    </div>

    <div class="panel-group">
        <h2>{{$course->name}}</h2>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse1">Module 1</a>
                </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse">
                <ul class="list-group">
                    <li class="list-group-item">One</li>
                    <li class="list-group-item">Two</li>
                    <li class="list-group-item">Three</li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse2">Module 2</a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse">
                <ul class="list-group">
                    <li class="list-group-item">Four</li>
                    <li class="list-group-item">Five</li>
                    <li class="list-group-item">Six</li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse3">Module 3</a>
                </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse">
                <ul class="list-group">
                    <li class="list-group-item">Seven</li>
                    <li class="list-group-item">Eight</li>
                    <li class="list-group-item">Nine</li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse4">Module 4</a>
                </h4>
            </div>
            <div id="collapse4" class="panel-collapse collapse">
                <ul class="list-group">
                    <li class="list-group-item">Ten</li>
                    <li class="list-group-item">Eleven</li>
                    <li class="list-group-item">Twelve</li>
                </ul>
            </div>
        </div>
    </div>
@endsection