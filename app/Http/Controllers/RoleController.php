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

    public function all(){
        $roles = Role::where('type',1)
            ->orWhere('type',2)
            ->select('id','title')
            ->get();
        if(count($roles))
            return response()->json(['status' => 1,'result' => $roles]);
        else
            return response()->json(['status' => 0,'msg' => $this->empty_result]);
    }
}
