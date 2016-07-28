<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Sunra\PhpSimple\HtmlDomParser;

class PyMt extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected  $url;
    protected $maps = [
        'cd'=>'成都',
        'bj'=>'北京',
    ];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->url = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = file_get_contents($this->url);
        $reg = '/http:\/\/([a-z]+)\..*\/category\/([a-z]+)\//';
        preg_match($reg,$this->url,$re);
        $city = isset($re[1]) ? $this->maps[$re[1]]:'unknown';
        $type = isset($re[2]) ? $re[2]:'unknown';
        $dom = HtmlDomParser::str_get_html($data);
        $picArr = [];
        $mt = new \App\Pymt();
        if(!count($dom->find('.poi-tile-nodeal'))){
            return false;
        }
        foreach ($dom->find('.poi-tile-nodeal') as $k => $v) {
            $saleSrc = $v->find('img') ?  $v->find('img')[0]->attr['src']:"";
            $saleName = $v->find('.poi-tile__info .f3') ? strip_tags((string)$v->find('.poi-tile__info .f3')[0]) : '';
            $saleUrl = $v->find('.poi-tile__head') ? $v->find('.poi-tile__head')[0]->attr['href'] : '';
            $saleComment = $v->find('.rate-stars') ? $v->find('.rate-stars')[0]->attr['style']:'';
            $queueData = [
                'saleSrc' => $saleSrc,
                'saleComment' => $saleComment,
                'saleName' => $saleName,
                'saleUrl' => $saleUrl,
                'saleType'=>$type,
                'saleCity'=>$city,
            ];
            Log::info(json_encode($queueData));
            $mt->addMt($queueData);
        }
    }
}
