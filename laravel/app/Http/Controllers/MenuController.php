<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
use Validator;
use ValidatorResultClass;
use App\Http\Requests;
use App\Libraries\MenuDesigner;
use EasyWeChat\Core\Exceptions\HttpException;
class MenuController extends Controller
{
    private $menuDesigner;
    private $menu;
    public function __construct(MenuDesigner $menuDesigner)
    {
        $this->menu = app('wechat')->menu;
        $this->menuDesigner = $menuDesigner;
    }
     public function index()
     {
         try{
             $menu = $this->menu->all();
         }catch(HttpException $e){
             return json_encode(['state'=>false,'erroe'=>['erroe_coe'=>$e->getCode(),'description'=>$e->getMessage()]]);
         }
         return json_encode(['state'=>true,'data'=>$menu['menu']]);
     }

    /**
     * 添加菜单 type 1为SubButton，2为click，3为view，4为location
     * @param Request $request
     * @return string
     */
     public function store(Request $request)
     {
//         $validator = Validator::make($request->all(),[
//             'name' => 'required',
//             'type' => 'required | integer',
//             'url'  => 'sometimes | required | url',//当有url字段才验证
//             'key'  => 'sometimes | required ',
//
//         ]);
//         $validatorResult = ValidatorResultClass::validatorHandle($validator);
//         if(!$validatorResult['state']){
//             return json_encode($validatorResult);
//         }

         $data = $request->all()['menu'];
         $menus = $this->handleMenu($data);
         $result = $this->menu->add($menus);
          if($result){
              return json_encode(['state'=>true,'data'=>[],'message'=>'success message']);
          }else{
              return json_encode(['state'=>false,'error_code'=>$result['errcode'],'error'=>$result['errmsg']]);
          }
     }

    public function delete()
    {
        if($this->menu->destroy()){
            return json_encode(['state'=>true,'data'=>[],'message'=>'success message']);
        }
        return json_encode(['state'=>false,'error_code'=>5001,'error'=>"删除失败"]);

    }

    private function handleMenu($data)
    {
        $menuDesigner = $this->menuDesigner;
        $oneMenu = [];
        foreach($data as $k=>$v){
            switch ($v['type']){
                case 1 :
                    $twoMenu = [];
                    foreach($v['sub_button'] as $kk=>$vv){
                        $func = $this->fieldsMap($vv['type']);
                        $twoMenu[] = $menuDesigner->$func($vv['name'],$vv['url']);
                    }
                    $oneMenu[] = $menuDesigner->addSubButton($v['name'],$twoMenu);
                    break;
                case 2 :
                    $oneMenu[] = $menuDesigner->addClick($v['name'],$v['key']);
                    break;
                case 3 :
                    $oneMenu[] = $menuDesigner->addView($v['name'],$v['url']);
                    break;
                case 4 :
                    $oneMenu[] = $menuDesigner->addLocation($v['name'],$v['key']);
                    break;
            }

        }
        return $oneMenu;

    }
    private function fieldsMap($type){
        $maps = [
            '1'=>'addSubButton',
            '2'=>'addClick',
            '3'=>'addView',
            '4'=>'addLocation',
        ];
        return $maps[$type];
    }

}
