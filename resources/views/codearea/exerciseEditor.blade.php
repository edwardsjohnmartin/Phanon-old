@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "exerciseIDE")

@php
    // Use the users latest attempt's code or the exercise's start_code if the user has never attempted it yet
    // TODO: Shouldn't this start with the starter code then?
    $latest_user_code = "";
    $has_solution = false;

if(!empty($exerciseProgress)){
        $latest_user_code = $exerciseProgress->latestContents();
        $has_solution = $exerciseProgress->completed();
    }
@endphp

@section('content')
    @component('codearea.exerciseNav', ['exercises' => $exercise->lesson->exercises(), 'current_exercise_id' => $exercise->id])
    @endcomponent

<div id="codeIde">
    <a class="flow" href="{{url(" flow",["id"> $exercise->lesson->module->concept->course_id])}}">Return</a>

    @component('codearea.prompt', ['prompt' => $exercise->prompt])
        @endcomponent

        @component('codearea.precode', ['pre_code' => $exercise->pre_code])
        @endcomponent

        @component('codearea.testcode', ['test_code' => $exercise->test_code])
        @endcomponent

        @component('codearea.codeEditor', ['start_code' => $exercise->start_code, 'item_type' => 'exercise',
                     'item_id' => $exercise->id, 'latest_user_code' => $latest_user_code,'has_solution' => $has_solution,
                    "previous_item_id"=> $previous_exercise_id,"next_item_id" => $next_exercise_id])
        @endcomponent
</div>
@endsection
