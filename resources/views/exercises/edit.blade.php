@extends('layouts.app')

@section('content')
    @section('scripts')
        @parent
        @component('scriptbundles.codemirror')
        @endcomponent

        @component('scriptbundles.skulpt')
        @endcomponent

        @component('scriptbundles.codeeditor')
        @endcomponent
    @endsection
    
    <div class="container">
        <h1>Edit Exercise</h1>

        {!! Form::open(['id' => 'exerciseEditForm', 'action' => 'ExercisesController@store', 'method' => 'POST']) !!}
            @if($exercise->getType() == "code")
                <div id="code_exercise_form" class="form-group">
                    <label for="code_prompt">Prompt</label>
                    <textarea id="code_prompt" name="code_prompt" class="form-control">{{$exercise->type->prompt}}</textarea>

                    <div id="idePreCode">
                        <label for="pre_code">Pre Code</label>
                        <textarea id="pre_code" name="pre_code" class="form-control">{{$exercise->type->pre_code}}</textarea>
                    </div>

                    <div id="ideStartCode">
                        <label for="start_code">Start Code</label>
                        <textarea id="start_code" name="start_code" class="form-control">{{$exercise->type->start_code}}</textarea>
                    </div>

                    <div id="ideTestCode">
                        <label for="test_code">Test Code</label>
                        <textarea id="test_code" name="test_code" class="form-control">{{$exercise->type->test_code}}</textarea>
                    </div>

                    <div id="ideCodeSolution">
                        <label for="code_solution">Solution</label>
                        <textarea id="code_solution" name="code_solution" class="form-control">{{$exercise->type->solution}}</textarea>
                    </div>
                </div>

                <script type="text/javascript">
                    makeFormCodeMirror('pre_code');
                    makeFormCodeMirror('start_code');
                    makeFormCodeMirror('test_code');
                    makeFormCodeMirror('code_solution');
                </script>
            @elseif($exercise->getType() == "choice")
                <div id="choice_exercise_form" class="form-group">
                    <label for="choice_prompt">Prompt</label>
                    <textarea id="choice_prompt" name="choice_prompt" class="form-control">{{$exercise->type->prompt}}</textarea>

                    <label for="choices">Choices</label>
                    <textarea id="choices" name="choices" class="form-control">{{$exercise->type->choices}}</textarea>

                    <label for="choice_solution">Solution</label>
                    <textarea id="choice_solution" name="choice_solution" class="form-control">{{$exercise->type->solution}}</textarea>
                </div>
            @endif

            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
    </div>
@endsection
