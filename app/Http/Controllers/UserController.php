<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{

    private function checkRole($user){
        $role = $user->role;

        return $role->name != 'ADMIN';
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if($this->checkRole($user)) return response('unauthorized',401);

        $currentPage = $request->get("page"); // You can set this to any page you want to paginate to

        $size = $request->get("size");

        $name= $request->get("name");

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        return $name == null ?
            User::paginate($size)
            :   User::where("name" , "LIKE" , "%{$name}%")
                ->paginate($size);

    }

    /**
     * Register Function
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $user  = $request->user();
        if($this->checkRole($user)) return response('unauthorized',401);

        if($request->confirmPassword == $request->password){
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string',
                'confirmPassword' => 'required|string',
            ]);

            $role  = Role::where("name" , $request->get("roleName"))->first();

            $user = new User();

            $user->email = $request->email;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->password =Hash::make($request->password);
            $user->address =$request->address;

            $user->role_id = $role->id;
            $user->save();

            $token  = $user->createToken("APP_TOKEN");

            $response = [
                'user' => $user,
                'token' => $token->plainTextToken
            ];

            return $response;
        }
    }

    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;

        $user = User::where("email" , $email)->firstOrFail();

        if($user == null) return "Error No User Found";

        if(!Hash::check($password , $user->password)) return "Credential Wrong";

        $token  = $user->createToken("APP_TOKEN");

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    public function show(User $user)
    {
        if($this->checkRole($user)) return response('unauthorized',401);
        return $user->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = $request->user();

        if($this->checkRole($user)) return response('unauthorized',401);

        $email = $request->get("email");

        $user = User::where("email" , $email)->firstOrFail();

        if($request->get("address")) $user->address =
                $request->get("address");

        if($request->get("phone")) $user->phone =
            $request->get("phone");

        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $request->user();
        if($this->checkRole($user)) return response('unauthorized',401);
        User::destroy($id);
    }
}
