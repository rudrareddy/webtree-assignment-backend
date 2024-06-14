<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Auth;
use App\Traits\HttpResponses;
class AuthController extends Controller
{
    use HttpResponses;

    /*
    * register new user via name, email address and password
    */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
        if($request->validated()) {
           $user =  User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken,
            ], "User created Successfully...!");
        }
    }

    /*
    * login via email address and password
    */
    public function login(LoginRequest $request)
    {
       $request->validated($request->all());
       if (!auth()->attempt($request->only(['email', 'password']))) {
           return $this->error('', 'Credentails are not matched...!', 401);
       }
       return $this->success(['user' => auth()->user(), 'token' => auth()->user()->createToken('API Token of ' . auth()->user()->name)->plainTextToken],'User logged in Successfully!');
    }

    /*
    * Get existing loggedin user full details
    */
    public function profile(Request $request)
    {
          return $this->success(['user' => $request->user()], 'User Detail get successfully...!');
    }

    /*
    * UpdatePassword on basis of Old password and new Password
    */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        #Match The Old Password
        if (!Hash::check($request->old_password, Auth()->user()->password)) {
            return $this->error('', "Old Password Doesn't match...!", 401);
        }
        #Update the new Password
        $request->user()->fill([
            'password' => Hash::make($request->new_password)
        ])->save();
        return $this->success([], 'Password changed successfully...!');
    }

    /*
   * logout existing logged in user
   */
   public function logout()
   {
       Auth()->user()->currentAccessToken()->delete();
       return $this->success("", "User logout successfully...!", 200);
   }
}
