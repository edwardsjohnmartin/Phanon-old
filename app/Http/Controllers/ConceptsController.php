<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Concept;
use App\Module;
use DB;

class ConceptsController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance']);
    }

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

        // Create concept
        $concept = new Concept();
        $concept->name = $request->input('name');
        $concept->user_id = auth()->user()->id;

        // Save concept to the database
        $concept->save();

        // Get the order of modules within the course as an array
        $module_order = $request->input('module_order');

        // Variable used to keep track of the last module added to the lesson
        $previous_module_id = null;

        if(!empty($module_order)){
            // Attach the modules to the lesson
            foreach($module_order as $id){
                // Get the module
                $module = Module::find($id);

                // Remove the modules to go into the concept from any concepts they are currently in
                if($module->concept != null){
                    $module->concept->removeModule($module);
                }

                // Add the module to this concept and set its previous_module_id field
                $module->concept_id = $concept->id;
                $module->previous_module_id = $previous_module_id;

                // Update the previous_module_id variable
                $previous_module_id = $module->id;

                // Save the module to the database
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
        $concept_modules = $concept->modules();

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

        // Get the concept to be updated
        $concept = Concept::find($id);

        // Update its fields
        $concept->name = $request->input('name');

        // Save the concept to the database
        $concept->save();

        // Remove the concept_id and previous_module_id field from all modules that were in this concept
        foreach($concept->modules() as $module){
            $module->concept_id = null;
            $module->previous_module_id = null;
            $module->save();
        }

        // Get the order of modules within the course as an int array
        $module_order = $request->input('module_order');

        // Variable used to keep track of the last module added to the lesson
        $previous_module_id = null;

        if(!empty($module_order)){
            // Attach the modules to the lesson
            foreach($module_order as $id){
                // Get the module
                $module = Module::find($id);

                // Remove the modules to go into the concept from any concepts they are currently in
                if($module->concept != null and $module->concept->id != $concept->id){
                    $module->concept->removeModule($module);
                }

                // Add the module to this concept and set its previous_module_id field
                $module->concept_id = $concept->id;
                $module->previous_module_id = $previous_module_id;

                // Update the previous_module_id variable
                $previous_module_id = $module->id;

                // Save the module to the database
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
