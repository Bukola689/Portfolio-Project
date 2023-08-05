<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminPermissionController extends Controller
{
    public function show($id)
    {
       $user = User::find($id);

       if(! $user) {
          throw new NotFoundHttpException('user not found');
       }

       return response()->json($user->getAllPermissions());
    }
}
