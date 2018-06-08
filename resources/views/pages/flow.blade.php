@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
    @endcomponent
@endsection

@section('content')
<div class="container">
    <div class="row">
        <section class="col-md-8 col-md-offset-2">
            <h1>Course Flow</h1>
            <h2>{{$course->name}}</h2>
            <aside class="dates">
                <!--TODO: these dates should come preformatted-->
                <!--Not sure why we are parsing them then reformatting them again.-->
                <span class="start">{{$course->getOpenDate('m/d/Y')}}</span>
                <span> - </span>
                <span class="end">{{$course->getCloseDate('m/d/Y')}}</span>
            </aside>
            <aside class="actions">
                <a href="{{url('/courses/' . $course->id)}}" class="btn btn-view">View</a>
                @can('Edit course')
                <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-edit">Edit</a>
                @endcan
                                @can('Delete course')
                <a href="{{url('/courses/' . $course->id . '/delete')}}"
                    onclick="return actionVerify(event,'{{'delete '.$course->name}}');" class="btn btn-delete">
                    Delete
                </a>
                @endcan
            </aside>

            @foreach($course->concepts() as $concept)
            <article>
                <h3>{{$concept->id}}</h3>
                @foreach($concept->modules() as $module)
                @component('modules.flow',['module' => $module])
                @endcomponent
                @endforeach
            </article>
            @endforeach
        </section>
    </div>
</div>
@endsection