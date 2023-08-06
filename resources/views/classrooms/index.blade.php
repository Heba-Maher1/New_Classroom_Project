<x-main-layout title="Classrooms">
    <div class="container">
      
      <x-alert name="success" class="alert-success"/>
      <x-alert name="error" class="alert-danger"/>
            
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="my-4">Classrooms</h1> 
        <div>
          <a class="me-3" href="{{ route('classrooms.trashed')}}">trashed classrooms</a>
          <a href="{{ route('topics.trashed')}}">trashed topics</a>
        </div>
      </div>
      <x-alert name="success" id="success" class="alert-success" />
        <div class="row ">
          @foreach($classrooms as $classroom)
            <div class="col-md-3">
             <x-card :image="$classroom->cover_image_path" :name="$classroom->name" :section="$classroom->section" :room="$classroom->room" :id="$classroom->id"/>  
              
            </div>
          @endforeach
        </div>
      </div>
</x-main-layout>