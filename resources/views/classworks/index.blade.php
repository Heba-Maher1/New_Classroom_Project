<x-main-layout :title="$classroom->name">
  <x-navbar :area="route('classrooms.show' , $classroom->id )" :ass="route('classrooms.classworks.index' , $classroom->id)" :people="route('classrooms.people' , $classroom->id)"/>
    <div class="container mt-4">

      <x-alert name="success" class="alert-success"/>
      <x-alert name="error" class="alert-danger"/>      
      <h1>{{ $classroom->name}} (#{{$classroom->id}})</h1>

        <h3 class="d-flex justify-content-between align-items-center">Classworks
             <div class="dropdown mt-4">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    + Create
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create' , [$classroom->id , 'type' => 'assignment']) }}">Assignment</a></li>
                    <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create' , [$classroom->id , 'type' => 'material']) }}">Material</a></li>
                    <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create' , [$classroom->id , 'type' => 'question']) }}">Question</a></li>
                </ul>
            </div>
        </h3>

        @forelse ($classworks as $i => $group)
        {{-- <h3>{{ $group->first()->topic->name }}</h3> --}}
        <div class="accordion accordion-flush" id="accordionFlushExample">
          @foreach ($group as $classwork)
            <div class="accordion-item">
              <h2 class="accordion-header " id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $classwork->id }}" aria-expanded="false" aria-controls="flush-collapseOne">
                  {{ $classwork->title }}
                </button>
              </h2>
              <div id="flush-collapse{{ $classwork->id}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body d-flex justify-content-between align-items-center">
                  {{ $classwork->description }}
                  <div class="buttons d-flex">
                    <a href="{{ route ('classrooms.classworks.edit' , [$classroom->id , $classwork->id])}}" class="btn"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route ('classrooms.classworks.destroy' , [$classroom->id ,$classwork->id])}}" method="post">
                      @csrf
                      @method('delete')
                      <button type="submit" class="btn"><i class="fa-solid fa-trash"></i></button> 
                    </form>
                  </div>
                </div>
              </div>
            </div>
            
          @endforeach
        @empty
            <p class="text-center fs-3">No Classworks Found</p>
        @endforelse
    </div>   

</x-main-layout>