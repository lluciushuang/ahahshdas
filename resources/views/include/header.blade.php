<nav class="navbar navbar-expand-lg navbar-dark py-3 px-md-4" style="background: linear-gradient(135deg,#00398e,#3578db);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 tracking-tight" href="#">ISB Commerce Mantap Jiwa Gacor IZINN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('store') ? 'active' : '' }}" href="{{ route('store') }}">Shop Here</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                </li>
            </ul>
            <div class="d-flex text-white align-items-center">
                <a href="#" class="text-white me-3 fs-5"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-3 fs-5"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-3 fs-5"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</nav>