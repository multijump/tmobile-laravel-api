<nav class="navbar navbar-expand-md navbar-dark navbar-tmobile admin-navbar">
    <div class="container">
        <a
        @if(!empty(Auth::user()) && Auth::user()->hasAdminRole())
            href="{{ route('admin.home') }}"
        @else
            href="{{ route('login') }}"
        @endif
        >
            <img src="/jpg/metro.jpg" alt="T-Mobile">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                @if(Route::currentRouteName() !== 'login' && !empty(Auth::user()))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif
                @if((Route::currentRouteName() !== 'register') && Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" href="{{ route('admin.home') }}"> {{ __('Events (Home)') }} </a>
                            <a class="dropdown-item" href="{{ route('admin.users') }}"> {{ __('Users') }} </a>
                            <a class="dropdown-item" href="{{ route('admin.clients') }}"> {{ __('Clients') }} </a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id) }}"> {{ __('Edit Profile') }} </a>
                            <a class="dropdown-item" href="{{ url('/') }}/pdf/Tool_Guide.pdf"  target="_blank" rel="noopener noreferrer">Tool Guide</a>
                            <a class="dropdown-item" href="{{ url('/') }}/pdf/Sweepstakes_Rules_Combined.pdf"  target="_blank" rel="noopener noreferrer">Sweepstakes Rules</a>
                            <a class="dropdown-item" href="{{ url('/') }}/admin/reports/historical"  target="_blank" rel="noopener noreferrer">Historical Data Report</a>
                            <a class="dropdown-item" href="{{ url('/') }}/admin/reports/historicalsurvey"  target="_blank" rel="noopener noreferrer">Historical Survey Report</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Sign Out') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
            </ul>
        </div>
    </div>
</nav>
