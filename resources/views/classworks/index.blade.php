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

      <form action="{{ URL::current() }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">
        <div class="col-12">
            <input type="text" placeholder="search" name="search" id="form-control">
        </div>
        <div class="col-12">
           <button class="btn btn-danger ms-2" type="submit">Find</button>
        </div>
    </form>

      

    {{-- @forelse($classworks as $group)
    <h3 class="mt-4 text-success">{{ $group->first()->topic->name }}</h3> --}}

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
                    <h6>{{$classwork->description}}</h6>
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
    {{ $classworks->links() }}
  </div>

</x-main-layout>
