<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          // reset cached roles and permissions
          app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

          $accessUser = 'access-user';
          $storeUser = 'store-user';
          $editUser = 'edit-user';
          $deleteUser = 'delete-user';
          $countUser = 'count-user';
 
          $accessRegister = 'access-register';
          $accessLogin = 'access-login';
          $accessLogout = 'access-logout';
 
          $forgotPassword = 'forgot-password';
          $resetPassword = 'reset-password';
 
          $profileUpdate = 'profile-update';
  
          $accessPost = 'access-post';
          $storePost = 'store-post';
          $showPost = 'show-post';
          $updatePost = 'edit-post';
          $deletePost = 'delete-post';
          $countPost = 'count-post';
  
          $accessCategory = 'access-categroy';
          $storeCategory = 'store-categroy';
          $editCategory = 'edit-categroy';
          $deleteCategory = 'delete-categroy';
          $countCategory = 'count-categroy';
  
          $accessTag = 'access-tag';
          $storeTag = 'store-tag';
          $editTag = 'edit-tag';
          $deleteTag = 'delete-tag';
          $countTag = 'count-tag';
 
          $accessComment = 'access-comment';
          $deleteComment = 'delete-comment';
          $countComment = 'count-comment';
 
          $accessContact = 'access-contact';
          $countContact = 'count-contact';
          $deleteContact = 'delete-contact';
          $countContact = 'count-contact';
 
          $accessSetting = 'access-settings';
          $deleteSetting = 'delete-settings';
 
 
            //create permisssion for post cms//
 
            Permission::create(['name' => $accessUser]);
            Permission::create(['name' => $storeUser]);
            Permission::create(['name' => $editUser]);
            Permission::create(['name' => $deleteUser]);
            Permission::create(['name' => $countUser]);
 
            Permission::create(['name' => $accessRegister]);
            Permission::create(['name' => $accessLogin]);
            Permission::create(['name' => $accessLogout]);
 
            Permission::create(['name' => $forgotPassword]);
            Permission::create(['name' => $resetPassword]);
 
            Permission::create(['name' => $profileUpdate]);
    
            Permission::create(['name' => $accessCategory]);
            Permission::create(['name' => $storeCategory]);
            Permission::create(['name' => $editCategory]);
            Permission::create(['name' => $deleteCategory]);
            Permission::create(['name' => $countCategory]);
    
            Permission::create(['name' => $accessPost]);
            Permission::create(['name' => $storePost]);
            Permission::create(['name' => $showPost]);
            Permission::create(['name' => $updatePost]);
            Permission::create(['name' => $deletePost]);
            Permission::create(['name' => $countPost]);
    
            Permission::create(['name' => $accessTag]);
            Permission::create(['name' => $storeTag]);
            Permission::create(['name' => $editTag]);
            Permission::create(['name' => $deleteTag]);
            Permission::create(['name' => $countTag]);
 
            Permission::create(['name' => $accessComment]);
            Permission::create(['name' => $deleteComment]);
            Permission::create(['name' => $countComment]);
 
            Permission::create(['name' => $accessContact]);
            Permission::create(['name' => $countContact]);
            Permission::create(['name' => $deleteContact]);
 
            Permission::create(['name' => $accessSetting]);
            Permission::create(['name' => $deleteSetting]);
 
 
             //...Roles...//
    
             $admin = 'admin';
            // $user = 'user';
 
 
             $role = Role::create(['name' => $admin])->givePermissionTo(Permission::all());
    }
}
