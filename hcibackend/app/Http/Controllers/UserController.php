<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        try {
            $users = User::all();
            return response()->json(['users'=> $users], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }

        public function delete($id) {
            $user = User::find($id);
            if($user){
                $user->delete();
                return response()->json([
                  'status'=>200,
                  'message'=>'Student Deleted Successfully',
                ],200);
            }else{
                return response()->json([
                    'status'=>400,
                    'message'=>'no User ID found',
                ],404);
            }

        }
}
