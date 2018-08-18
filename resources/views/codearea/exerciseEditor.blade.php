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

@section('scripts')
    @parent
    @component('scriptbundles.codeeditor')
    @endcomponent
@endsection
@section('content')
    @component('codearea.exerciseNav', [
        'role' => $role,
        'exercises' => $exercise->lesson->exercises(),
        'current_exercise_id' => $exercise->id,
        'lesson_id' => $exercise->lesson->id
    ])
    @endcomponent

    <div id="codeIde">
        @section("navButtons")
            <a class="flow" href="{{url('flow/' . $exercise->lesson->module->concept->course_id)}}">Course Flow</a>
        @endsection

        <div class="hidden">
            <p id="exerciseId">{{$exercise->id}}</p>
        </div>
        
        @component('codearea.prompt', [
            'prompt' => $exercise->type->prompt,
            'show_survey' => false,
            'team' => null,
            'item_type' => 'exercise'
        ])
        @endcomponent

        @if($exercise->getType() == "code")
            @component('codearea.precode', ['pre_code' => $exercise->type->pre_code])
            @endcomponent

            @component('codearea.testcode', ['test_code' => $exercise->type->test_code])
            @endcomponent

            @component('codearea.codeEditor', [
                'role' => $role,
                'item' => $exercise,
                'item_type' => 'exercise',
                'initial_editor_code' => $initial_editor_code
            ])
            @endcomponent
        @elseif($exercise->getType() == "choice")
            <button class="btn edit modifying" tooltip="Turn Editing Mode On" onclick="toggleEditMode(this, 'choice_exercise', '{{url('/ajax/choiceexerciseedit')}}');">Enable Edit Mode</button>

            @component('codearea.choices', ['exercise' => $exercise])
            @endcomponent
        @elseif($exercise->getType() == "scale")
            @component('codearea.scales', ['exercise' => $exercise])
            @endcomponent
        @endif
    </div>
@endsection
