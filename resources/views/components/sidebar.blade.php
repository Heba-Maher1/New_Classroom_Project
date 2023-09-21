<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-blocks sidebar border-end ps-5" style="height: 100vh">
    <ul class="navbar-nav mr-auto my-4">
        <li class="nav-item sidebar-item d-flex align-items-center active mb-2">
          <i class="fa-solid fa-book-open main-color me-3"></i>
          <a class="nav-link" href="{{ route('classrooms.index') }}">Classrooms <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item sidebar-item d-flex align-items-center mb-2">
          <i class="fa-solid fa-plus main-color me-3"></i>
          <a class="nav-link" href="{{ route('classrooms.create') }}">Create Classroom</a>
        </li>
        {{-- <li class="nav-item sidebar-item d-flex align-items-center mb-2">
          <x-user-notification-menu count="5" />
        </li>
        <li class="nav-item mb-2">
            <x-language />
        </li> --}}
        <li class="nav-item sidebar-item d-flex align-items-center my-2">
          <i class="fa-solid fa-user main-color me-2"></i>
          <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
        </li>
        
        <li class="nav-item sidebar-item d-flex align-items-center my-2">
          <i class="fa-solid fa-trash main-color me-2"></i>
          <a href="{{ route('classrooms.trashed')}}" class="nav-link" >{{__('trashed classrooms')}}</a>
        </li>
        <li class="nav-item sidebar-item d-flex align-items-center my-2">
          <i class="fa-solid fa-trash main-color me-2"></i>
          <a href="{{ route('topics.trashed')}}" class="nav-link"  >{{__('trashed topics')}}</a>
        </li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <li class="nav-item sidebar-item d-flex align-items-center my-2">
            <i class="fa-solid fa-user main-color me-2"></i>
            <button type="submit" class="nav-link" href="{{ route('logout') }}">Logout</button>
          </li>
        </form>
       
      </ul>
</nav>