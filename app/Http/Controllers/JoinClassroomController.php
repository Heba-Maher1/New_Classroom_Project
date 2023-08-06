<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassroomUser;
use App\Models\Scopes\UserClassroomScope;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JoinClassroomController extends Controller
{
    public function create($id)
    {
        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)
                    ->active()
                    ->findOrFail($id);

        try{

            $this->exists($classroom , Auth::id());

        } catch(Exception $e){

            return redirect()->route('classrooms.show' , $id);           

        }

        return view('classrooms.join' , compact('classroom'));

        // $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)->active()->findOrFail($id);

        // try{
        //     $this->exists($id , Auth::id());
        // }catch(Exception $e){
        //     return redirect()->route('classrooms.show' , $id);
        // }


        // return view('classrooms.join' , compact('classroom'));
    }


    public function store(Request $request , $id)
    {

        $request->validate([
            'role' => 'in:student,teacher'
        ]);

        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)
                    ->active()
                    ->findOrFail($id);

        try{

            $classroom->join(Auth::id() , $request->input('role' , 'student'));

        }catch(Exception $e){

            return redirect()->route('classrooms.show' , $id);

        }

        return redirect()->route('classrooms.show' , $id);

        // $request->validate([
        //     'role' => 'in:student,teacher',
        // ]);

        // $classroom = Classroom::findOrFail($id);

        // try{
        //     $this->exists($id , Auth::id());
        // }catch(Exception $e){
        //     return redirect()->route('classrooms.show' , $id);
        // }

        //       DB::table('classroom_user')->insert([
        //     'classroom_id' => $classroom->id,
        //     'user_id' => Auth::id(),
        //     'role' =>  $request->input('role' , 'student'),
        //     'created_at' => now(),
        // ]);

        // return redirect()->route('classrooms.show' , $id);

    }

    protected function exists(Classroom $classroom , $user_id){
        
        // using relation

        //users->where => all users in array and filter the data , where here is differ user()->where
        
        $exists = $classroom->users()
            ->wherePivot('user_id' ,'=' , $user_id)
            ->exists();
            // OR
        // $exists = $classroom->users()
        //     ->where('id' ,'=' , $user_id)
        //     ->exists();

        if($exists){
            throw new Exception('User already joined the classroom');
        }

        // $exists =   DB::table('classroom_user')
        // ->where('classroom_id' , $classroom_id)
        // ->where('user_id' , $user_id)
        // ->exists();
    }
        
}
