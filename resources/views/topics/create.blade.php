<x-main-layout title="Create Topics">
    <div class="container mt-5">
        <h1>Create Topic</h1>
        <form action="{{ route('topics.store' , $classroom) }}" method="POST">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="classname" placeholder="Topic  Name" name="name">
                <label for="classname">Topic Name</label>
            </div>
            <input type="hidden" name="classroom_id" value="{{ $classroom }}">
            <button type="submit" class="btn btn-success">Create Topic</button>
        </form>
    </div>
</x-main-layout>