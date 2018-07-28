@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "exerciseIDE")

@php
    $initial_editor_code = $exercise->start_code;

    if(!empty($exerciseProgress)){
        if(!is_null($exerciseProgress->latestContents())){
            $initial_editor_code = $exerciseProgress->latestContents();
        }
    }
@endphp

@section('content')
    @component('codearea.exerciseNav', ['exercises' => $exercise->lesson->exercises(), 'current_exercise_id' => $exercise->id])
    @endcomponent

    <div id="codeIde">
        @section("navButtons")
        <a class="flow" href="{{url('flow/' . $exercise->lesson->module->concept->course_id)}}">Course Flow</a>
        @endsection

        <div class="hidden">
            <p id="exerciseId">{{$exercise->id}}</p>
        </div>
        
        @component('codearea.prompt', ['prompt' => $exercise->prompt, 'show_survey' => false, 'team' => null])
        @endcomponent

        @component('codearea.precode', ['pre_code' => $exercise->pre_code])
        @endcomponent

        @component('codearea.testcode', ['test_code' => $exercise->test_code])
        @endcomponent

        @component('codearea.codeEditor',
        [
            'role' => $role,
            'item' => $exercise,
            'item_type' => 'exercise',
            'initial_editor_code' => $initial_editor_code
        ])
        @endcomponent
    </div>
@endsection
