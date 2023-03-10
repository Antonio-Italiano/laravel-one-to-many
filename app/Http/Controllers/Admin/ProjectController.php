<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $projects = Project::orderBy('updated_at', 'DESC')->get();
        $projects = Project::orderBy('id')->get();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        return view('admin.projects.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|unique:projects|max:100',
            'description' => 'required|string',
            'imag' => 'nullable|image|mimes:jpeg,jpg,png',
            'url' => 'nullable|url',
        ], [
            'title.required' => 'The title is mandatory',
            'title.unique' => "The name $request->title is already present",
            'title.max' => 'Exceeded the maximum number of characters :max',
            'description.required' => 'Description is required',
            'image.mimes' => 'accepted extensions are :mimes',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($data['title'], '-');
        
        $project = new Project();

        if(array_key_exists('image', $data)){
            $img_url = Storage::put('projects', $data['image'] );
            $data['image'] = $img_url;
        }

        $data['is_published'] = Arr::exists($data, 'is_published');


        $project->fill($data);

        $project->save();
        
        return to_route('admin.projects.show', $project->id)
            ->with('type', 'success')
            ->with('message', "project $project->title created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // dd($project);

        $request->validate([
            'title' => ['required','string', Rule::unique('projects')->ignore($project->id) ,'max:100'],
            'description' => 'required|string',
            'imag' => 'nullable|image|mimes:jpeg,jpg,png',
            'url' => 'nullable|url',
        ], [
            'title.required' => 'The title is mandatory',
            'title.unique' => "The name $request->title is already present",
            'title.max' => 'Exceeded the maximum number of characters :max',
            'description.required' => 'Description is required',
            'image.mimes' => 'accepted extensions are :mimes',
        ]);
        
        $data = $request->all();

        $project->slug = Str::slug($data['title'], '-');

        if (array_key_exists('image', $data)) {
            //se c'è gia un url lo sostituisce con quello che mandiamo
            if ($project->image) {
                Storage::delete($project->image);
            }
            $img_url = Storage::put('projects', $data['image']);
            $data['image'] = $img_url;
        }

        $data['is_published'] = Arr::exists($data, 'is_published');

        $project->update($data);

        return to_route('admin.projects.show', $project->id)
            ->with('type', 'success')
            ->with('message', 'Change made successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if($project->image) Storage::delete($project->image);
        
        $project->delete();

        return to_route('admin.projects.index')
            ->with('message', "The project $project->title was deleted successfully")
            ->with('type', 'success');
    }

    /**
     * Change param of the toggle.
     */
    public function toggle(Project $project){
        $project->is_published = !$project->is_published;
     
        $action = $project->is_published ? 'pubblicato con successo' : 'salvato come bozza';
        $type = $project->is_published ? 'success' : 'info';
     
        $project->save();
        return redirect()->back()
           ->with('type', $type )
           ->with('message', "il progetto è stato $action");
    }
}
