<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{$area == url()->current() ? 'active' : ''}}" href="{{$area}}">Participation Area</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$ass == url()->current() ? 'active' : ''}}" href="{{$ass}}">Classworks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$people == url()->current() ? 'active' : ''}}" href="{{$people}}">People</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background-color: #f8f9fa; /* Light gray background color */
    }

    .navbar-toggler-icon {
        background-color: #007bff; /* Toggler icon color */
    }

    .navbar-nav .nav-item {
        margin: 0 15px; /* Spacing between nav items */
    }

    .navbar-nav .nav-link {
        color: #333; /* Nav item text color */
        transition: color 0.3s;
    }

    .navbar-nav .nav-link:hover {
        color: #007bff; /* Nav item text color on hover */
    }

    .navbar-nav .nav-link.active {
        color: #007bff; /* Active nav item text color */
        border-bottom: 2px solid #007bff; /* Underline for active link */
    }
</style>
