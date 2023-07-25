<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('style')
    <style>
      .list-group{
        top: 77px;
        right: 56px;
        display: none;
      }
      .add:hover.list-group{
        display: block;
      }

    </style>
</head>

<body>

<nav class=" navbar bg-body-tertiary shadow-sm">
  <div class="container">
    <div class="left">
      <i class="fa-solid fa-bars m-3 fs-5"></i>  
       <a class="navbar-brand fs-4">
          {{-- {{ config ('app.name' , 'Laravel')}}  --}}
          ClassroomGoogle
          
      </a>
    </div>
    <div class="right d-flex justify-content-betweeen align-items-center position-relative">
      <a class="link-secondary m-3" href="{{ route('classrooms.create')}}">
        <i class="add fa-solid fa-plus fs-5"></i>
      </a>
      {{-- <ul class="list-group position-absolute" style="width:15rem;">
        <li class="list-group-item">Create Classroom</li>
        <li class="list-group-item">Create Topic</li>
      </ul> --}}
      {{ Auth::user()->name }}
    </div>
   
    
  </div>
</nav>


<main>
  {{$slot}}   
</main>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
@endpush

</body>

</html>