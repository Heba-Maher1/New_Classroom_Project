<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Models\Stream;
use App\Models\Topic;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ClassroomsController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscribed')->only('create', 'store');
    }

    public function index()
    {
        $classrooms = Classroom::Active()
                    ->recent()
                    ->orderBy('created_at' , 'DESC')
                    ->get();


        return view('classrooms.index', [
            'classrooms' => $classrooms,
            'success' => session('success'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Classroom::class);

        return view('classrooms.create' , [
            'classroom' => new Classroom(),
        ]);
    }

    public function store(ClassroomRequest $request)
    {
        $this->authorize('create', Classroom::class);

        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $path = Classroom::uploadCoverImage($request->file('cover_image'));
            $validated['cover_image_path'] = $path;
        }

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

    public function show($id)
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

    public function edit(Classroom $classroom)
    {
        $this->authorize('update', $classroom);

        return view('classrooms.update', compact('classroom'));
    }

    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $this->authorize('update', $classroom);

        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $path = Classroom::uploadCoverImage($request->file('cover_image'));
            $validated['cover_image_path'] = $path;
        }

        if ($classroom->cover_image_path) {
            Classroom::deleteCoverImage($classroom->cover_image_path);
        }

        $old = $classroom->cover_image_path;

        $classroom->update($validated);
        
        if($old && $old != $classroom->cover_image_path){
            Classroom::deleteCoverImage($old);
        }

        return redirect()->route('classrooms.index')->with('success', 'Classroom Updated');
    }

    public function destroy(Classroom $classroom)
    {
        $this->authorize('delete', $classroom);

        $classroom->delete();

        return redirect(route('classrooms.index'))->with('success', 'Classroom Deleted');
    }

    public function trashed()
    {
        $classrooms = Classroom::onlyTrashed()->latest('deleted_at')->get();
        return view('classrooms.trashed', compact('classrooms'));
    }

    public function restore($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore();
        return redirect()->route('classrooms.index')->with('success', "Classroom ({$classroom->name}) Restored");
    }

    public function forceDelete($id)
    {
        $classroom = Classroom::withTrashed()->findOrFail($id);

        if ($classroom->cover_image_path) {
            Classroom::deleteCoverImage($classroom->cover_image_path);
        }

        $classroom->forceDelete();

        return redirect()->route('classrooms.index')->with('success', "Classroom ({$classroom->name}) Deleted Forever!");
    }

    public function chat(Classroom $classroom)
    {
        return view('classrooms.chat', compact('classroom'));
    }
}
