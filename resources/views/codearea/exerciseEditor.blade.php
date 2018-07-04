@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "exerciseIDE") 

@php
    // Use the users latest attempt's code or the exercise's start_code if the user has never attempted it yet
    $latest_user_code = "";

    if(!empty($exerciseProgress)){
        $latest_user_code = $exerciseProgress->latestContents();
    }
@endphp

@section('content')
    @component('codearea.exerciseNav', ['exercises' => $exercise->lesson->exercises(), 'current_exercise_id' => $exercise->id])
    @endcomponent

    <div id="codeIde">
        @component('codearea.prompt', ['prompt' => $exercise->prompt])
        @endcomponent

        @component('codearea.precode', ['pre_code' => $exercise->pre_code])
        @endcomponent

        @component('codearea.testcode', ['test_code' => $exercise->test_code])
        @endcomponent

        @component('codearea.codeEditor', ['start_code' => $exercise->start_code, 'latest_user_code' => $latest_user_code, 'item_type' => 'exercise', 'item_id' => $exercise->id])
        @endcomponent
    </div>
@endsection
