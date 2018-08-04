    <h1>Edit Project</h1>
    {!! Form::open(['action' => ['ProjectsController@update', $project->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $project->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('teams_enabled', 'Teams Enabled')}}
            {{Form::checkbox('teams_enabled', 'yes', $project->teamsEnabled())}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($project->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($project->open_date)))}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', new \Carbon\Carbon($project->close_date))}}
            {{Form::time('close_time', date("H:i:s", strtotime($project->close_date)))}}
        </div>
        {{FORM::hidden('_method', 'PUT')}}
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
