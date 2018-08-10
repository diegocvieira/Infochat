@include('inc.header')

@if(isset($top_nav))
    @include('inc.top-nav')
@elseif(isset($top_simple))
    @include('inc.top-simple')
@endif

@yield('content')

@include('inc.footer')
