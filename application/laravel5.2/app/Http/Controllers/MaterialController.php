<?php

namespace App\Http\Controllers;

use App\Material;
use EasyWeChat\Core\Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Wechat;
use Validator;
use ValidatorResultClass;
use App\Libraries\ImageUpload;

class MaterialController extends Controller
{
    private  $material_temporary;
    private  $material;
    public function __construct(Wechat $wechat)
    {
        $this->material_temporary = $wechat::material_temporary();
        $this->material = $wechat::material();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd(public_path());
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * 上传素材
     * @type 0图片，1声音，2视频，3缩略图，4图文
     * @storaged  0长期，1临时
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
             'media'=>'required | mimes:jpeg,bmp,png',
             'type'=>'required|integer',
             'storaged'=>'required|integer',
             'description'=>'required',
         ]);
        $validatorResult = ValidatorResultClass::validatorHandle($validator);
        if(!$validatorResult['state']){
             return json_encode($validatorResult);
        }
        $type = $request->input('type');
        $storaged = $request->input('storaged');
        $description = $request->input('description');
        $data = ['type'=>$type,'is_long'=>$storaged,'description'=>$description];
        $fileInfo = $request->file('media')->getFileInfo();
        if ($fileInfo->getSize() > 2*1024*1024){
            return ['state'=>false,'error'=>['code'=>'2003','description'=>'请不要上传大于2M的图片！']];
        }
        $localUpload = ImageUpload::upload($fileInfo->getPathname());
        if ($localUpload['state']) {
            $materail = new Material();
            $imgSrc = $localUpload['data']['src'];
            $data['image'] = $imgSrc;
            switch ($storaged) {
                case 0:
                    $result = $materail->addMaterail($this->material,$data,$imgSrc);
                    break;
                case 1:
                    $result = $materail->addMaterail($this->material_temporary,$data,$imgSrc);
                    break;
            }
            return json_encode($result);
        } else {
            return json_encode(['satae'=>false,'error'=>$localUpload['error']]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
