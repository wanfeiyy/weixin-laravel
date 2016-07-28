<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pymt extends Model
{
    protected $fillable = ['name','pic','comment','url','type','city'];

    public function addMt($data)
    {
        $dbData['name'] = $data['saleName'];
        $dbData['url'] = $data['saleUrl'];
        $dbData['pic'] = $data['saleSrc'];
        $dbData['comment'] = $data['saleComment'];
        $dbData['type'] = $data['saleType'];
        $dbData['city'] = $data['saleCity'];
        return $this->create($dbData);
    }
}
