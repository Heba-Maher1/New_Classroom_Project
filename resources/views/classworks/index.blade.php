<x-main-layout :title="$classroom->name">
  <x-navbar :area="route('classrooms.show', $classroom->id)" :ass="route('classrooms.classworks.index', $classroom->id)" :people="route('classrooms.people', $classroom->id)" />
  <div class="container mt-4">

      <x-alert name="success" class="alert-success" />
      <x-alert name="error" class="alert-danger" />

      <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Classworks</h2>
          <div class="dropdown">
              <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  + Create
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create' , [$classroom->id , 'type' => 'assignment']) }}">Assignment</a></li>
                <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create' , [$classroom->id , 'type' => 'material']) }}">Material</a></li>
                <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create' , [$classroom->id , 'type' => 'question']) }}">Question</a></li>
            </ul>
          </div>
      </div>

      <div class="accordion" id="accordionFlushExample">
          @forelse ($classworks as $group)
          <div class="accordion-item">
              <h2 class="accordion-header" id="flush-heading{{ $loop->index }}">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $loop->index }}" aria-expanded="false" aria-controls="flush-collapse{{ $loop->index }}">
                      {{ $group->first()->topic->name }}
                  </button>
              </h2>
              <div id="flush-collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{ $loop->index }}" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                      @foreach ($group as $classwork)
                      <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-3">
                          <p class="m-0">{{ $classwork->title }}</p>
                          <div class="buttons d-flex">
                              <a href="{{ route('classrooms.classworks.edit', [$classroom->id, $classwork->id]) }}" class="btn btn-secondary me-2"><i class="fa-solid fa-pen"></i></a>
                              <a href="{{ route('classrooms.classworks.show', [$classroom->id, $classwork->id]) }}" class="btn btn-primary me-2"><i class="fa-solid fa-eye"></i></a>
                              <form action="{{ route('classrooms.classworks.destroy', [$classroom->id, $classwork->id]) }}" method="post">
                                  @csrf
                                  @method('delete')
                                  <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                              </form>
                          </div>
                      </div>
                      @endforeach
                  </div>
              </div>
          </div>
          @empty
          <p class="text-center fs-3">No Classworks Found</p>
          @endforelse
      </div>
  </div>
</x-main-layout>
