<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('projects')->get();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }


    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            Project::where('user_id', $user->id)->delete();
          
            $user->delete(); 
            DB::commit();
            return response()->json([
                'status' => 'Delete User and all his Projects Successfully',
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return response()->json([
                'status' => 'Delete Failed',
            ]);
        }
    }
}
