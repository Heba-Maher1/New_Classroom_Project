<x-main-layout :title="$classroom->name">
  <x-navbar :area="route('classrooms.show', $classroom->id)" :ass="route('classrooms.classworks.index', $classroom->id)" :people="route('classrooms.people', $classroom->id)" />
  <div class="container mt-4">

      <x-alert name="success" class="alert-success" />
      <x-alert name="error" class="alert-danger" />

      <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Classworks</h2>
          {{-- @can('classworks.create' , [$classroom]) --}}
          @can('create' , ['App\Models\Classwork', $classroom])
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
          @endcan
      </div>

      <div class="col-12 text-center my-4">
        <form action="{{ URL::current() }}" method="get">
          <div class="input-group mb-3">
            <input
              type="text"
              class="form-control border shadow-sm p-3 rounded"
              placeholder="search"
              aria-label="search"
              aria-describedby="button-addon2"
              name="search"
            />
            <button class="btn text-color" type="button" id="button-addon2" data-mdb-ripple-color="dark" style="position: absolute;right: 0;top: 11px;">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
        </form>
      </div>

      

    {{-- @forelse($classworks as $group) --}}
    {{-- <h3 class="mt-4 text-success">{{ $group->first()->topic->name }}</h3> --}}
    <div class="accordion accordion-flush" id="accordionFlushxample">
        @foreach($classworks as $classwork)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{$classwork->id}}" aria-expanded="false" aria-controls="flush-collapseThree">
                    {{$classwork->title}}
                </button>
            </h2>
            <div id="flush-collapse{{$classwork->id}}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body text-secondary d-flex justify-content-between">
                    {{-- <h6>{!! $classwork->description !!}</h6> --}}
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
            </div>
        </div>
        @endforeach

    </div>
    {{-- @empty
    <p class="text-center fs-4 text-success">No Classworks Found.</p>
    @endforelse --}}
    {{-- {{ $classworks->links() }} --}}

  </div>

  @push('scripts')
    <script>
        classroomId = "{{$classwork->classroom_id}}";
    </script>
  @endpush


</x-main-layout>
