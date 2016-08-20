<html>
<head>
    <title>微信开发-菜单</title>
    <link rel="stylesheet" href="{{ URL::asset('assets') }}/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <style>
        .list-group-item{padding: 55px 25px; font-size: 25px;}
        .active{background-color: #66ccff !important;}
        .bofang{background:url("{{ URL::asset('') }}/upload/163/bofang.png") no-repeat 8px -123px;}
        .list-group-item div{float: right;width: 27px; height: 28px; position: relative;top:-4px; cursor: pointer}
        .list-group-item p{width: 80%;float: left;position: relative;top:-4px;}
        iframe{width: 100%; display: none; height: 20%;}
    </style>
</head>
<body>
        <div class="list-group" >
           @foreach($songArr as $k=>$v)
                    <div class="list-group-item {{$k == 0 ? 'active':""}}"><p>{{$v['name']}}</p>  <div url="{{$v['url']}}" class="bofang"></div> </div>
            @endforeach
        </div>
        <iframe frameborder="no" border="0" marginwidth="0" marginheight="0"  height=86 src=""></iframe>

</body>
<script src="{{ URL::asset('assets') }}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ URL::asset('assets') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
    $('.bofang').click(function(){
       $('.list-group > div').removeClass('active');
       $(this).parent().addClass('active');
       var url = $(this).attr('url');
        $('iframe').css('display','block');
        $('iframe').attr('src',url);
    })

</script>
</html>

