@extends('layouts.app')

@section('scripts')
    @component('scriptbundles.edit-course')
    @endcomponent
@endsection

@section('content')
    <div class="col-md-8 col-md-offset-2">
        <h1 ondblclick="toggleEditable(this);">New Course Name</h1>

        <aside class="dates">
            <span class="start" ondblclick="toggleEditable(this);">{{date(config('app.dateformat_short'))}}</span>
            <span> - </span>
            <span class="end" ondblclick="toggleEditable(this);">{{date(config('app.dateformat_short'))}}</span>
        </aside>

        <aside class="actions">
            <a class="btn btn-view" disabled>View</a>
            @can(Permissions::COURSE_EDIT)
                <a class="btn btn-edit" disabled>Edit</a>
            @endcan
            @can(Permissions::COURSE_DELETE)
                <a class="btn btn-delete" disabled>Delete</a>
            @endcan
        </aside>

        <section id="courseFlow">
            {!! Form::open(['id' => 'flow_course_create_form', 'action' => 'FlowController@store', 'method' => 'POST']) !!}
                <div id="course_div">
                    <div id="course_contents">
                    </div>
                    
                    <div id="course_buttons">
                        <button type="button" id="add_concept_button" onclick="addConcept()">Add Concept</button> 
                    </div>

                    {{Form::submit('Create', ['class' => 'btn btn-primary'])}}
                </div>
            {!! Form::close() !!}
        </section>
    </div>
@endsection
