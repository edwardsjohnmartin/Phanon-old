@php
    if(!isset($previous_item_id)){
        $previous_item_id = -1;
    }
             
    if(!isset($next_item_id)){
        $next_item_id = -1;
    }

@endphp

<div id="ideButtons">
    @if($item_type != 'sandbox' && $item_type != 'project')
    <button id="btnPrevious" title="Go to previous. (Ctrl+P)" type="button"
            class="btn btn-default previous{{$previous_item_id > 0? "":" disabled"}}"
            onclick="location.href = '{{url("code/" . $item_type . "/" . $previous_item_id)}}'">
        Previous
    </button>
    @else
    <span class="buttonSpacer"> - </span>
    @endif
    @if($item_type != 'sandbox')
    <button id="btnSave" type="button" title="Save code. (Ctrl+S)" class="btn btn-default save"
            data-item-type="{{$item_type}}"
            data-item-id="{{$item->id}}"
            data-save-url="{{url('code/' . $item_type . '/save')}}">
        Save
    </button>
    @endif

    <button id="btnRunCode" title="Run and Save code. (Ctrl+Enter)" type="button" class="btn btn-default run currentStep"
            data-item-type="{{$item_type}}"
            @if($item_type !='sandbox' )
            data-item-id="{{$item->id}}"
            data-save-url="{{url('code/' . $item_type . '/save')}}">
        @else
        data-item-id=null
        data-save-url=null>
        @endif
        Run
    </button>


    <button id="btnReset" title="Discard changes and go back to starting code. (Ctrl+R)" type="button" class="btn btn-default reset"
            @if($item_type !='sandbox' )
            data-reset-code="{{$item->start_code}}">
        @else
        >
        @endif

        Reset
    </button>

    @if($item_type != 'sandbox' && $item_type != 'project')
    <button id="btnNext" title="Go to next. (Ctrl+N)" type="button"
            class="btn btn-default next{{$is_completed && $next_item_id > 0? "":" disabled"}}"
            data-id="{{$next_item_id}}" onclick="window.location='{{url('code/'.$item_type.'/'.$next_item_id)}}';"
            data-url="{{url("code/".$item_type."/".$next_item_id)}}">
        Next
    </button>
    @endif
    <span class="buttonSpacer"> - </span>

    @if($item_type != "sandbox"){{--&& $role->hasPermissionTo(Permissions::PROJECT_EDIT)--}}
    @php
    if($item_type == "project"){
    $url = url('/ajax/projectedit');
    } elseif($item_type == "exercise"){
    $url = url('/ajax/exerciseedit');
    } else {
    $url = "";
    }
    @endphp

    <button class="btn edit modifying" title="Turn Editing Mode On" onclick="toggleEditMode(this, '{{$item_type}}', '{{$url}}');">Enable Edit Mode</button>
    @if($item_type == "exercise")
    <button class="btn copy modifying" title="Copy this item."
            onclick="copyItem(this, '{{$item_type}}', '{{$url}}', {{$item->id}});">
        Copy
    </button>
    <button class="btn insert modifying" title="Insert new after this item."
            onclick="insertItem(this, '{{$item_type}}', '{{$url}}', {{$item->id}});">
        Insert
    </button>
    @endif
    @endif
</div>

@section("scripts-end")
    @parent
    <script>
        makeResetButton("btnReset");
        makeSaveButton("btnSave");
    </script>
@endsection
