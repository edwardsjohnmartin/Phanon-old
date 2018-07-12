@extends('layouts.app')

@section('scripts')
    @component('scriptbundles.edit-course')
    @endcomponent
@endsection

@section('content')
    <div class="col-md-8 col-md-offset-2">
        <h1>Create Course</h1>
    
        <section id="courseFlow">
            {!! Form::open(['id' => 'flow_course_create_form', 'action' => 'FlowController@store', 'method' => 'POST']) !!}
                <div id="course_div">
                    <div id="course_fields" class="form-group">
                        {{Form::label('name', 'Course Name')}}
                        {{Form::text('course[name]', '', ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required'])}}

                        {{Form::label('open_date', 'Open Date')}}
                        {{Form::date('course[open_date]', \Carbon\Carbon::now(), ['class' => 'form-control', 'required' => 'required'])}}

                        {{Form::label('close_date', 'Close Date')}}
                        {{Form::date('course[close_date]', \Carbon\Carbon::now(), ['class' => 'form-control', 'required' => 'required'])}}
                    </div>

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
