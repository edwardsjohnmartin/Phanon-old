<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Concept;
use App\Module;
use DB;

class ConceptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $concepts = Concept::paginate(10);

        return view('concepts.index')->
            with('concepts', $concepts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = Module::all();

        return view('concepts.create')->
            with('modules', $modules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:concepts',          
        ]);

        // Get the list of module_ids the user wants to include in the concept
        $module_ids = $request->input('modules');

        // Get the order of modules within the course as an int array
        $module_order = $request->input('module_order');

        // Create concept
        $concept = new Concept();
        $concept->name = $request->input('name');
        $concept->user_id = auth()->user()->id;

        // Save concept to the database
        $concept->save();

        // Attach the modules to the concept
        if(!empty($module_ids)) {
            $modules = Module::find($module_ids);

            // Remove the modules to go into the concept from any concepts they are currently in
            foreach($modules as $module){
                if($module->concept->id != $concept->id){
                    $module->concept->removeModule($module->id);
                    $module->concept_id = $concept->id;
                    $module->save();
                }
            }

            // Write the previous_module_id field for every module in the course
            for($i = 0; $i < count($module_order); $i++){
                $module = Module::find($module_order[$i]);
                if($i == 0){
                    $module->previous_module_id = null;
                } else {
                    $module->previous_module_id = $module_order[$i-1];
                }
                $module->save();
            }
        }

        return redirect(url('/concepts'))->
            with('success', 'Concept Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $concept = Concept::find($id);

        return view('concepts.show')->
            with('concept', $concept);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concept = Concept::find($id);
        $modules = Module::all();

        // Check for correct user
        if($concept->user_id != auth()->user()->id){
            return redirect(url('/concepts'))->
                with('error', 'Unauthorized Page');
        }

        // Create an array that contains the ids of the modules within the concept
        $module_ids = array();
        foreach($concept->modules() as $module) {
            array_push($module_ids, $module->id);
        }

        return view('concepts.edit')->
            with('concept', $concept)->
            with('modules', $modules)->
            with('module_ids', $module_ids);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',          
        ]);

        // Get the list of module_ids the user wants to include in the concept
        $module_ids = $request->input('modules');

        // Get the order of modules within the course as an int array
        $module_order = $request->input('module_order');

        // Get the concept to be updated
        $concept = Concept::find($id);

        // Update its fields
        $concept->name = $request->input('name');

        // Save the concept to the database
        $concept->save();

        // Set the concept_id and previous_module_id field of all the old modules within the course to null
        foreach($concept->modules() as $module){
            $module->concept_id = null;
            $module->previous_module_id = null;
            $module->save();
        }

        // Attach the modules to the concept
        if(!empty($module_ids)) {
            $modules = Module::find($module_ids);

            // Remove the module from the concept it is currently in
            foreach($modules as $module){
                if(!empty($module->concept)){
                    $module->concept->removeModule($module->id);
                }
            }
            
            $concept->unorderedModules()->saveMany($modules);

            // Write the previous_module_id field for every module in the concept
            for($i = 0; $i < count($module_order); $i++){
                $module = Module::find($module_order[$i]);
                if($i == 0){
                    $module->previous_module_id = null;
                } else {
                    $module->previous_module_id = $module_order[$i-1];
                }
                $module->save();
            }
        }
        
        return redirect(url('/concepts'))->
            with('success', 'Concept Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $concept = Concept::find($id);

        // Check that the concept belongs to the logged-in user
        if($concept->user_id != auth()->user()->id){
            return redirect('/concepts')->
                with('error', 'Unauthorized Page');
        }

        // Delete the concept from the database
        $concept->delete();

        return redirect('/concepts')->
            with('success', 'Concept Deleted');
    }
}
