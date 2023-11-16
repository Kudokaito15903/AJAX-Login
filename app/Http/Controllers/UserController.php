<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
class UserController extends Controller
{
    public function index()
    {
        return view("auth.login");
    }

    public function register()
    {
        if (session()->has('loggedInUser')){
            return redirect('/profile');
        }
        else {
            return view("auth.register");
        }
    }

    public function ForgotPassword()
    {
        return view("auth.changePassword");
    }


    public function reset(Request $request)
    {
        $email= $request->email;
        $token= $request->token;
        return view("auth.resetPassword", ['email' =>$email, "token" =>$token]);
    }

    public function saveUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|unique:users|max:100',
            'password' => 'required|min:6|max:50',
            'c_password' => 'required|min:6|same:password'
        ], [
            'c_password.same' => "Password does not match.",
            'c_password.required' => "Confirm password is required."
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status' => 200,
                'user_id' => $user->id,
                'messages' => 'Registered successfully',
                'created_at' => $user->created_at,
            ], Response::HTTP_OK);
        }
    }

    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put("loggedInUser", $user->id);
                    return response()->json([
                        'status' => 200,
                        'messages' => 'Login success'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 401,
                        'messages' => "E-mail or password is incorrect!"
                      ], Response::HTTP_UNAUTHORIZED);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'messages' => "User not found"
                  ], Response::HTTP_UNAUTHORIZED);
            }
        }
    }

    public function forgot () {
        if (session()->has('loggedInUser')){
            return redirect('/profile');
        }
        else  {
            return view('auth.changePassword');
        }
    }
    public function profile () {
        $data= ['userInfo'=> DB::table('users')->where('id', session('loggedInUser'))->first() ];
        return view('auth.profile',$data);
    }

    public function logout() {
        if (session()->has('loggedInUser')){
            session() ->pull ("loggedInUser");
            return redirect('/login');
        }
    }

    public function profileImageUpdate(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/img/', $fileName);
            if ($user->picture) {
                Storage::delete('public/img/' . $user->picture);
            }
            $user->picture = $fileName;
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => "Profile image updated successfully!"
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => "No picture file found in the request."
        ],400);
    }

    public function profileUpdate ( Request $request) {
        User::where('id', $request->id)->update ([
            'name'=>$request->name,
            'email' =>$request ->email,
            'gender'=>$request ->gender,
            'dob' => $request->dob,
            'phone' => $request->phone
        ]);
        return response()->json ([
            'status' =>200,
            'messages'=>"Profile updated Successfully!"

        ]);
    }

    public function sendMailPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ], 400);
        } else {
            $token = Str::uuid();
            $user = DB::table('users')->where('email', $request->email)->first();
            $details = [
                'body' => route('reset', ['email' => $request->email, 'token' =>$token])
            ];

            if ($user) {
                User::where('email', $request->email)->update([
                    'token' => $token,
                    'token_expire' => Carbon::now()->addMinutes(10)->toDateTimeString()
                ]);

                Mail::to($request->email)->send(new ForgotPassword($details));

                return response()->json([
                    'status' => 200,
                    'messages' => 'Reset password link has been sent to your email'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'messages' => 'This e-mail is not register with us!'
                ], 404);
            }
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:6|max:50',
            'confirm_password' => 'required|min:6|max:50|same:new_password'
        ], [
            'confirm_password.same' => 'Passwords do not match!'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ], 400);
        } else {
            $user = DB::table('users')
                ->where('email', $request->email)
                ->whereNotNull('token')
                ->where('token', $request->token)
                ->where('token_expire', '>', Carbon::now())
                ->exists();

            if ($user) {
                DB::table('users')
                    ->where('email', $request->email)
                    ->update([
                        'password' => Hash::make($request->new_password),
                        'token' => null,
                        'token_expire' => null
                    ]);

                return response()->json([
                    'status' => 200,
                    'messages' => 'New password updated!'
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'messages' => 'Reset link expired! Request a new reset password link.'
                ],401);
            }
        }
    }

    public function deleteAccount(Request $request)
{
    $user_id = $request->input('id');
    $user = User::find($user_id);
    if (!$user) {
        return response()->json([
            'status' => 404,
            'message' => 'User not found',

        ], 404);
    }

    // Kiểm tra xác thực người dùng
    if ($user->id !== session('loggedInUser')) {
        return response()->json([
            'status' => 401,
            'message' => 'Unauthorized'
        ], 401);
    }

    // Xóa tài khoản người dùng
    $user->delete();

    // Đăng xuất người dùng
    session()->pull('loggedInUser');

    return response()->json([
        'status' => 200,
        'method' => "DELETED",
        'message' => 'Account deleted successfully'

    ]);
}

public function deleteAccountAPI(Request $request)
{
    $user_id = $request->input('id');
    $user = User::find($user_id);
    if (!$user) {
        return response()->json([
            'status' => 404,
            'message' => 'User not found',

        ], 404);
    }

    // Kiểm tra xác thực người dùng
    // Xóa tài khoản người dùng
    $user->delete();

    // Đăng xuất người dùng
    $currentDateTime =Carbon::now();
    return response()->json([
        'status' => 200,
        'message' => 'Account deleted successfully',
        'deleted_at' => $currentDateTime
    ]);
}
 public function  AllUser () {
    return UserResource::collection(User::all());
 }

}
