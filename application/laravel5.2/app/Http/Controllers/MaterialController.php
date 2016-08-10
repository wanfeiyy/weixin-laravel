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
use EasyWeChat\Message\Article;

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
        dd($this->material->lists('news', 0, 20));
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
        $validator = Validator::make($request->all(), [
            'media' => 'required',
            'type' => 'required|integer',
            'storaged' => 'required|integer',
            'description' => 'required',
        ]);
        $validatorResult = ValidatorResultClass::validatorHandle($validator);
        if (!$validatorResult['state']) {
            return json_encode($validatorResult);
        }
        $type = $request->input('type');
        $storaged = $request->input('storaged');
        $description = $request->input('description');
        $materail = new Material();
        switch ($type) {
            case 4:
                $data = $request->all();
                if (!isset($data['title']) || !isset($data['author']) || !isset($data['show_cover_pic']) || !isset($data['content_source_url']) || !isset($data['digest'])) {
                    return json_encode(['state'=>false,'error'=>['error_code'=>3001,'description'=>'参数错误！']]);
                }
                if  (isset($data['media'])  && is_array($data['media'])) {
                    $mediaInfo = $data['media'];
                    $articles = [];
                    foreach($mediaInfo as $k=>$v){
                        $fileInfo = $v->getFileInfo();
                        $localUpload = ImageUpload::upload($fileInfo->getPathname());
                        if ($localUpload['state']) {
                            $imgSrc = $localUpload['data']['src'];
                            $dbData = ['type'=>0,'is_long'=>0,'description'=>$data['description'][$k],'image'=>$imgSrc];
                            $returnMediaId = $materail->addMaterail($this->material, $dbData, $imgSrc, 1);
                            $mdia_id = $returnMediaId['data']['media_id'];
                            $articles[] = new Article([
                                'title'=>$data['title'][$k],
                                'author'=>$data['author'][$k],
                                'thumb_media_id'=>$mdia_id,
                                'digest'=>'',
                                'show_cover_pic'=>$data['show_cover_pic'][$k],
                                'content'=>$data['content'][$k],
                                'content_source_url'=>$data['content_source_url'][$k],
                            ]);
                        } else {
                            return json_encode(['satae' => false, 'error' => $localUpload['error']]);
                        }
                    }
                    $articleLog = ['type'=>4,'is_long'=>0,'description'=>$data['digest']];
                    $articleResult = $materail->addMaterail($this->material,$articleLog,$articles,4);
                    if ($articleResult['state']) {
                        return json_encode(['state'=>true,'data'=>$articleResult['data']]);
                    }
                }
                break;
            default:
                $data = ['type' => $type, 'is_long' => $storaged, 'description' => $description];
                $fileInfo = $request->file('media')->getFileInfo();
                if ($fileInfo->getSize() > 2 * 1024 * 1024) {
                    return ['state' => false, 'error' => ['code' => '2003', 'description' => '请不要上传大于2M的图片！']];
                }
                $localUpload = ImageUpload::upload($fileInfo->getPathname());
                if ($localUpload['state']) {
                    $imgSrc = $localUpload['data']['src'];
                    $data['image'] = $imgSrc;
                    switch ($storaged) {
                        case 0:
                            $result = $materail->addMaterail($this->material, $data, $imgSrc, $type);
                            break;
                        case 1:
                            $result = $materail->addMaterail($this->material_temporary, $data, $imgSrc, $type);
                            break;
                    }
                    return json_encode($result);
                } else {
                    return json_encode(['satae' => false, 'error' => $localUpload['error']]);
                }
                break;
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
