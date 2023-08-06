<x-main-layout title="Create Classwork">
    <div class="container mt-4">
        <h1 class="text-success ">{{ $classroom->name }} (#{{  $classroom->id }})</h1>
        <hr>
        <h3 class="mb-4">Update Classwork</h3>
        <x-alert name="success" class="alert-success"/>
        <form action="{{ route('classrooms.classworks.update' , [$classroom->id , $classwork->id , 'type' => $type])}}" method="POST">
          @csrf
          @method('put')
        <div class="row">
          <div class="col-md-8">        
                <x-form.form-floating name="title" placeholder="Title">
                  <x-form.input name="title" :value="$classwork->name" placeholder="Title" />
                </x-form.form-floating>

                <x-form.form-floating name="description" placeholder="Description">
                    <x-form.textarea name="description" :value="$classwork->description" placeholder="Description" />
                </x-form.form-floating>
            </div>
            <div class="col-md-4">
              <div>
                @foreach($classroom->students as $student)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}" id="std-{{ $student->id }}" @checked(in_array($student->id , $assigned))>
                  <label class="form-check-label" for="std-{{ $student->id }}">
                    {{ $student->name }}
                  </label>
                </div>  
                @endforeach
            </div>

              <x-form.form-floating name="topic_id" placeholder="Topic Id">
                <select class="form-select" name="topic_id" id="topic_id" >
                  <option value="">No Topic</option>
                  @foreach($classroom->topics as $topic)
                  <option @selected($topic->id == $classwork->topic_id) value="{{ $topic->id }}">{{ $topic->name }}</option>
                  @endforeach
                </select>

              </x-form.form-floating>
          </div>         
              <button type="submit" class="btn btn-success">Create</button>
        </form>
     </div>
    
</x-main-layout>