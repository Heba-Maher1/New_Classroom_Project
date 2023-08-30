<div class=" mb-4 position-relative overflow-hidden lh-lg border rounded" style="width: 18.75rem;height: 18.375rem;">
    <div class="image position-relative">
      @if('storage/{{ $image }}')
      <img class="card-img-top" src="storage/{{ $image }}" alt="Card image cap" style="height:8rem;">
      @else
      <img class="card-img-top" src="" alt="Card image cap" style="height:8rem;">
      @endif
    </div>
 


   <div class="card-body">
    <img class="rounded-circle position-absolute" style="top: 90px;left: 20px;" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" width="60" height="60">  

    <div class="content position-absolute text-white" style="top: 24px; width: 100%; {{ app()->getLocale() === 'ar' ? 'right: 20px;' : 'left: 22px;' }}">
      <h5 class="card-title">{{ $name }}</h5>
      <p class="card-text">{{ $section }} - {{ $room }}</p>
    </div>
      
      <div class="buttons d-flex justify-content-end border-top position-absolute p-1" style="bottom: 0;width:100%">
        <a href="{{ route ('classrooms.show' , $id)}}" class="btn"><i class="fa-solid fa-eye"></i></a>
        <a href="{{ route ('classrooms.edit' , $id)}}" class="btn"><i class="fa-solid fa-pen"></i></a>
        <form action="{{ route ('classrooms.destroy' , $id)}}" method="post">
          @csrf
          @method('delete')
          <button type="submit" class="btn"><i class="fa-solid fa-trash"></i></button> 
        </form>
      </div>
   </div>
</div>