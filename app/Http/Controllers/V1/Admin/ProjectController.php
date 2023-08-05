<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_size = $request->page_size ?? 20;

        $projects = Project::orderBy('id', 'desc')->get($page_size);

        return response()->json($projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Validator::make($request->all, ([
            'name' => ['required', 'unique:posts'],
            'keywords' => ['required'],
            'description' => ['required'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $project = new Project();
        $project->name = $request->input('name');
        $project->keywords = $request->input('keywords');
        $project->description = $request->input('description');
        $project->save();

        return response()->json([
            'message' => 'project Created Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        return response()->json($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        $data = Validator::make($request->all, ([
            'name' => ['required', 'unique:projects'],
            'keywords' => ['required'],
            'description' => ['required'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $project = new Project();
        $project->name = $request->input('name');
        $project->keywords = $request->input('keywords');
        $project->description = $request->input('description');
        $project->update();

        return response()->json([
            'message' => 'project Created Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        $project->delete();

        return response()->json([
            'message' => 'Category deleted Successfully'
        ]);
    }
}
