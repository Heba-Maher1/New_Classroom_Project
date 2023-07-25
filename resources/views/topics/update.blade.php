<x-main-layout title="update Classrooms">
    <div class="container mt-5">
        <h1>Update Topic</h1>
        <form action="{{ route('topics.update' , $topic->id)}}" method="post">
            @csrf
            @method('put')
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="classname" placeholder="Topic  Name" name="name" value="{{ $topic->name }}">
                <label for="classname">Topic Name</label>
            </div>
            <button type="submit" class="btn btn-success">Update Topic</button>
        </form>
    </div>
</x-main-layout>