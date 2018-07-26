@php
    if(!isset($previous_item_id)){
        $previous_item_id = -1;
    }
             
    if(!isset($next_item_id)){
        $next_item_id = -1;
    }
@endphp

<div id="ideButtons">
    @if($item_type != 'sandbox')
            <button id="btnPrevious" title="Go to previous. (Ctrl+P)" type="button" 
               class="btn btn-default previous{{$previous_item_id > 0? "":" disabled"}}"
                onclick="location.href = '{{url("code/" . $item_type . "/" . $previous_item_id)}}'"
            </button>
    @endif

    <button id="btnRunCode" title="Run and Save code. (Ctrl+Enter)" type="button" class="btn btn-default run currentStep"
        data-item-type="{{$item_type}}"
        @if($item_type != 'sandbox')
            data-item-id="{{$item->id}}"
            data-save-url="{{url('code/' . $item_type . '/save')}}">
        @else
            data-item-id=null
            data-save-url=null>
        @endif
        Run
    </button>

    @if($item_type != 'sandbox')
        <button id="btnReset" title="Discard changes and go back to starting code. (Ctrl+R)" type="button" class="btn btn-default reset"
            data-reset-code="{{$item->start_code}}">
            Reset
        </button>
    @endif

    @if($item_type != 'sandbox')
            <button id="btnNext" title="Go to next. (Ctrl+N)" type="button" 
                class="btn btn-default next{{$is_completed && $next_item_id > 0? "":" disabled"}}"
                data-id="{{$next_item_id}}" onclick="window.location='{{url('code/'.$item_type.'/'.$next_item_id)}}';"
                data-url="{{url("code/".$item_type."/".$next_item_id)}}">
                Next
            </button>
    @endif

    @if($item_type != "sandbox" and $role->hasPermissionTo(Permissions::PROJECT_EDIT))
        @php
            if($item_type == "project"){
                $url = url('/ajax/projectedit');
            } elseif($item_type == "exercise"){
                $url = url('/ajax/exerciseedit');
            } else {
                $url = "";
            }
        @endphp

        <button class="btn" style="width: auto" onclick="toggleEditMode(this, '{{$item_type}}', '{{$url}}');">Enable Edit Mode</button> 
    @endif
</div>

@section("scripts-end")
    @parent
    @if($item_type != 'sandbox')
        <script>
            makeResetButton("btnReset");
        </script>
    @endif
@endsection
