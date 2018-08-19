@include('mobile.inc.header')

@if(isset($top_nav))
    @include('mobile.inc.top-nav')
@elseif(isset($top_simple))
    @include('mobile.inc.top-simple')
@endif

@yield('content')

@include('mobile.inc.footer')
