<x-main-layout title="Create Classrooms">
    <div class="container mt-5">
        <h1>Create Classroom</h1>
        <form  method="POST" action="{{ route('classrooms.store') }}" enctype="multipart/form-data">
            @csrf     
            @include('classrooms._form' , [
                    'button_lable' => 'Create '
            ])
        </form>
    </div>
</x-main-layout>