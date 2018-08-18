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
        <h1>Create Exercise</h1>

        {!! Form::open(['id' => 'exerciseCreateForm', 'action' => 'ExercisesController@store', 'method' => 'POST']) !!}
            {{Form::select('type', $types, null, ['placeholder' => 'Pick the exercise type.', 'id' => 'exercise_type'])}}

            <div id="code_exercise_form" class="form-group">
                <label for="code_prompt">Prompt</label>
                <textarea id="code_prompt" name="code_prompt" class="form-control"></textarea>

                <div id="idePreCode">
                    <label for="pre_code">Pre Code</label>
                    <textarea id="pre_code" name="pre_code" class="form-control"></textarea>
                </div>

                <div id="ideStartCode">
                    <label for="start_code">Start Code</label>
                    <textarea id="start_code" name="start_code" class="form-control"></textarea>
                </div>

                <div id="ideTestCode">
                    <label for="test_code">Test Code</label>
                    <textarea id="test_code" name="test_code" class="form-control"></textarea>
                </div>

                <div id="ideCodeSolution">
                    <label for="code_solution">Solution</label>
                    <textarea id="code_solution" name="code_solution" class="form-control"></textarea>
                </div>
            </div>

            <div id="choice_exercise_form" class="form-group">
                <label for="choice_prompt">Prompt</label>
                <textarea id="choice_prompt" name="choice_prompt" class="form-control"></textarea>

                <div id="choices_div">
                    <label for="choices">Choices</label>
                </div>
                <button id="btnAddChoice" type="button" class="btn show" onclick="addChoice();">Add Choice</button>
            </div>

            <div id="scale_exercise_form" class="form-group">
                <label for="scale_prompt">Prompt</label>
                <textarea id="scale_prompt" name="scale_prompt" class="form-control"></textarea>

                <label for="num_options" class="form-group">Number of Options</label>
                <input type="number" id="num_options" name="num_options" class="form-control" onchange="makeLabelsInputs();"/>

                <div id="labels_div">
                    <label for="labels">Option Labels</label>
                </div>
            </div>
            {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
    </div>

    @section('scripts-end')
        @parent
        <script type="text/javascript">
            function addChoice() {
                var numChoices = document.getElementsByName("choice_solution").length;

                var newSpan = document.createElement("span");
                newSpan.innerText = numChoices + ". ";

                var newInput = document.createElement("input");
                newInput.type = "radio";
                newInput.name = "choice_solution";
                newInput.value = numChoices;
                if(numChoices == 0){
                    newInput.checked = true;
                }

                var newTextInput = document.createElement("input");
                newTextInput.type = "text";
                newTextInput.name = "choice_names[]";
                newTextInput.placeholder = "New Option";

                var newDiv = document.createElement("div");
                newDiv.appendChild(newSpan);
                newDiv.appendChild(newInput);
                newDiv.appendChild(newTextInput);

                document.getElementById("choices_div").appendChild(newDiv);
            }

            makeFormCodeMirror('pre_code');
            makeFormCodeMirror('start_code');
            makeFormCodeMirror('test_code');
            makeFormCodeMirror('code_solution');

            function showForm(type) {
                if(type == "code"){
                    $('#code_exercise_form').removeClass('hidden');
                    $('#choice_exercise_form').addClass('hidden');
                    $('#scale_exercise_form').addClass('hidden');
                } else if(type == "choice"){
                    $('#code_exercise_form').addClass('hidden');
                    $('#choice_exercise_form').removeClass('hidden');
                    $('#scale_exercise_form').addClass('hidden');
                } else if(type == "scale"){
                    $('#code_exercise_form').addClass('hidden');
                    $('#choice_exercise_form').addClass('hidden');
                    $('#scale_exercise_form').removeClass('hidden');
                }
            }

            function makeLabelsInputs() {
                var numOptions = $('#num_options').val();
                var currentOptionLabels = $('#labels_div').find('input');

                var optionLabelsNeeded = numOptions - currentOptionLabels.length;
                console.log("optionLabelsNeeded is: " + optionLabelsNeeded);

                if(optionLabelsNeeded > 0){
                   for(var i = 0; i < optionLabelsNeeded; i++){
                    makeNewLabelInput();
                    } 
                } else {
                    // Delete from end of option labels until only the correct number shows
                    for(var i = optionLabelsNeeded; i < 0; i++){
                        removeLastLabelInput();
                    }
                }
            }

            function makeNewLabelInput() {
                var newInput = document.createElement("input");
                newInput.type = "text";
                newInput.name = "labels[]";
                newInput.classList.add("show");
                newInput.placeholder = "Enter option label";

                document.getElementById("labels_div").appendChild(newInput);
            }

            function removeLastLabelInput() {
                $('#labels_div').children().last().remove();
            }

            $('#exercise_type').on('change', function() {
                showForm(this.options[this.selectedIndex].value);
            });

            $(document).ready(function () {
                $("#code_exercise_form").addClass("hidden");
                $("#choice_exercise_form").addClass("hidden");
                $("#scale_exercise_form").addClass("hidden");

                // Set height of pre code codeMirror
                var editor = $('#idePreCode').find('.CodeMirror')[0].CodeMirror;
                editor.setSize(null, 100);

                var editor = $('#ideStartCode').find('.CodeMirror')[0].CodeMirror;
                editor.setSize(null, 100);

                var editor = $('#ideTestCode').find('.CodeMirror')[0].CodeMirror;
                editor.setSize(null, 100);

                var editor = $('#ideCodeSolution').find('.CodeMirror')[0].CodeMirror;
                editor.setSize(null, 100);
            });
        </script>
    @endsection
@endsection
