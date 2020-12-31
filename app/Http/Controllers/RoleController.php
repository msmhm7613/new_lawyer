<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends FileController
{
    /*
     * Return All Role with type
     */
    public function allRole($type = 2){

        $roles = Role::where('type',$type)->get();
        return $roles;

    }
}
