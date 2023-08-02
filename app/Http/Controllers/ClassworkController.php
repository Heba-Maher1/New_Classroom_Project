<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected function getType(Request $request)
     {
        $type = $request->query('type');
        $allow_types = [
            Classwork::TYPE_ASSIGNMENT , Classwork::TYPE_MATERIAL , Classwork::TYPE_QUESTION
        ];

        if(in_array($type , $allow_types)){
            $type = Classwork::TYPE_ASSIGNMENT;
        }

        return $type;
     }

    public function index(Classroom $classroom)
    {
        // $classworks = Classwork::where('classroom_id' ,'=',$classroom->id)->where('type' , '=',Classroom::TYPE_ASSIGNMENT)->get(); without relation
        
        // $classworks = $classroom->classworks; // with relation , $classroom->classworks = $classroom->classworks()->get()

        
        // $assignments = $classroom->classworks()->where('type' ,'=',Classwork::TYPE_ASSIGNMENT)->get();
        
        $classworks = $classroom->classworks()
        ->with('topic') // Eager Load
        ->orderBy('published_at' , 'DESC')
        ->get();

        

        return view('classworks.index' , [
            'classroom' => $classroom,
            'classworks' => $classworks->groupBy('topic_id'),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request ,Classroom $classroom)
    {

        $type = $this->getType($request);

        
        return view('classworks.create' , compact('classroom' , 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,Classroom $classroom)
    {

        $type = $this->gettype($request);

        $request->validate([
            'title' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable' , 'string'],
            'topic_id' => ['nullable' , 'int' , 'exists:topics,id'], //exists:table-name,column-name
        ]);

        $request->merge([
            'user_id' => Auth::id(),
            'type' => $type ,
            // 'classroom_id' => $classroom->id,
        ]);

        $classroom->classworks()->create($request->all()); // there is no need to pass the classroom id because the relationship

        // Classwork::create($request->all());

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , 'Classwork Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom , Classwork $classwork)
    {
        
        return view('classrooms.show' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom , Classwork $classwork)
    {
        return view('classworks.edit' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Classroom $classroom , Classwork $classwork)
    {
        $request->validate([
            'title' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable' , 'string'],
            'topic_id' => ['nullable' , 'int' , 'exists:topics,id'],
        ]);

        $request->merge([
            'user_id' => Auth::id(),
        ]);

        $classroom->update([
            'title' => $request->title,
            'description' => $request->description,
            'topic_id' => $request->topic_id,
        ]); 

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , 'Classwork Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom , Classwork $classwork)
    {
        $classwork->delete();

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , 'Classwork Deleted!');
    }
}
