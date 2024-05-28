<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\userMess;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{

    public function createuser(Request $request)
     {
    try {
      // check
      $validatedData = $request->validate([
          'login' => 'required|string|max:255|min:5|unique:user_mess',
          'email' => 'required|string|email|max:255|unique:user_mess',
          'password' => 'required|string|min:8',
          'telephone' => 'required|string|min:8',
          'adress' => 'required|string|min:10',
          'date_naissance' => 'required|string|min:10',
      ]);
      // create new user
      $userMess = userMess::create([
          'login' => $validatedData['login'],
          'email' => $validatedData['email'],
          'password' => ($validatedData['password']),
          'telephone' => $validatedData['telephone'],
          'adress' => $validatedData['adress'],
          'date_naissance' => $validatedData['date_naissance'],
      ]);

      return response()->json(['user' => $userMess], 201);
  } catch (ValidationException $e) {
      return response()->json(['errors' => $e->errors()], 422);
    }
  }


    function GetUsers()
   {
    $userMess = userMess::All();
    return $userMess ;
   }


   function Getuser($id)
   {
    $userMess = userMess::find($id);
    if (!$userMess) {
        return response()->json(['errors' => 'check your id'], 404);
    }
    return $userMess ;
   }


   function updateuser(Request $request , $id){
    $userMess = userMess::find($id);
    if (!$userMess) {
        return response()->json(['errors' => 'user not found'], 422);
    }
    $validator = Validator::make($request->all(), [
        'login' => 'required|string|max:255|min:5',
        'email' => 'required|string|email|max:255|unique:user_mess,email,' . $id,
        'password' => 'required|string|min:8',
        'telephone' => 'required|string|min:8',
        'adress' => 'required|string|min:10',
        'date_naissance' => 'required|string|min:10',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();
        $errorMessage = 'saisir validate data';
        foreach ($errors->all() as $error) {
            $errorMessage .= ', ' . $error;
        }
        return response()->json(['errors' => $errorMessage], 422);
    }
    $userMess->login = $request->input("login");
    $userMess->email = $request->input("email");
    $userMess->password = $request->input("password");
    $userMess->telephone = $request->input("telephone");
    $userMess->adress = $request->input("adress");
    $userMess->date_naissance = $request->input("date_naissance");
    $userMess->save();
    return response()->json(['status' => 'data update succ', 'user' => $userMess], 200);
    }


   function Deleteuser($id){
    if ( $userMess = userMess::find($id)){
        $userMess->delete();
    return "user deleted" ;
    }
    else {
      return' user not found' ;
    }
   }

   //loginUser

   public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => 'saisir validate data'], 422);
        }

        $credentials = $validator->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['errors' => 'check your email or password'], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => 'welcome ' . $user->login, 'token' => $token], 201);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }



}
