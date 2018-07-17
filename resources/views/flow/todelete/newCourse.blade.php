@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
    @endcomponent

    @component('scriptbundles/percentages')
    @endcomponent

    @component('scriptbundles/course-flow')
    @endcomponent
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <section id="courseFlow" class="col-md-8 col-md-offset-2">
                <h1>{{$course->name}}</h1>
                
                <aside class="dates">
                    <span class="start">{{$course->getOpenDate('m/d/Y')}}</span>
                    <span> - </span>
                    <span class="end">{{$course->getCloseDate('m/d/Y')}}</span>
                </aside>

                <aside class="actions">
                    <a href="{{url('/courses/' . $course->id)}}" class="btn btn-view">View</a>
                    @can(Permissions::COURSE_EDIT)
                        <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-edit">Edit</a>
                    @endcan
                    @can(Permissions::COURSE_DELETE)
                        <a href="{{url('/courses/' . $course->id . '/delete')}}"
                            onclick="return actionVerify(event,'{{'delete '.$course->name}}');" class="btn btn-delete">
                            Delete
                        </a>
                    @endcan
                </aside>

                @foreach($course->unorderedConcepts as $concept)
                    @component('flow.newConcept', ['concept' => $concept])
                    @endcomponent
                @endforeach
            </section>
        </div>
    </div>
@endsection
