<?php
namespace App\Http\Controllers;
include('../../../wlight/develop/Library.class.php');
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;

use App\Http\Requests;

class MenuController extends Controller
{
     public function index()
     {
         $url = 'http://music.163.com/discover/toplist?id=19723756';
         $link_url = ' http://music.163.com/outchain/player?type=2&id=';
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_HEADER, 0);
         $output = curl_exec($ch);
         curl_close($ch);
         $dom = HtmlDomParser::str_get_html($output);
         $songArr = [];
         foreach($dom->find('#song-list-pre-cache .f-hide a') as $k=>$v){
             preg_match('/.*?id=([0-9]+)/',$v->href,$res);
             $songArr[]  = ['url'=>$link_url.$res[1],'name'=>strip_tags ((string) $v)];
             if(count($songArr) >=10){
                 break;
             }
         }
         return view('menu.index',['songArr'=>$songArr]);
     }

     public function store()
     {
         $menu = \wlight\dev\Library::import('menu', 'Menu');
         $menuDesigner = \wlight\dev\Library::import('menu', 'MenuDesigner');
         $menuDesigner->addView('网易云音乐', url('menu'));
         $menuDesigner->addLocation('LBS查询', 'LBS_SEARCH');
         $menuDesigner->addView('Esay soso','https://www.baidu.com');
         //$menuDesigner->addSubButton('网易云音乐',$menuDesigner->addLocation('LBS查询', 'LBS_SEARCH'));
        $menu->create($menuDesigner->getMenu());
     }



}
