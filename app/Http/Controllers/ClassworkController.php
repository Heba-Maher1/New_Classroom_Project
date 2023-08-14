<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Topic;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function index(Request $request ,Classroom $classroom)
    {
        // $classworks = Classwork::where('classroom_id' ,'=',$classroom->id)->where('type' , '=',Classroom::TYPE_ASSIGNMENT)->get(); without relation
        
        // $classworks = $classroom->classworks; // with relation , $classroom->classworks = $classroom->classworks()->get()

        
        // $assignments = $classroom->classworks()->where('type' ,'=',Classwork::TYPE_ASSIGNMENT)->get();
        
        $classworks = $classroom->classworks()
        ->with('topic') // Eager Load
        ->filter($request->query())
        ->orderBy('published_at')
        ->paginate(5);


        return view('classworks.index' , [
            'classroom' => $classroom,
            'classworks' => $classworks/*->groupBy('topic_id')*/,
            'topics' => Topic::all(),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request ,Classroom $classroom)
    {

        $type = $this->getType($request);

        $classwork = new Classwork();

        $topics = $classroom->topics;

        return view('classworks.create' , compact('classroom' , 'type' , 'classwork'));
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
            'students' => ['required', 'array'],
            'options.grade' => [Rule::requiredIf(fn() => $type == 'assignment') , 'numeric' , 'min:0'],
            'options.due' => ['nullable' , 'date' , 'after:published_at'],
        ]);

        $request->merge([
            'user_id' => Auth::id(),
            'type' => $type,
            'published_at' => now(),
        ]);

        try{
            DB::transaction(function() use($classroom , $request){
                
                //there are two actions related to each other insert classwork and students 
                $classwork = $classroom->classworks()->create($request->all()); // there is no need to pass the classroom id because the relationship
                // Classwork::create($request->all());
    
                $classwork->users()->attach($request->input('students'));
            });
        }catch(QueryException $e){
            return back()->with('error' , $e->getMessage());
        }

       

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , 'Classwork Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom , Classwork $classwork)
    {
        $classwork->load('comments.user');

        return view('classworks.show' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request ,Classroom $classroom , Classwork $classwork)
    {
        $type = $classwork->type->value;

        $assigned = $classwork->users()->pluck('id')->toArray();

        return view('classworks.edit' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
            'assigned' => $assigned,
            'type' => $type,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Classroom $classroom , Classwork $classwork)
    {
        $type = $classwork->type;

        $request->validate([
            'title' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable' , 'string'],
            'topic_id' => ['nullable' , 'int' , 'exists:topics,id'], //exists:table-name,column-name
            'students' => ['required', 'array'],
            'options.grade' => [Rule::requiredIf(fn() => $type == 'assignment') , 'numeric' , 'min:0'],
            'options.due' => ['nullable' , 'date' , 'after:published_at'],
        ]);

        $classwork->update($request->all());

        $classwork->users()->sync($request->input('students'));

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , 'Classwork Updated!');

        // $request->validate([
        //     'title' => ['required' , 'string' , 'max:255'],
        //     'description' => ['nullable' , 'string'],
        //     'topic_id' => ['nullable' , 'int' , 'exists:topics,id'],
        // ]);

        // $request->merge([
        //     'user_id' => Auth::id(),
        // ]);

        // $classroom->update([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'topic_id' => $request->topic_id,
        // ]); 

        // return redirect()->route('classrooms.classworks.index' , $classroom->id)
        //                  ->with('success' , 'Classwork Updated!');
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
