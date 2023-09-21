<?php
namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Classroom;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function create(Classroom $classroom)
    {
        $this->authorize('createTopic', $classroom);

        return view('topics.create', compact('classroom'));
    }

    public function store(TopicRequest $request): RedirectResponse
    {

        $validatedData = $request->validated();

        $validatedData['user_id'] = Auth::id();
        
        Topic::create($validatedData);

        return redirect()->route('classrooms.index')->with('success', 'Topic Created');
    }

    public function edit(Topic $topic)
    {
        return view('topics.update', compact('topic'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());

        return redirect()->route('classrooms.index')->with('success', 'Topic Updated');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect(route('classrooms.index'))->with('success', 'Topic Deleted');
    }

    public function trashed()
    {

        $topics = Topic::onlyTrashed()->latest('deleted_at')->get();

        return view('topics.trashed', compact('topics'));
    }

    public function restore($id)
    {

        $topic = Topic::onlyTrashed()->findOrFail($id);

        $topic->restore();

        return redirect()->route('classrooms.index')->with('success', "Topic ({$topic->name}) restored");
    }

    public function forceDelete(Topic $topic)
    {
        $topic->forceDelete();

        return redirect()->route('classrooms.index')->with('success', "Topic ({$topic->name}) deleted forever!");
    }
}
