<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public $success_msg = 'عملیات با موفقیت انجام شد';
    public $fails_msg = 'خطا : عملیات با شکست مواجه شد';
    public $empty_result = 'هیچ داده ای پیدا نشد';
    public $upload_file_fails = 'خطا در بارگذاری فایل رخ داده است';
    public $user_id = 2;

    public function __construct()
    {
        Auth::loginUsingId(2);
    }

    public function upload($path, $file){

        $file_name = date('y_m_d_h_i_s') . '_' . rand(1,1000) . '.' . $file->getClientOriginalExtension();

        //return public_path('../../' . $path);

        if ($file->move(public_path($path), $file_name))
            return "$path$file_name";
        else
            return false;

    }
}
