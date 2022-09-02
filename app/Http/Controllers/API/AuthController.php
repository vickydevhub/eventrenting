<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends BaseController
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['register','login','verify']);
    }

    public function register(Request $request)
    {
        $rules = [
            
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
            'phone_number'=> 'required'
           
            
        ];
       
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return $this->sendError('Error validation', $validator->errors(),422);
        }

        $input = $request->all();

        $exist = User::where(function($query) use ($input){
            $query->where('email',  $input['email'])
                ->orWhere('phone_number', $input['phone_number']);
            })->first();
            
        if($exist)
        {
        return $this->sendError('User with this email address or phone number already registered.', 'Error validation',422);
        }

       
        
        $input['password'] = bcrypt($input['password']);
        
        $user = User::create($input);
        // Adding permissions via a role
        $user->assignRole($input['role']);
         
        event(new Registered($user));

        // $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
         $success['name'] =  $user->name;

         return $this->sendResponse($success, 'User created successfully.');
        


    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors(),422);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser = Auth::user(); 
                 
            $success['user'] =  $authUser;
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
        
                 
                return $this->sendResponse($success, 'User signed in');
            }  else {
            return $this->sendError('The email address or password you entered is incorrect.', ['error'=>'Unauthorised'],401);
        }
    }

    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        Mail::send('email.signup-thank-you', [
            'fullname'      => $user->fullname

        ], function($message)  use($user){
            $message->subject('Thank you for Sign up');
            $message->to($user->email);
        });
        
        return redirect()->to(config('api.front_end').'/login')->withSuccess('Email successfully verified!.');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse(true, 'Logged out successfully');
    }

    

}
