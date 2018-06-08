@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Create Concept</h1>
    {!! Form::open(['id' => 'createConcept', 'action' => 'ConceptsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($modules) > 0)
            <div class="form-group">
                <label>Select which modules you want in the concept</label>
                <select id="modules" name="modules[]" multiple class="form-control" onchange="updateList('sortableModules', 'modules')">
                    @foreach($modules as $module)
                        <option value="{{$module->id}}">{{$module->name}}</option>
                    @endforeach
                </select>
            </div>

            <div id="moduleDiv">
                <label>Drag and drop the modules to change the ordering they will appear in the concept</label>
                <ol id="sortableModules"></ol>
            </div>
        @else
            <p>No modules exist</p>
        @endif

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        makeMultiSelect('modules', 'Select Modules');

        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableModules").sortable({
            axis: "y",
            containment: "#moduleDiv",
            scroll: false
        });
        $("#sortableModules").disableSelection();

        addInputsToForm("createConcept", "sortableModules", "module_order");
    </script>
@endsection