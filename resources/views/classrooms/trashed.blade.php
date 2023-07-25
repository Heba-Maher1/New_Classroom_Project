<x-main-layout title="Trashed">
    <div class="container">   
      <h1 class="my-4">Trashed Classrooms</h1>
      <div class="row ">
        @foreach($classrooms as $classroom)
          <div class="col-md-3">
            <div class="card mb-4 " style="width:20rem; height:18rem;">
                <div class="image ">
                  <img class="card-img-top" src="{{ asset('storage/' . $classroom->cover_image_path) }}" alt="Card image cap" style="height:8rem;">
                </div>
            
               <div class="card-body">
                  <h5 class="card-title">{{ $classroom->name }}</h5>
                  <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                  <div class="buttons d-flex justify-content-between">
                    <form action="{{ route ('classrooms.restore' , $classroom->id)}}" method="post">
                      @csrf
                      @method('put')
                      <button type="submit" class="btn"><i class="fa-solid fa-trash-can-arrow-up"></i></button> 
                    </form>
                    <form action="{{ route ('classrooms.force-delete' , $classroom->id)}}" method="post">
                      @csrf
                      @method('delete')
                      <button type="submit" class="btn"><i class="fa-solid fa-trash"></i></button> 
                    </form>
                  </div>
               </div>
            </div>          
        </div>
        @endforeach
      </div>
    </div>
    </x-main-layout>
    