<x-main-layout title="update Classrooms">
    <div class="container mt-5">
        <h1>{{ __('Update') }} {{ __('Classroom') }}</h1>
        <form action="{{ route('classrooms.update', $classroom->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            @include('classrooms._form', [
                'button_lable' => 'Edit',
            ])
        </form>
    </div>
</x-main-layout>
