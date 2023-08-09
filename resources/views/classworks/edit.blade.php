<x-main-layout title="Update Classwork">
  <x-navbar :area="route('classrooms.show', $classroom->id)" :ass="route('classrooms.classworks.index', $classroom->id)" :people="route('classrooms.people', $classroom->id)" />

  <div class="container mt-4">
      <x-alert name="success" class="alert-success"/>

      <div class="row">
          <div class="col-md-8">
      <h3 class="mb-4">Update Classwork</h3>

              <form action="{{ route('classrooms.classworks.update', [$classroom->id, $classwork->id, 'type' => $type]) }}" method="POST">
                  @csrf
                  @method('put')

                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="title" name="title" value="{{ $classwork->title }}" placeholder="Title">
                      <label for="title">Title</label>
                  </div>

                  <div class="form-floating mb-3">
                      <textarea class="form-control" id="description" name="description" rows="15" placeholder="Description" style="height: 150px">{{ $classwork->description }}</textarea>
                      <label for="description">Description</label>
                  </div>

                  <button type="submit" class="btn btn-success">Update</button>
              </form>
          </div>

          <div class="col-md-4">
              <div class="students-section">
                  <h4>Assigned Students</h4>
                  @foreach($classroom->students as $student)
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}" id="std-{{ $student->id }}" @checked(in_array($student->id , $assigned))>
                      <label class="form-check-label" for="std-{{ $student->id }}">{{ $student->name }}</label>
                  </div>
                  @endforeach
              </div>

              <div class="topic-section mt-4">
                  <h4>Topic</h4>
                  <select class="form-select" name="topic_id" id="topic_id">
                      @foreach($classroom->topics as $topic)
                      <option @selected($topic->id == $classwork->topic_id) value="{{ $topic->id }}">{{ $topic->name }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
      </div>
  </div>
</x-main-layout>

<style>
  .students-section, .topic-section {
      background-color: #f8f9fa;
      padding: 1.5rem;
      border-radius: 10px;
  }

  .form-check-label {
      font-weight: 500;
  }

  .topic-section select {
      width: 100%;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 0.5rem;
  }
</style>
