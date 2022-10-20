<nav class="navbar navbar-expand-md navbar-light navbar-tmobile guest-navbar">
    <div class="container">

        @auth
            <a href="{{ route('login') }}">
                <img src="/jpg/metro.jpg" id="nav-logo">
            </a>
        @else
            <img src="/jpg/metro.jpg" alt="T-Mobile" id="nav-logo">
        @endauth

    </div>
</nav>
