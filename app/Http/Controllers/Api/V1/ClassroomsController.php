<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassroomCollection;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Throwable;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
        }

         // return the data of classroom with the data of user relationship and topics relationship
        // return Classroom::with('user:id,name' , 'topics')->withCount('students')->get(); // return the data of classroom with the data of user relationship and topics relationship
        // return Classroom::all();
        // return Classroom::paginate();
        $classrooms = Classroom::with('user:id,name' , 'topics')->withCount('students')->get(); 

        // return ClassroomResource::collection($classrooms); // using resource
        return new ClassroomCollection($classrooms); // using resource collection

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if(!Auth::guard('sanctum')->user()->tokenCan('classrooms.create')){
            abort(403);
        }

        $request->validate([
            'name' => ['required']
        ]);

        $classroom = Classroom::create($request->all());

        return Response::json([
            'code' => 100,
            'message' => __('Classroom created.'),
            'classroom' => $classroom,
        ] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {

        if(!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
        }

        return new ClassroomResource($classroom->load('user')->loadCount('students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {

        if(!Auth::guard('sanctum')->user()->tokenCan('classrooms.update')){
            abort(403);
        }

        $request->validate([
            'name' => ['sometimes' ,'required' , Rule::unique('classrooms' , 'name')->ignore($classroom->id)],
            'section' => ['sometimes']
        ]);

        $classroom->update($request->all());

        return [
            'code' => 100,
            'message' => __('Classroom Updated.'),
            'classroom' => $classroom,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if(!Auth::guard('sanctum')->user()->tokenCan('classrooms.delete')){
            abort(403 , 'You Cannot delete this classroom');
        }

        Classroom::destroy($id);

        return Response::json([] , 204);
    }
}
