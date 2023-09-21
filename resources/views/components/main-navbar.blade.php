<nav class="container-fluid navbar navbar-expand-lg navbar-light bg-light px-5 py-0 border d-flex justify-content-between">
  <div class="d-flex align-items-center">

    <button class="btn btn-link" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      <i class="fas fa-bars main-color"></i>
    </button>
    <x-application-logo />
    <p class="main-color m-0 fs-3">Classroom</p>
  </div>

  <div class="d-flex align-items-center">
    <div class="input-group align-items-center m-3">
      <div class="input-group-prepend">
        <span class="search-icon" id="searchIcon">
          <i class="fas fa-search main-color me-1"></i>
        </span>
      </div>
      <input type="text" class="form-control d-none rounded" id="searchInput" placeholder="Search">
    </div>
    <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" width="40" height="40"> 
  </div>
</nav>