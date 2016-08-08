<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Wechat;
use Validator;
use ValidatorResultClass;
use App\Libraries\ImageUpload;

class MaterialController extends Controller
{
    private  $material;

    public function __construct(Wechat $wechat)
    {
        $this->material = $wechat::material();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd(1);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd(1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
             'media'=>'required | mimes:jpeg,bmp,png',
             'type'=>'required',
         ]);
        $validatorResult = ValidatorResultClass::validatorHandle($validator);
        if(!$validatorResult['state']){
             return json_encode($validatorResult);
        }
        $fileInfo = $request->file('media')->getFileInfo();
        if ($fileInfo->getSize() > 2*1024*1024){
            return ['state'=>false,'error'=>['code'=>'2003','description'=>'请不要上传大于2M的图片！']];
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
