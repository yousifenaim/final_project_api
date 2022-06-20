<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = \App\Models\News::all();
        return response()->json($news);
    }

    public function getNewsByTitle(Request $request)
    {

        if ($request->title == null) {
            return response()->json('لا يتوفر بيانات', 422);
        }


        $clasess = News::where('title', 'like', '%' . $request->title . '%')->paginate();

        return response()->json($clasess);
    }

    public function getNewsByCategory(Request $request)
    {
        if ($request->category_name == null) {
            return response()->json('لا يتوفر بيانات', 422);
        }
        $clasess = News::where('category_name', 'like', '%' . $request->category_name . '%')->paginate();
        return response()->json($clasess);
    }

    public function getNewsByDate(Request $request)
    {
        if ($request->created_at == null) {
            return response()->json('لا يتوفر بيانات', 422);
        }
        $clasess = News::where('created_at', 'like', '%' . $request->created_at . '%')->paginate();
        return response()->json($clasess);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|max:150',
            'description' => 'required',
            'user_id' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000',
        ], [], [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'category_id' => 'معرف التصنيف',
            'user_id' => 'معرف اليوزر',
            'image' => 'الصورة',
        ]);
        if ($validated->fails()) {
            $msg = "تأكد من البيانات المدخلة";
            $data = $validated->errors();
            return response()->json(compact('msg', 'data'), 422);
        }
        $news = new News();
        $news->title = $request->title;
        $news->description = $request->description;
        $news->user_id = $request->user_id;
        $news->category_id = $request->category_id;
        $category = Category::Find($request->category_id);
        $news->category_name = $category->name;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $news->image = $path;
        }
        $news->save();
        return response()->json(['msg' => "تم الاضافة بنجاح"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|max:150',
            'description' => 'required',
            'category_id' => 'required|integer',
            'image' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000' . $id,
        ], [], [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'category_id' => 'معرف التصنيف',
            'user_id' => 'معرف اليوزر',
            'image' => 'الصورة',
        ]);
        if ($validated->fails()) {
            $msg = "تأكد من البيانات المدخلة";
            $data = $validated->errors();
            return response()->json(compact('msg', 'data'), 422);
        }
        //dd($request);
        $news = News::Find($id);
        $news->title = $request->title;
        $news->description = $request->description;
        $news->category_id = $request->category_id;
        $category = Category::Find($request->category_id);
        $news->category_name = $category->name;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $news->image = $path;
        }
        $news->save();
        return response()->json(['msg' => "تم التحديث بنجاح"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (News::Find($id)) {
            $news = News::destroy($id);
            return response()->json(['msg' => "تم الحذف بنجاح"]);
        } else
            return response()->json(['msg' => "تأكد من المعرف"]);

    }
}
