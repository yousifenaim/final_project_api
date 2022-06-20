<?php

namespace App\Http\Controllers;

use \App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
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
            'name' => 'required|max:15',
            'email' => 'required|unique:users',
            'password' => 'required',
            'confirmpassword' => 'required|same:password',
        ], [], [
            'name' => 'الاسم',
            'email' => 'البريد الالكتروني',
            'password' => 'كلمة المرور',
            'confirmpassword' => ' تأكيد كلمة المرور',
        ]);
        if ($validated->fails()) {
            $msg = "تأكد من البيانات المدخلة";
            $data = $validated->errors();
            return response()->json(compact('msg', 'data'), 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
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
        $user = User::Find($id);
        return response()->json(compact('user'));
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
            'name' => 'required|max:100',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'required',
            'confirmpassword' => 'required|same:password',
        ], [], [
            'name' => 'الاسم',
            'email' => 'البريد الالكتروني',
            'password' => 'كلمة المرور',
            'confirmpassword' => ' تأكيد كلمة المرور',
        ]);
        if ($validated->fails()) {
            $msg = "تأكد من البيانات المدخلة";
            $data = $validated->errors();
            return response()->json(compact('msg', 'data'), 422);
        }
        //dd($request);

        $user = User::Find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
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
        if (User::Find($id)) {
            $user = User::destroy($id);
            return response()->json(['msg' => "تم الحذف بنجاح"]);
        } else
            return response()->json(['msg' => "تأكد من المعرف"]);

    }

    public function login(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'عذرا هذا الايميل غير صحيح'], 401);
        }
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response($response, 200);
        } else {
            $response = ["message" => "عذرا كلمة المرور خطأ"];
            return response($response, 422);
        }
    }



}
