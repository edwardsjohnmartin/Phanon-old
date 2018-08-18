<div id="choices_div">
    @if(count($exercise->type->choicesAsArray()) > 0)
        @php
            $current = 0;
        @endphp
        @foreach($exercise->type->choicesAsArray() as $choice)
            <div class="show">
                <input type="radio" name="choice_selection" value="{{$current}}" @if($current == $exercise->type->solution) checked="true" @endif>
                <span>{{$exercise->type->choicesAsArray()[$current]}}</span>
                <input type="text" name="choices[]" class="hidden" value="{{$exercise->type->choicesAsArray()[$current]}}">
            </div>
            @php
                $current++;
            @endphp
        @endforeach
    @endif
</div>
<div>
    <button id="btnAddChoice" type="button" class="btn show hidden" onclick="addChoice();">Add Choice</button>
    
    <button>Submit Answer</button>
</div>

@section('scripts-end')
    @parent
    <script type="text/javascript">
        function addChoice() {
            var numChoices = document.getElementsByName("choice_selection").length;

            var newInput = document.createElement("input");
            newInput.type = "radio";
            newInput.name = "choice_selection";
            newInput.value = numChoices;
            if(numChoices == 0){
                newInput.checked = true;
            }

            var newSpan = document.createElement("span");
            newSpan.classList.add("hidden");

            var newTextInput = document.createElement("input");
            newTextInput.type = "text";
            newTextInput.name = "choices[]";
            newTextInput.placeholder = "New Option";

            var newDiv = document.createElement("div");
            newDiv.classList.add("show");
            newDiv.appendChild(newInput);
            newDiv.appendChild(newSpan);
            newDiv.appendChild(newTextInput);

            document.getElementById("choices_div").appendChild(newDiv);
        }
    </script>
@endsection
