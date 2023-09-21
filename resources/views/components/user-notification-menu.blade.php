
<li class="nav-item sidebar-item  dropdown d-flex align-items-center me-2">
    <i class="fa-solid fa-bell main-color me-3"></i>

    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Notifications ({{ $unreadCount }})
    </a>
    <ul class="dropdown-menu">
        @foreach($notifications as $notification)
        <li><a href="{{ $notification->data['link'] }}??nid={{ $notification->id }}" class="dropdown-item">
            @if($notification->unread())  <b style="color: #00736c">*</b>@endif
            {{ $notification->data['body'] }}
            <br>
            <small class="text-muted">{{$notification->created_at->diffForHumans()}}</small>
        </a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @endforeach
    </ul>
  </li>
