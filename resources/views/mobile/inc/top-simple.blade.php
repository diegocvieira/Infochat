<header class="top-nav top-simple">
    <a href="{{ isset($top_nav_home) ? url('/') : url()->previous() }}" class="link-back"></a>

    <span class="nav_title">{{ $top_nav_title }}</span>
</header>
