<x-main-layout title="Join Classroom">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="rounded shadow-lg p-5 text-center">
            <h2 class="mb-4">{{ $classroom->name }}</h2>
            <form action="{{ route('classrooms.join', $classroom->id) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-success">{{ __('join') }}</button>
            </form>
        </div>

    </div>


</x-main-layout>
