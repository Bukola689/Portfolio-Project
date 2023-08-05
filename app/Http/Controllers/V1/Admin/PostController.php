<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{

    public function showAll(Request $request)
    {
        $categoryId = $request->query('categoryId');

        $posts = Post::with('categories')->whereHas('categories', function($query) use($categoryId) {
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
        })->get();

        if ($posts->isEmpty()) {
            throw new NotFoundHttpException('Category Not Found For Product');
        }

        return response()->json($posts);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $pageSize = $request->page_size ?? 5;

        $posts = Post::latest()->with(['category', 'tags'])->paginate($pageSize);

        if($posts->isEmpty()) {
            return response()->json([
                'message' => 'Post is empty'
            ]);
        }

        return response()->json($posts);

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
            'category_id' => ['required', 'int'],
            'title' => ['required', 'unique:posts,title'],
            'slug' => ['required', 'string'],
            'content' => ['required'],
            'keywords' => ['required'],
            'description' => ['required'],
            'thumbnail' => ['required'],
            'published' => ['required'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data and try again later '
            ]);
        }


        // $thumbnail = $request->thumbnail;

        // $originalName = $thumbnail->getClientOriginalName();

        // $thumbnail_new_name = 'thumbnail-' .time() .  '-' .$originalName;

        // Image::make($request->file($thumbnail))->resize(300,300)->save('products/thumbnail/' . $thumbnail_new_name);

        // $thumbnail->move('products/thumbnail', $thumbnail_new_name);

        // $thumbnail_new_name = hexdec(uniqid()).'-'.$thumbnail->getClientOriginalExtension();

        if( $request->hasFile('thumbnail')) {

            $title = Str::slug($request->title, '-');

            $data['thumbnail'] = $this->createThumbnail($request->file('thumbnail'), $title);

         }


        $post = new Post();
        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->slug = Str::slug($post['title'], "-");
        $post->content = $request->content;
        $post->keywords = $request->keywords;
        //$post->thumbnail = 'posts/thumbnail/' . $thumbnail_new_name;
        $post->description = $request->description;
        $post->published = $request->post['published'] ? true : false;
        $post->save();

        $post->tags()->sync($request->tag);

        return response()->json([
            'message' => 'post Created Successfully'
        ]);
    }

    public function createThumbnail($file, $title)
    {
        $fileName = "thumbnails/{'title'} . {$file->getClientOriginalExtension()}";

        Image::make($file)->resize(300, null, function ($constraint) {
            $constraint->aspectRation();
        })->save($fileName);

        return $fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'message' => 'Post Id Not Found'
            ]);
        }

        return response()->json($post);
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
        $post = Post::find($id);

        // $data = Validator::make($request->all, ([
        //     'title' => ['required', 'unique:posts'],
        //     'slug' => ['required', 'unique:posts'],
        //     'content' => ['required'],
        //     'keywords' => ['required'],
        //     'thumbnail' => ['required'],
        //     'description' => ['required'],
        //     'published' => ['required']
        // ]));

        // if($data->fails())
        // {
        //     return response()->json([
        //         'message' => 'check your data very well'
        //     ]);
        // }

        // if( $request->hasFile('thumbnail')) {

        //     $thumbnail = $request->thumbnail;

        //     $originalName = $thumbnail->getClientOriginalName();

        //     $thumbnail_new_name = 'thumbnail-' .time() .  '-' .$originalName;

        //     $thumbnail->move('thumbnails/thumbnail', $thumbnail_new_name);

        //     $post->thumbnail = 'thumbnails/thumbnail/' . $thumbnail_new_name;
        // }

        $data = $request->all();

        if( $request->hasFile('thumbnail')) {

            $fileName = "thumbnails/($data('title')). ($request->file('thumbnail)->getClientOriginalExtension())";

            $title = Str::slug($data['title'], '-');

            $data['thumbnail'] = $this->createThumbnail($request->file('thumbnail'), $title);

         }

         if($request->category_id) {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer'
            ]);
           
            if($validator->fails()) {
                return response()->json('category_id is empty or has been used', 400);
            };

            $post->category_id = $request->category_id;
        }

         if($request->title) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:3|max:15'
            ]);
           
            if($validator->fails()) {
                return response()->json('title is empty or has been used', 400);
            };

            $post->title = $request->title;
        }

        if($request->slug) {
            $validator = Validator::make($request->all(), [
                'slug' => 'required|string|min:3|max:15'
            ]);
           
            if($validator->fails()) {
                return response()->json('slug is empty or has been used', 400);
            };

            $post->slug = Str::slug($post['title'], "-");
        }

        if($request->content) {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:3|max:15'
            ]);
           
            if($validator->fails()) {
                return response()->json('content is empty or has been used', 400);
            };

            $post->content = $request->content;
        }

        if($request->keywords) {
            $validator = Validator::make($request->all(), [
                'keywords' => 'required|string|min:3|max:15'
            ]);
           
            if($validator->fails()) {
                return response()->json('keywords is empty or has been used', 400);
            };

            $post->keywords = $request->keywords;
        }

        if($request->description) {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|min:3|max:15'
            ]);
           
            if($validator->fails()) {
                return response()->json('description is empty or has been used', 400);
            };

            $post->description = $request->description;
        }

        if($request->published) {
            $validator = Validator::make($request->all(), [
                'published' => 'required|string|min:3|max:15'
            ]);
           
            if($validator->fails()) {
                return response()->json('published is empty or has been used', 400);
            };

            $post->published = $request->post['published'] ? true : false;
        }

        // $post->category_id = $request->category_id;
        // $post->title = $request->title;
        // $post->slug = Str::slug($post['title'], "-");
        // $post->content = $request->content;
        // $post->keywords = $request->keywords;
        // $post->description = $request->description;
        // $post->published = $request->post['published'] ? true : false;
        // $post->update();

        $post->tags()->attach($request->tag);

        return response()->json([
            'message' => 'post Updated Successfully'
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
        $post = Post::find($id);

        if(! $post) {
            return response()->json([
                'message' => 'Post Id Not Found'
            ]);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted Successfully'
        ]);
    }
}
