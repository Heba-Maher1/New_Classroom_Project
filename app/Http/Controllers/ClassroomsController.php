<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Models\Post;
use App\Models\Scopes\UserClassroomScope;
use App\Models\Stream;
use App\Models\Topic;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // $this->middleware('subscribed')->only('create' , 'store');
        // $this->authorizeResource(Classroom::class);
    }

    public function index()
    {

        $classrooms = Classroom::Active()->recent()->orderBy('created_at' , 'DESC')->get();

        $success = session('success');

        return view('classrooms.index' , compact('classrooms' , 'success'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classrooms.create' , [
            'classroom' => new Classroom(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassroomRequest $request)
    {
        $validated = $request->validated();

        if($request->hasFile('cover_image')){

            $file = $request->file('cover_image');

            $path = Classroom::uploadCoverImage($file);

            $validated['cover_image_path'] = $path;

        }
// come from listener and events in booted function in the model
        // $validated['code'] = Str::random(8);
        // $validated['user_id'] = Auth::id(); //Auth::user()->id

        DB::beginTransaction();
        try{

            $classroom = Classroom::create($validated);

            $classroom->join(Auth::id() , 'teacher');

            DB::commit();

        }catch(QueryException $e){
            DB::rollBack();
            return back()->with('error' , $e->getMessage())
            ->withInput();
        } 

        return redirect()->route('classrooms.index')
                         ->with('success' , 'Classroom Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classroom = Classroom::withTrashed()->findOrFail($id);
        $topics = Topic::where('classroom_id', '=', $classroom->id)->get();
        $streams = Stream::where('classroom_id' , $classroom->id)->get();

        if(!$classroom)
        {
            abort(404);
        }

        $invitation_link = URL::SignedRoute('classrooms.join' , [
            'classroom' => $classroom->id,
            'code' => $classroom->code,
        ]) ;

        return view('classrooms.show' , [
            'classroom' => $classroom,
            'topics' => $topics,
            'invitation_link' => $invitation_link,
            'streams' => $streams,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        if(!$classroom)
        {
            abort(404);
        }
        return view('classrooms.update',[
            'classroom' => $classroom,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomRequest $request ,Classroom $classroom)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
     
            $file = $request->file('cover_image');
            $path = Classroom::uploadCoverImage($file);
            
            $validated['cover_image_path'] = $path;
        }
        
        
        $old = $classroom->cover_image_path;

        $classroom->update($validated);
        
        if($old && $old != $classroom->cover_image_path){
            Classroom::deleteCoverImage($old);
        }
    
        return redirect()->route('classrooms.index')->with('success' , 'Classroom Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return redirect(route('classrooms.index'))->with('success' , 'Classroom Deleted');
    }

    public function trashed()
    {
        $classrooms = Classroom::onlyTrashed()->latest('deleted_at')->get();
        return view('classrooms.trashed' , compact('classrooms'));
    }

    public function restore($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore(); 
        return redirect()->route('classrooms.index')->with('success' , "classroom ({$classroom->name}) restored");  
    }
    
    public function forceDelete($id)
    {
        $classroom = Classroom::withTrashed()->findOrFail($id);
        $classroom->forceDelete();
        // Classroom::deleteCoverImage($classroom->cover_image_path);

        return redirect()->route('classrooms.index')->with('success' , "classroom ({$classroom->name}) deleted forever!");
    }
}
