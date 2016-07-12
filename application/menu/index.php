<?php
    include('../../wlight/develop/Library.class.php');
	$menuDesigner = \wlight\dev\Library::import('menu', 'MenuDesigner');
    $menu = \wlight\dev\Library::import('menu', 'Menu');
//    $menuDesigner->addView('网易云音乐','http://music.163.com');
//    $menuDesigner->addClick('今日歌曲', 'V1001_TODAY_MUSIC');
//    $menuDesigner->addLocation('我在哪', 'V_TODAY_LOCATION');
//    $result = $menu->create($menuDesigner->getMenu());
    var_dump(json_encode($menu->get()));
