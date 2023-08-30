<!DOCTYPE html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">   

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @if(App::isLocale('ar'))
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.rtl.min.css" integrity="sha384-PRrgQVJ8NNHGieOA1grGdCTIt4h21CzJs6SnWH4YMQ6G5F5+IEzOHz67L4SQaF0o" crossorigin="anonymous">
    @else
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}


    @stack('styles')
    <style>
     
      html{
        font-family: Figtree,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,"Apple Color Emoji","Segoe UI Emoji",Segoe UI Symbol,"Noto Color Emoji";
      }

      .list-group{
        top: 77px;
        right: 56px;
        display: none;
      }
      .add:hover.list-group{
        display: block;
      }
      .bg-green{
        text:#fff;
        background-color: #00736c;
      }
      .bg-green:hover{
        background-color: #009288;
      }
    </style>
</head>
<body>
  <nav class="container navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="{{ route('classrooms.index')}}">
        <x-application-logo /> 
      </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarSupportedContent">
      <div>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active me-2">
            <a class="nav-link" href="{{ route('classrooms.index') }}">Classrooms <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link" href="{{ route('classrooms.create') }}">Create Classroom</a>
          </li>
          <x-user-notification-menu count="5" />
          <x-language />
        </ul>
      </div>
      <div class="d-flex align-items-center flex-end">
        <div class="input-group align-items-center  m-3">
          <div class="input-group-prepend">
            <span class="search-icon" id="searchIcon">
              <i class="fas fa-search me-1" style="color:#00736c"></i>
            </span>
          </div>
          <input type="text" class="form-control d-none rounded" id="searchInput" placeholder="Search">
        </div>
        <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" width="40" height="40"> 
      </div>
      

    </div>
  </nav>
  {{-- <nav class="navbar shadow-sm">
          {{ config ('app.name' , 'Laravel')}} 
          <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('classrooms.index')}}">
              <x-application-logo /> 
            </a>
            <div class="d-flex align-items-center">

              <div class="">
                <button type="button" class="btn bg-green text-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a>
                    <a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a>
                </div>
              </div>    
              <x-user-notification-menu count="5" />

                <div class="input-group align-items-center  m-3">
                  <div class="input-group-prepend">
                    <span class="search-icon" id="searchIcon">
                      <i class="fas fa-search me-1" style="color:#00736c"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control d-none rounded" id="searchInput" placeholder="Search">
                </div>

              <a class="link-secondary m-3" href="{{ route('classrooms.create')}}">
                <i class="add fa-solid fa-plus fs-5" style="color:#00736c"></i>
              </a> 
              <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" width="40" height="40"> 
              
            </div>
            </div>  
          </div>
</nav> --}}


 

<main>
  {{$slot}}   
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
  $(document).ready(function() {
    $("#searchIcon").click(function() {
      $("#searchInput").toggleClass("d-none");
    });
  });
  </script>

<script>
    var classroomId;
    const userId = "{{ Auth::id() }}"
</script> 

@stack('scripts')
@vite(['resources/js/app.js'])




</body>

</html>