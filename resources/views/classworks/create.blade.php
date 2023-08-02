<x-main-layout title="Create Classwork">
    <div class="container mt-4">
        <h1 class="text-success">{{ $classroom->name }} (#{{  $classroom->id }})</h1>
        <hr>
        <h3>Create Classwork</h3>
        <form action="{{ route('classrooms.classworks.store' , [$classroom->id , 'type' => $type])}}" method="POST">
          @csrf
          <x-form.form-floating name="title" placeholder="Title">
            <x-form.input name="title" placeholder="Title" />
          </x-form.form-floating>

            <x-form.form-floating name="description" placeholder="Description">
                <x-form.textarea name="description" placeholder="Description" />
            </x-form.form-floating>

            <x-form.form-floating name="topic_id" placeholder="Topic Id">
                <select class="form-select" name="topic_id" id="topic_id" >
                  <option value="">No Topic</option>
                  @foreach($classroom->topics as $topic)
                  <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                  @endforeach
                </select>

              </x-form.form-floating>
              <button type="submit" class="btn btn-success">Create</button>
        </form>
     </div>
    
</x-main-layout>