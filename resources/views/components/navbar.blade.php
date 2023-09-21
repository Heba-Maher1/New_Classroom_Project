<nav class="navbar navbar-expand-lg shadow-sm border-bottom">
    <div class="container " style="height: 3rem">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{$area == url()->current() ? 'active' : ''}}" href="{{$area}}" style="padding-top:20px;padding-bottom:20px;">{{__("Participation Area")}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$ass == url()->current() ? 'active' : ''}}" href="{{$ass}}" style="padding-top:20px;padding-bottom:20px;">{{__("Classworks")}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$people == url()->current() ? 'active' : ''}}" href="{{$people}}" style="padding-top:20px;padding-bottom:20px;">{{__("People")}}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background-color: #f8f9fa; 
    }

    .navbar-toggler-icon {
        background-color: #00736c; 
    }

    .navbar-nav .nav-item {
        margin: 0 15px;
    }

    .navbar-nav .nav-link {
        color: #000;
        transition: color 0.3s;
    }

    .navbar-nav .nav-link:hover {
        color: #00736c;
    }

    .navbar-nav .nav-link.active {
        color: #00736c;
        border-bottom: 2px solid #00736c; 
        font-weight: bold;
    }
</style>
