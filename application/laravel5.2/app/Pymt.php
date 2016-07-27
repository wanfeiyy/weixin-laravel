<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pymt extends Model
{
    protected $fillable = ['name','pic','comment','url'];

    public function addMt($data)
    {
        $dbData['name'] = $data['saleName'];
        $dbData['url'] = $data['saleUrl'];
        $dbData['pic'] = $data['saleSrc'];
        $dbData['comment'] = $data['saleComment'];
        return $this->create($dbData);
    }
}
