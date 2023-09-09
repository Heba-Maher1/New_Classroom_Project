<x-main-layout title="{{__('Classrooms')}}">
    <div class="container">
      
      <x-alert name="success" class="alert-success mt-4"/>
      <x-alert name="error" class="alert-danger"/>

      <ul id="classrooms">
      </ul>

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

      @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
          axios.get('/api/V1/classrooms')
            .then(function (response) {
              let ul = document.getElementById('classrooms');
              for (let i in response.data) {
                ul.innerHTML += `<li>${response.data[i].name}</li>`;
              }
            })
            .catch(function (error) {
              console.error('Error:', error);
            });
        </script>
        @endpush
</x-main-layout>