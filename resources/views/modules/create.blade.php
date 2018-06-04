@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Create Module</h1>
    {!! Form::open(['id' => 'createModule', 'action' => 'ModulesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', \Carbon\Carbon::now()->toDateString())}}
            {{Form::time('open_time', \Carbon\Carbon::now()->toTimeString())}}
        </div>

        @if(count($lessons) > 0)
            <div class="form-group">
                <label>Select which lessons you want in the module</label>
                <select id="lessons" name="lessons[]" multiple class="form-control" onchange="updateList('sortableLessonsAndProjects', 'lessons', 'projects')">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}">{{$lesson->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No lessons exist</p>
        @endif

        @if(count($projects) > 0)
            <div class="form-group">
                <label>Select which projects you want in the module</label>
                <select id="projects" name="projects[]" multiple class="form-control" onchange="updateList('sortableLessonsAndProjects', 'lessons', 'projects')">
                    @foreach($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No projects exist</p>
        @endif

        @if(count($lessons) > 0 or count($projects) > 0)
            <div id="lessonAndProjectDiv">
                <label>Drag and drop the lessons and projects to change the ordering they will appear in the module</label>
                <ol id="sortableLessonsAndProjects"></ol>
            </div>
        @endif

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#lessons').multiselect({
                nonSelectedText: 'Select Lesson',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });

        $(document).ready(function(){
            $('#projects').multiselect({
                nonSelectedText: 'Select Project',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>

    <script>
        // Add any selected items from the multiselect to the ordered list
        function updateList(olName, lessonSelectName, projectSelectName)
        {
            // Get the ordered list object
            var objectList = document.getElementById(olName);

            // Remove all options from the sortable list
            objectList.innerHTML = "";

            // Get all options from multiselect element
            var lessonOptions = document.getElementById(lessonSelectName).options;
            var projectOptions = document.getElementById(projectSelectName).options;

            var ids = [];
            var details = [];

            // If an option was selected, add the id and name/prompt of the object to arrays
            for(var i = 0; i < lessonOptions.length; i++){
                if(lessonOptions[i].selected){
                    ids.push("lesson " + lessonOptions[i].value);
                    details.push(lessonOptions[i].innerHTML);
                }
            }

            // If an option was selected, add the id and name/prompt of the object to arrays
            for(var i = 0; i < projectOptions.length; i++){
                if(projectOptions[i].selected){
                    ids.push("project " + projectOptions[i].value);
                    details.push(projectOptions[i].innerHTML);
                }
            }

            // Add all selected options from multiselect to the sortable list
            for(var i = 0; i < ids.length; i++){
                var li = document.createElement("li");

                li.appendChild(document.createTextNode(details[i]));
                li.setAttribute("id", ids[i]);

                objectList.appendChild(li);
            }
        }
    </script>

    <script>
        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableLessonsAndProjects").sortable({
            axis: "y",
            containment: "#lessonAndProjectDiv",
            scroll: false
        });
        $("#sortableLessonsAndProjects").disableSelection();

        addInputsToForm("createModule", "sortableLessonsAndProjects", "lesson_and_project_order");       
    </script>
@endsection