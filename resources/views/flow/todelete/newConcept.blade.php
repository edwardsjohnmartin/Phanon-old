<article class="sortableConcept">
    <h3>{{$concept->name}}</h3>
    
    @foreach($concept->unorderedModules as $module)
        @component('flow.newModule', ['module' => $module])
        @endcomponent
    @endforeach
</article>
