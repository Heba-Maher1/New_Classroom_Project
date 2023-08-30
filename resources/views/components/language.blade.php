
<li class="nav-item dropdown me-2">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Language
    </a>
    <ul class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a>
        <li>
            <hr class="dropdown-divider">
        </li>
        <a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a>
    </ul>
  </li>

{{-- 
  <div class="">
    <button type="button" class="btn bg-green text-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a>
        <a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a>
    </div>
  </div>  --}}