<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_size = $request->page_size ?? 5;

        $categories = Category::latest()->paginate($page_size);

        if($categories->isEmpty) {
            return response()->json([
                'message' => 'Categories Is Empty'
            ]);
        }

        return response()->json($categories);
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
            'name' => ['required', 'unique:categories'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return response()->json([
            'message' => 'Category Created Successfully'
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
        $category = Category::find($id);

        if(! $category) {
            return response()->json([
                'message' => 'Category Id Not Found'
            ]);
        }

        return response()->json($category);
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
        $category = Category::find($id);

        if(! $category) {
            return response()->json([
                'message' => 'Category Id Not Found'
            ]);
        }

        $data = Validator::make($request->all(), ([
            'name' => ['required', 'unique:categories'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $category->name = $request->input('name');
        $category->update();

        return response()->json([
            'message' => 'Category Updated Successfully'
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
        $category = Category::find($id);

        if(! $category) {
            return response()->json([
                'message' => 'Category Id Not Found'
            ]);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted Successfully'
        ]);
    }
}
