<div class="card mb-4 " style="width:20rem; height:18rem;">
    <div class="image ">
      <img class="card-img-top" src="storage/{{ $image }}" alt="Card image cap" style="height:8rem;">
    </div>

   <div class="card-body">
      <h5 class="card-title">{{ $name }}</h5>
      <p class="card-text">{{ $section }} - {{ $room }}</p>
      <div class="buttons d-flex justify-content-between">
        <a href="{{ route ('classrooms.show' , $id)}}" class="btn"><i class="fa-solid fa-eye"></i></a>
        <a href="{{ route ('classrooms.edit' , $id)}}" class="btn"><i class="fa-solid fa-pen"></i></a>
        <form action="{{ route ('classrooms.destroy' , $id)}}" method="post">
          @csrf
          @method('delete')
          <button type="submit" class="btn"><i class="fa-solid fa-trash"></i></button> 
        </form>
        {{-- <form action="{{ route ('topics.create' , $id)}}" method="post">
          @csrf
          @method('get')
          <button type="submit" class="btn"><i class="fa-solid fa-plus"></i></button>
        </form> --}}
      </div>
   </div>
</div>