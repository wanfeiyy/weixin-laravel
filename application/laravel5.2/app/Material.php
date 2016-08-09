<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    const UPDATED_AT =  null;
    protected $fillable = ['type','media_id','is_long','description','image','url'];

    public function addMaterail($resource,$data,$imgSrc)
    {
        try {
            $result = $resource->uploadImage(public_path().$imgSrc);
            isset($result['media_id']) && $data['media_id'] = $result['media_id'];
            $data['url'] = isset($result['url']) ?  $result['url']: '';
            Material::create($data);
            return ['state'=>true,'data'=>['media_id'=>$result['media_id'],'url'=>$data['url']],'message'=>'上传成功'];
        } catch (\Exception $e) {
            return ['state'=>false,'error'=>['error_code'=>5001,'description'=>'保存media_id失败']];
        }
    }
}
