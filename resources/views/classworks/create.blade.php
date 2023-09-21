<x-main-layout title="Create Classwork">
    <div class="container mt-4">
        <h1 class="text-success">{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <hr>

        <h3>Create Classwork</h3>
        <form action="{{ route('classrooms.classworks.store', [$classroom->id, 'type' => $type]) }}" method="POST">
            @csrf
            @include('classworks._form')

            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>

</x-main-layout>
