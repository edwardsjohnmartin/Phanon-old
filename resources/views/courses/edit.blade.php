@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Edit Course</h1>
    {!! Form::open(['id' => 'editCourse', 'action' => ['CoursesController@update', $course->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $course->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($course->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($course->open_date)))}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', new \Carbon\Carbon($course->close_date))}}
            {{Form::time('close_time', date("H:i:s", strtotime($course->close_date)))}}
        </div>

        @if(count($concepts) > 0)
            <div class="form-group">
                <label>Select which concepts you want in the course</label>
                <select id="concepts" name="concepts[]" multiple class="form-control" onchange="updateList('sortableConcepts', 'concepts')">
                    @foreach($concepts as $concept)
                        <option value="{{$concept->id}}" @if(in_array($concept->id, $concept_ids)) selected @endif>{{$concept->name}}</option>
                    @endforeach
                </select>
            </div>

            <div id="conceptDiv">
                <label>Drag and drop the concepts to change the ordering they will appear in the course</label>
                <ol id="sortableConcepts">
                    @foreach($course->concepts() as $concept)
                        <li id="{{$concept->id}}">{{$concept->name}}</li>
                    @endforeach
                </ol>
            </div>
        @else
            <p>No concepts exist</p>
        @endif

        @if(!empty($users))
            <div class="form-group">
                <label>Select which users to add to the course</label>
                <select id="users" name="users[]" multiple class="form-control" onchange="updateTable('roleSelection', 'users')">
                    @foreach($users as $user)
                        <option value="{{$user->id}}"
                            @if(array_key_exists($user->id, $course->users()->get()->keyBy('id')->toArray())) 
                                selected 
                            @endif
                            >{{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Choose which role the user will participate as</label>
                <table class="table" id="roleSelection">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        @else
            <p>No students exist to put into the course</p>
        @endif

        {{FORM::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        makeMultiSelect('concepts', 'Select Concepts');
        makeMultiSelect('users', 'Select Users');

        // TODO: This shouldn't be here but it is required to make the role dropdown work.
        var rolesArray = @php echo json_encode($roles); @endphp;

        updateTable('roleSelection', 'users');

        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableConcepts").sortable({
            axis: "y",
            containment: "#conceptDiv",
            scroll: false
        });
        $("#sortableConcepts").disableSelection();

        addInputsToForm("editCourse", "sortableConcepts", "concept_order");
        addUserRoleInputToForm("editCourse");
    </script>
@endsection