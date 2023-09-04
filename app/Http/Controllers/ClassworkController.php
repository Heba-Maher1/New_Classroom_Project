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
                })
                // ->where(function($query){
                //     $query->whereRaw('EXISTS (SELECT 1 FROM classwork_user WHERE classwork_user.classwork_id = classworks.id AND classwork_user.user_id = ? )', [Auth::id()]);
                //     $query->orWhereRaw('EXISTS (SELECT 1 FROM classroom_user WHERE classroom_user.classroom_id = classworks.classroom_id AND classroom_user.user_id = ? AND classroom_user.role = ?)' , [Auth::id() , 'teacher']);
                // })
                ->paginate(50);



        return view('classworks.index' , [
            'classroom' => $classroom,
            'classworks' => $classworks->groupBy('topic_id'),
            'topics' => Topic::all(),
            'classwork' => $classwork,
            'success' => session('success'),
        ]);

        // $classworks = Classwork::where('classroom_id' ,'=',$classroom->id)->where('type' , '=',Classroom::TYPE_ASSIGNMENT)->get(); without relation
        
        // $classworks = $classroom->classworks; // with relation , $classroom->classworks = $classroom->classworks()->get()
        
        // $assignments = $classroom->classworks()->where('type' ,'=',Classwork::TYPE_ASSIGNMENT)->get();
        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request ,Classroom $classroom)
    {

        $this->authorize('create' ,[Classwork::class , $classroom]);
        
        // $response =  Gate::inspect('classworks.create' , [$classroom]);
        // if(!$response->allowed()){

        //     abort(403 , $response->message() ?? '');

        // }
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

        // Gate::authorize('classworks.create' , [$classroom]);

        $this->authorize('create' ,[Classwork::class , $classroom]);
        
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

            // strip_tags('<h1></h1>');
            DB::transaction(function() use($classroom , $request){
                
                //there are two actions related to each other insert classwork and students 
                $classwork = $classroom->classworks()->create($request->all()); // there is no need to pass the classroom id because the relationship
                // Classwork::create($request->all());
    
                $classwork->users()->attach($request->input('students'));
            
                event(new ClassworkCreated($classwork));

                // ClassworkCreated::dispatch($classwork);

                
            });
        }catch(QueryException $e){
            return back()->with('error' , $e->getMessage());
        }

       

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , __('Classwork Created!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom , Classwork $classwork)
    {

        // Gate::authorize('classworks.view' , [$classwork]);
        $this->authorize('view' , $classwork);

        $submissions = Auth::user()->submissions()->where('classwork_id' , $classwork->id)->get();
        
        $classwork->load('comments.user');

        return view('classworks.show' ,[
            'classroom' => $classroom,
            'classwork' => $classwork,
            'submissions' => $submissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Classroom $classroom , Classwork $classwork)
    {
        $this->authorize('update' , $classwork);

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

        event(new classworkUpdated($classwork));

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , __('Classwork Updated!'));

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
        $this->authorize('delete' , $classwork);

        $classwork->delete();

        return redirect()->route('classrooms.classworks.index' , $classroom->id)
                         ->with('success' , __('Classwork Deleted!'));
    }
}
