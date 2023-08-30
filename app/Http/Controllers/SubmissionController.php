<?php

namespace App\Http\Controllers;

use App\Models\Classwork;
use App\Models\ClassworkUser;
use App\Models\Submission;
use App\Rules\ForbiddenFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SubmissionController extends Controller
{
    public function store(Request $request , Classwork $classwork)
    {

        Gate::authorize('submissions.create', [$classwork]); 

        $request->validate([
            'files' => 'required',
            'files.*' => ['file' , new ForbiddenFile( 'text/x-php' , 'application/x-msdownload')], //validation on the items inside the array (.*) 
        ]);
        
        $assigned = $classwork->users()->where('id' , Auth::id())->exists();
        if(!$assigned){
            abort(403);
        }

        //return array of uploaded files
        DB::beginTransaction();

        try{
            $data = [];
            foreach($request->file('files') as $file){
                $data[] =[
                    'classwork_id' => $classwork->id,
                    'content' => $file->store("Submissions/{$classwork->id}"),
                    'type' => 'file',
                ];
            }  

            $user = Auth::user();

            $user->submissions()->createMany($data);

            // Submission::insert($data);

            ClassworkUser::where([
                'user_id' => $user->id,
                'classwork_id' => $classwork->id,
            ])->update([
                'status' =>'submitted',
                'submitted_at' => now(),
                
            ]);

            DB::commit();
        
        }catch(Throwable $e){

            DB::rollBack();

            return back()->with('error' , $e->getMessage());

        }
       
            return back()->with('success' , 'Work Submitted');
    
    }

    public function file(Submission $submission)
    {

        $user = Auth::user();
        // check if the user is classroom teacher 
        /*
         select * from classroom_user
         where user_id = ?
         and role = teacher
         and exists (
            select 1 from classworks where classworks.classroom_id = classroom_user.classroom_id exists (
                select 1 from submissions where submissions.classwork_id = classworks.id id =?
                )
         )

          
         */
        $collections = DB::select('select * FROM classroom_user 
        WHERE user_id = ?
        AND role = ?
        AND  EXISTS (
            SELECT 1 FROM classworks WHERE classworks.classroom_id = classroom_user.classroom_id 
             AND EXISTS  (
                SELECT 1 FROM submissions WHERE submissions.classwork_id =  classworks.id AND id= ?) 
                )'
        , [$user->id , 'teacher' , $submission->id]);

        $isTeacher = $submission
                    ->classwork
                    ->classroom
                    ->teacher()
                    ->where('id' , $user->id)->exists();
                    
        $isOwner = $submission->user_id == $user->id;

        if(!$isTeacher && ! $isOwner){
            abort(403);
        }

        // Storage::disk('local')->download($submission->content);
        
        return response()->file(storage_path('app/' . $submission->content));
    }
}
