<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_size = $request->page_size ?? 5;

        $tags = Tag::latest()->paginate($page_size);

        if($tags->isEmpty) {
            return response()->json([
                'message' => 'Tag Is Empty'
            ]);
        }

        return response()->json($tags);
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
        $data = Validator::make($request->all(), ([
            'name' => ['required', 'unique:tags,name'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'The Name already exist'
            ]);
        }

        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->save();

        return response()->json([
            'message' => 'tag Created Successfully'
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
        $tag = Tag::find($id);

        if(! $tag) {
            return response()->json([
                'message' => 'Tag Id Not Found'
            ]);
        }

        return response()->json($tag);
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
        $tag = Tag::find($id);

        if(! $tag) {
            return response()->json([
                'message' => 'Tag Id Not Found'
            ]);
        }

        $data = Validator::make($request->all(), ([
            'name' => ['required', 'unique:tags'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $tag->name = $request->input('name');
        $tag->update();

        return response()->json([
            'message' => 'tag Updated Successfully'
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
        $tag = Tag::find($id);

        if(! $tag) {
            return response()->json([
                'message' => 'Tag Id Not Found'
            ]);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted Successfully'
        ]);
    }
}
