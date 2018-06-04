@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Edit Concept</h1>
    {!! Form::open(['id' => 'editConcept', 'action' => ['ConceptsController@update', $concept->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $concept->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($modules) > 0)
            <div class="form-group">
                <label>Select which modules you want in the concept</label>
                <select id="modules" name="modules[]" multiple class="form-control" onchange="updateList('sortableModules', 'modules')">
                    @foreach($modules as $module)
                        <option value="{{$module->id}}" @if(in_array($module->id, $module_ids)) selected @endif>{{$module->name}}</option>
                    @endforeach
                </select>
            </div>

            <div id="moduleDiv">
                <label>Drag and drop the modules to change the ordering they will appear in the concept</label>
                <ol id="sortableModules">
                    @foreach($concept->modules() as $module)
                        <li id="{{$module->id}}">{{$module->name}}</li>
                    @endforeach
                </ol>
            </div>
        @else
            <p>No modules exist</p>
        @endif

        {{FORM::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#modules').multiselect({
                nonSelectedText: 'Select Module',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>

    <script>
        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableModules").sortable({
            axis: "y",
            containment: "#moduleDiv",
            scroll: false
        });
        $("#sortableModules").disableSelection();

        addInputsToForm("editConcept", "sortableModules", "module_order");
    </script>
@endsection