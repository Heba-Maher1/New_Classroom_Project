<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Http\Requests\TopicRequest;
use App\Models\Classroom;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TopicsController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $classroom)
    {
        return view('topics.create' , compact('classroom'));
    }

    public function store(Request $request, Classroom $classroom): RedirectResponse
    {
                $topic = new Topic();
                $topic->name = $request->post('name');
                $topic->classroom_id = $request->post('classroom_id');
                $topic->save();

                return redirect()->route('classrooms.index');
    }


    public function edit(string $id)
    {
        $topics = Topic::find($id);
        if(!$topics)
        {
            abort(404);
        }
        return view('topics.update',[
            'topic' => $topics,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $topics = Topic::find($id);
        $topics->update($request->all());

        return Redirect::route('classrooms.index')->with('success' , 'Topic Updated')->with('error' , 'error in update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id )
    {
        $topic = Topic::findOrFail($id);

        $topic->delete();

        return redirect(route('classrooms.index'))->with('success' , 'Topic Deleted')->with('error' , 'error in delete');

    }

    public function trashed()
    {
        $topics = Topic::onlyTrashed()->latest('deleted_at')->get();
        return view('topics.trashed' , compact('topics'));
    }

    public function restore($id )
    {
        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->restore(); 
        return redirect()->route('classrooms.index')->with('success' , "topic ({$topic->name}) restored")->with('error' , 'error in restore'); 
    }

    public function forceDelete($id)
    {
        $topic = Topic::withTrashed()->findOrFail($id);
        $topic->forceDelete();

        return redirect()->route('classrooms.index')->with('success' , "tpoic ({$topic->name}) deleted forever!")->with('error' , 'error in delete forever');
    }
}
