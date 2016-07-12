
<html>
    <head>
        <title>微信开发 - @yield('title')</title>
</head>
<body>
@section('sidebar')
    这是主要的侧边栏。
@show
<div class="container">
    @yield('content')
</div>
</body>
</html>