<?php
namespace App\Libraries;
class ImageUpload
{
    /**
     * 获取图片的基本信息
     * @param $img
     * @return array|bool
     */
    public static function info($img) {
        $image_info = getimagesize($img);
        if ($image_info === false) {
            return false;
        }
        $image_type = strtolower(substr(image_type_to_extension($image_info[2]),1));
        $image_size = filesize($img);
        $info = array(
            'width'=>$image_info[0],
            'height'=>$image_info[1],
            'type'=>$image_type,
            'size'=>$image_size,
            'mime'=>$image_info['mime']
        );
        return $info;
    }
    /**
     * 文件上传
     * @param  $tmpSrc   临时文件
     * @param array $size   要裁减的尺寸，可以多个尺寸[['width'=>100,'height'=>100],['width'=>200,'height'=>200]]
     * @return array
     */
    public static function upload ($tmpSrc,$size = []) {
        $public_path =  public_path();
        if (!file_exists($tmpSrc)) {
            return ['state'=>false,'error'=>['code'=>'2001','description'=>'参数有误！']];
        }
        $img_info = self::info($tmpSrc);
        if (!$img_info) {
            return ['state'=>false,'error'=>['code'=>'2002','description'=>'打开图片失败！']];
        }

        $uploadTime = time();

        //设置路径//创建图片存储文件夹
        $uploadSrc = '/upload/img/';
        $dir = $uploadSrc . date('Y',$uploadTime) . '/' . date('md',$uploadTime) . '/';
        $allDir = $public_path.$dir;
        $return_src = '/upload/img/' .date('Y',$uploadTime) . '/' . date('md',$uploadTime) . '/';
        if (!file_exists($allDir)) {
            mkdir($allDir, 0777, true);
        }
        $img_name = md5(uniqid().'_'.date('His',$uploadTime));     //图片名称
        $old_src = $allDir . $img_name.'.'.$img_info['type']; //原始图片

        if (copy($tmpSrc, $old_src)) {


            // 裁减
            $other_src = [];
            if (count($size)) {
                foreach ($size as $v) {
                    if (!isset($v['width']) || !$v['height'] || !is_numeric($v['width']) || !is_numeric($v['height'])) {
                        continue;
                    }
                    $tmpCropSrc = self::crop($old_src,$dir,$img_name.'_'.$v['width'].'x'.$v['height'].'.'.$img_info['type'],$v['width'],$v['height']);
                    $tmpCropSrc['state'] && $other_src[] = $tmpCropSrc['src'];
                }
            }


            return ['state'=>true,'message'=>'保存成功！','data'=>['src'=>$return_src.$img_name.'.'.$img_info['type'],'other_src'=>$other_src]];
        }
        return ['state'=>false,'error'=>['code'=>'3001','description'=>'保存失败！']];
    }

    /**
     * @param $file     要裁减的图片
     * @param $dir      要保存的路径不包括文件名（/dir/）
     * @param $file_name    要保存的文件名(name.jpg)
     * @param $width    要裁减到的宽
     * @param $height   工裁减到的长
     * @return array
     */
    static public function crop ($file,$dir,$file_name,$width,$height,$prefix=false) {
        $public_path = public_path();
        $prefix === false && $prefix = $public_path; //默认为public_path
        if (!extension_loaded('gd')) {
            return['state'=>false,'error'=>['code'=>'1001','description'=>'没有相关扩展，请通知管理员！']];
        }

        if (!file_exists($file) || !is_numeric($height) || !is_numeric($width)) {
            return['state'=>false,'error'=>['code'=>'2001','description'=>'参数有误！']];
        }

        $info  = self::info($file);
        if($info === false) return false;
        $src_width  = $info['width'];
        $src_height = $info['height'];
        $type = $info['type'];
        $type = strtolower($type);
        unset($info);

        if (!function_exists('imagecreatefrom'.($type == 'jpg' ? 'jpeg' : $type))) {
            return['state'=>false,'error'=>['code'=>'1002','description'=>'没有相关方法，请通知管理员！']];
        }
        $create_arr = self::getPercent($src_width,$src_height,$width,$height);
        $create_width = $create_arr['w'];
        $create_height = $create_arr['h'];

        $psrc_x = $psrc_y = 0;
        $createFun = 'imagecreatefrom'.($type=='jpg' ? 'jpeg' : $type);
        $srcimg = $createFun($file);
        if($type != 'gif' && function_exists('imagecreatetruecolor'))
            $thumbimg = imagecreatetruecolor($create_width, $create_height);
        else
            $thumbimg = imagecreate($create_width, $create_height);

        if(function_exists('imagecopyresampled'))
            imagecopyresampled($thumbimg, $srcimg, 0, 0, $psrc_x, $psrc_y, $width, $height, $src_width, $src_height);
        else
            imagecopyresized($thumbimg, $srcimg, 0, 0, $psrc_x, $psrc_y, $width, $height,  $src_width, $src_height);

        //裁剪
        if ($create_width <= $width && $create_height <= $height) {
            $cropped_image = $thumbimg;
        } else {
            $cropped_image = imagecreatetruecolor($width, $height);
            $crop_x = $create_width > $width ? round(($create_width - $width) / 2) : 0;
            $crop_y = $create_height > $height ? round(($create_height - $height) / 2) : 0;
            imagecopy($cropped_image, $thumbimg, 0, 0, $crop_x, $crop_y, $width, $height);
        }
        /*裁剪结束*/

        if($type=='gif' || $type=='png') {
            $background_color  =  imagecolorallocate($cropped_image,0, 255, 0);  //  指派一个绿色
            imagecolortransparent($cropped_image, $background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
        }

        if($type=='jpg' || $type=='jpeg') imageinterlace($cropped_image, 0);
        $imageFun = 'image'.($type=='jpg' ? 'jpeg' : $type);
        $imageFun($cropped_image, $prefix.$dir.$file_name);
        imagedestroy($cropped_image);
//        imagedestroy($thumbimg);
        imagedestroy($srcimg);

        return ['state'=>true,'src'=>$dir.$file_name];

    }

    /**
     * crop方法的辅助方法
     * 通过两组尺寸（图片原始大小、想要大小），来获取最终裁减的大小
     * @param int $src_width    原始图片的宽
     * @param int $src_height   原始图片的长
     * @param int $width    想要的宽
     * @param int $height   想要的长
     * @return array
     */
    private static function getPercent($src_width,$src_height,$width,$height) {

        if ($width/$height > $src_width/$src_height) {
            $w = $width;
            $h = round($src_height * $w / $src_width);
        } else {
            $h = $height;
            $w = round($h * $src_width / $src_height);
        }
        $array['w']  = $w;
        $array['h']  = $h;
        return $array;
    }


}