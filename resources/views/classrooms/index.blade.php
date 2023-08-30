<x-main-layout title="{{__('Classrooms')}}">
    <div class="container">
      
      <x-alert name="success" class="alert-success mt-4"/>
      <x-alert name="error" class="alert-danger"/>

      {{-- {!!__('pagination.next')!!} --}}
            
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="my-4">{{__('Classrooms')}}</h1> 
        <div>
          <a href="{{ route('classrooms.trashed')}}" class="btn bg-green text-white me-3" >{{__('trashed classrooms')}}</a>
          <a href="{{ route('topics.trashed')}}" class="btn bg-green text-white me-3"  >{{__('trashed topics')}}</a>
        </div>
      </div>
        <div class="row">
          @foreach($classrooms as $classroom)
            <div class="col-md-3">
             <x-card :image="$classroom->cover_image_path" :name="$classroom->name" :section="$classroom->section" :room="$classroom->room" :id="$classroom->id"/>  
            </div>
          @endforeach
        </div>
      </div>
</x-main-layout>