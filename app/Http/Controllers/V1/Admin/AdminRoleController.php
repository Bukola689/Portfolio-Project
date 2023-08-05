<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminRoleController extends Controller
{
    public function show($id)
    {
       $user = User::find($id);

       if(! $user) {
          throw new NotFoundHttpException('user not found');
       }

       return response()->json($user->getRoleNames());
    }

    public function changeRole(Request $request, $id)
    {
       $user = User::find($id);

       if(! $user) {
          throw new NotFoundHttpException('user not found');
       }

       try {
        $user->syncRoles([$request->role]);
       }  catch (HttpException $th) {
            throw $th;
        }
        return response()->json([
            'success' =>  'User Roles Updated !'
        ]);
     
    }

}
