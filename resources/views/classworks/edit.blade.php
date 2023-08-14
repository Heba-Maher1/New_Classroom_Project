<x-main-layout title="Update Classwork">
  <x-navbar :area="route('classrooms.show', $classroom->id)" :ass="route('classrooms.classworks.index', $classroom->id)" :people="route('classrooms.people', $classroom->id)" />

  <div class="container mt-4">
      <x-alert name="success" class="alert-success"/>
      <h3 class="mb-4">{{$classwork->type}} - Update Classwork</h3>
        <form action="{{ route('classrooms.classworks.update', [$classroom->id, $classwork->id, 'type' => $type]) }}" method="POST">
            @csrf
            @method('put')
            @include('classworks._form')
            <button type="submit" class="btn btn-success">Update</button>     
        </form>
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
