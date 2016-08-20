<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    const UPDATED_AT =  null;
    protected $fillable = ['type','media_id','is_long','description','image','url'];
    private $filedsMaps = [
        0=>'Image',
        1=>'Voice',
        2=>'Video',
        3=>'Thumb',
        4=>'Article',
    ];
    public function addMaterail($resource,$data,$imgSrc,$type)
    {
        try {
            $func = 'upload'.$this->filedsMaps[$type];
            $result = $resource->$func($type != 4 ? public_path().$imgSrc : $imgSrc);
            isset($result['media_id']) && $data['media_id'] = $result['media_id'];
            $data['url'] = isset($result['url']) ?  $result['url']: '';
            Material::create($data);
            return ['state'=>true,'data'=>['media_id'=>$result['media_id'],'url'=>$data['url']],'message'=>'上传成功'];
        } catch (\Exception $e) {
            return ['state'=>false,'error'=>['error_code'=>5001,'description'=>'保存media_id失败']];
        }
    }
}
