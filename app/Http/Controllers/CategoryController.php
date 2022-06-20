<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = \App\Models\Category::all();
        return response()->json($category);
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
            'name' => 'required|max:20',
            'user_id' => 'required|integer',
        ], [], [
            'name' => 'الاسم',
            'user_id' => 'معرف اليوزر',
        ]);
        if ($validated->fails()) {
            $msg = "تأكد من البيانات المدخلة";
            $data = $validated->errors();
            return response()->json(compact('msg', 'data'), 422);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->user_id = $request->user_id;
        $category->save();
        return response()->json(['msg' => "Add Success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
            'name' => 'required|max:100' . $id,
        ], [], [
            'name' => 'الاسم',
            'user_id' => 'معرف اليوزر',
        ]);
        if ($validated->fails()) {
            $msg = "تأكد من البيانات المدخلة";
            $data = $validated->errors();
            return response()->json(compact('msg', 'data'), 422);
        }
        //dd($request);

        $category = Category::Find($id);
        $category->name = $request->name;
        $category->save();
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
        if (Category::Find($id)) {
            $category = Category::destroy($id);
            return response()->json(['msg' => "تم الحذف بنجاح"]);
        } else
            return response()->json(['msg' => "تأكد من المعرف"]);

    }
}
