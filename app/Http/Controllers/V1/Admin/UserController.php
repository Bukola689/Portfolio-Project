<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page_size = $request->page_size ?? 5;

        $users = User::latest()->paginate($page_size);

        if($users->isEmpty()) {
            return response()->json([
                'message' => 'User Is Empty'
            ]);
        }

        return response()->json($users);
    }

    public function store(Request $request)
    {

        $data = Validator::make($request->all(), ([
            'name' => ['required', 'unique:users,name'],
            'email' => ['required'],
            'password' => ['required'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'User Created Successfully'
        ]);

    }

    public function show($id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User Id Not Found !'
            ]);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(), ([
            'name' => ['required', 'unique:users'],
        ]));

        if($data->fails())
        {
            return response()->json([
                'message' => 'check your data very well'
            ]);
        }

        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User Id Not Found !'
            ]);
        }

        $user->name = $request->input('name');
        $user->update();

        return response()->json([
            'message' => 'User updated Successfully'
        ]);
      }


    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User Id Not Found !'
            ]);
        }

        $user->delete();

        return response()->json([
            'message' => 'user deleted Successfully'
        ]);
    }

    public function suspend($id)
    {
       $user = User::find($id);

       if(! $user) {
           throw new NotFoundHttpException('user not found');
        }

        $user->active = false;
        $user->save();

        return response()->json([
           'message' => 'User Suspended Successfully'
        ]);
    }

    public function active($id)
    {

       $user = User::find($id);

       if(! $user) {
           throw new NotFoundHttpException('user not found');
        }

        $user->active = true;
        $user->save();

        return response()->json([
           'message' => 'User Been Active Successfully'
        ]);
    }
}
