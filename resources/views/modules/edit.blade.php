@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Edit Module</h1>
    {!! Form::open(['id' => 'editModule', 'action' => ['ModulesController@update', $module->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $module->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($module->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($module->open_date)))}}
        </div>

        @if(count($lessons) > 0)
            <div class="form-group">
                <label>Select which lessons you want in the module</label>
                <select id="lessons" name="lessons[]" multiple class="form-control" onchange="updateList('sortableLessonsAndProjects', 'lessons', 'projects')">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}" @if(in_array($lesson->id, $lesson_ids)) Selected @endif>{{$lesson->name}}</option>
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
                        <option value="{{$project->id}}" @if(in_array($project->id, $project_ids)) Selected @endif>{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No projects exist</p>
        @endif

        @if(count($lessons) > 0 or count($projects) > 0)
            <div id="lessonAndProjectDiv">
                <label>Drag and drop the lessons and projects to change the ordering they will appear in the module</label>
                <ol id="sortableLessonsAndProjects">
                    @foreach($module->lessonsAndProjects() as $item)
                        @if(is_a($item, 'App\Lesson'))
                            <li id="lesson {{$item->id}}">{{$item->name}}</li>
                        @else
                            <li id="project {{$item->id}}">{{$item->name}}</li>
                        @endif
                    @endforeach
                </ol>
            </div>
        @endif

        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        makeMultiSelect('lessons', 'Select Lessons');
        makeMultiSelect('projects', 'Select Projects');
        
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

        addInputsToForm("editModule", "sortableLessonsAndProjects", "lesson_and_project_order");       
    </script>
@endsection