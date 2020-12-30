<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FileController;
use League\CommonMark\Block\Parser\BlockParserInterface;

class BlogController extends FileController
{

    public function create(Request $req)
    {

        $validate = Validator::make($req->all(), [
            'title' => 'required',
            'slug' => 'required|unique:blogs',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'body' => 'required|string'
        ]);

        if ($validate->fails())
            return response()->json($validate->messages(), 200);

        try {

            if (!empty($req->image)) {
                $image = $this->upload('blog/images/', $req->file('image'));
                if ($image)
                    $req['image'] = $image;
                else
                    return response()->json(['status' => 0, 'msg' => $this->upload_file_fails]);
            } else
                $req['image'] = 'default.png';

            $req['user_id'] = Auth::id();

            Blog::create($req->except('_token'));

            return response()->json(['status' => 1, 'msg' => $this->success_msg]);
        } catch (\Exception $exp) {
            return response()->json(['status' => 0, 'msg' => $this->fails_msg]);
        }

    }

    public function update(Request $req)
    {

        $validate = Validator::make($req->all(), [
            'title' => 'required',
            'slug' => 'required',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'body' => 'required|string',
            'blog_id' => 'required'
        ]);

        if ($validate->fails())
            return response()->json($validate->messages(), 200);

        try {

            if (Blog::where('slug', $req->slug)->count() > 0)
                return response()->json(['status' => 0, 'msg' => 'نامک فایل قبلا ثبت شده است']);

            if (!empty($req->image)) {
                $image = $this->upload('blog/images/', $req->file('image'));

                if ($image !== false) {
                    $req->image = $image;
                } else {
                    return response()->json(['status' => 0, 'msg' => $this->upload_file_fails]);
                }

            } else
                $req->image = 'default.png';

            Blog::where('id', $req->blog_id)->update([
                'title' => $req->title,
                'slug' => $req->slug,
                'body' => $req->body,
                'image' => $req->image,
            ]);

            return response()->json(['status' => 1, 'msg' => $this->success_msg]);
        } catch (\Exception $exp) {
            return response()->json(['status' => 0, 'msg' => $this->fails_msg]);
        }
    }

    public function changeStatus($blog_id, $status)
    {

        try {

            $blog = Blog::findOrFail($blog_id);
            $blog->status = $status;
            $blog->save();

            return response()->json(['status' => 1, 'msg' => $this->success_msg]);
        } catch (\Exception $exp) {
            return response()->json(['status' => 0, 'msg' => $this->fails_msg]);
        }

    }

    public function destroy($blog_id){

        try {

            $blog = Blog::findOrFail($blog_id);

            /*
             *  @TODO Find Image Blog & Remove File
             */

            $blog->delete();

            return response()->json(['status' => 1, 'msg' => $this->success_msg]);
        } catch (\Exception $exp) {
            return response()->json(['status' => 0, 'msg' => $this->fails_msg]);
        }
    }

    public function show($blog_id){

        $blog = Blog::findOrFail($blog_id);

        return response()->json(['status' => 1,'result' => $blog]);

    }

    public function all($user_id){

        $blogs = Blog::where('user_id',$user_id)->latest()->get();

        if(count($blogs))
            return response()->json(['status' => 1,'result' => $blogs]);
        else
            return response()->json(['status' => 0,'msg' => $this->empty_result]);

    }

    public function allBlog($status){

        $blogs = Blog::where('status',$status)->latest()->get();

        if(count($blogs))
            return response()->json(['status' => 1,'result' => $blogs]);
        else
            return response()->json(['status' => 0,'msg' => $this->empty_result]);
    }

}
