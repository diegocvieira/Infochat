@include('inc.header')

@if(isset($top_nav))
    @include('inc.top-nav')
@endif

@yield('content')

@include('inc.footer')
