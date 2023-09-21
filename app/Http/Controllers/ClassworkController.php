<?php

namespace App\Http\Controllers;

use App\Events\ClassworkCreated;
use App\Events\classworkUpdated;
use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Topic;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Http;

class ClassworkController extends Controller
{

     protected function getType(Request $request)
     {
        $type = $request->query('type');
        $allowed_types = [Classwork::TYPE_ASSIGNMENT, Classwork::TYPE_MATERIAL, Classwork::TYPE_QUESTION];

        if (!in_array($type, $allowed_types)) {
            $type = Classwork::TYPE_ASSIGNMENT;
        }
        return $type;
     }
     

    public function index(Request $request ,Classroom $classroom , Classwork $classwork)
    {

        $this->authorize('viewAny' , [Classwork::class , $classroom]);
       
        $classworks = $classroom->classworks()
                ->with('topic') // Eager Load
                ->withCount([
                    'users',
                    'users as turnedin_count' => function($query){
                        $query->where('classwork_user.status','=','submitted');
                    },
                    'users as created_count' => function($query){
                        $query->whereNotNull('classwork_user.grade');
                    },
                ])
                ->withCount('users')
                ->filter($request->query())
                ->latest('published_at')
                ->where(function($query){
                    $query->whereHas('users' , function($query){
                        $query->where('id' , '=' , Auth::id());
                    })
                    ->orWhereHas('classroom.teacher' , function($query){
                        $query->where('id' , '=' , Auth::id());
                    });
                })->paginate(50);

        return view('classworks.index' , [
            'classroom' => $classroom,
            'classworks' => $classworks->groupBy('topic_id'),
            'topics' => Topic::all(),
            'classwork' => $classwork,
            'success' => session('success'),
        ]);     
    }

    public function create(Request $request ,Classroom $classroom)
    {

        $this->authorize('create' ,[Classwork::class , $classroom]);

        $type = $this->getType($request);

        $classwork = new Classwork();

        $topics = $classroom->topics;

        return view('classworks.create' , compact('classroom' , 'type' , 'classwork'));
    }

    public function store(Request $request ,Classroom $classroom)
    {

        $this->authorize('create' ,[Classwork::class , $classroom]);
        
        $type = $this->gettype($request);

        $request->validate([
            'title' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable' , 'string'],
            'topic_id' => ['nullable' , 'int' , 'exists:topics,id'],
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

                $classwork = $classroom->classworks()->create($request->all());
    
                $classwork->users()->attach($request->input('students'));
            
                event(new ClassworkCreated($classwork));

            });
        }catch(QueryException $e){

            return back()->with('error' , $e->getMessage());
            
        }

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , __('Classwork Created!'));
    }

    public function show(Classroom $classroom , Classwork $classwork)
    {

        $this->authorize('view' , $classwork);

        $submissions = Auth::user()->submissions()->where('classwork_id' , $classwork->id)->get();
        
        $classwork->load('comments.user');

        return view('classworks.show' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
            'submissions' => $submissions
        ]);
    }

    public function edit(Request $request ,Classroom $classroom , Classwork $classwork)
    {
        $this->authorize('update' , $classwork);

        $type = $classwork->type->value;

        $assigned = $classwork->users()->pluck('id')->toArray();

        return view('classworks.edit' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
            'assigned' => $assigned,
            'type' => $type,
        ]);
    }

    public function update(Request $request,Classroom $classroom , Classwork $classwork)
    {
        $this->authorize('update' , $classwork);

        $type = $classwork->type;

        $request->validate([
            'title' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable' , 'string'],
            'topic_id' => ['nullable' , 'int' , 'exists:topics,id'], 
            'students' => ['required', 'array'],
            'options.grade' => [Rule::requiredIf(fn() => $type == 'assignment') , 'numeric' , 'min:0'],
            'options.due' => ['nullable' , 'date' , 'after:published_at'],
        ]);

        $classwork->update($request->all());

        $classwork->users()->sync($request->input('students'));

        event(new classworkUpdated($classwork));

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , __('Classwork Updated!'));

    }

    public function destroy(Classroom $classroom , Classwork $classwork)
    {
        $this->authorize('delete' , $classwork);

        $classwork->delete();

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , __('Classwork Deleted!'));
    }
}
