<html>
<head>
    <title>微信开发-菜单</title>
    <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" />
</head>
<body>
    <div class="container">
        <div class="list-group">
           @foreach($songArr as $k=>$v)
                  <a href="{{$v['url']}}" class="list-group-item {{$k == 0 ? 'active':""}}">{{$v['name']}}</a>
            @endforeach
        </div>
    </div>


</body>
<script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</html>

