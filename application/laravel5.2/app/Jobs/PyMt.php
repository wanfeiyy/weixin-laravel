<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sunra\PhpSimple\HtmlDomParser;

class PyMt extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected  $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $dom = HtmlDomParser::str_get_html($this->data);
        $picArr = [];
        $mt = new \App\Pymt();
        foreach ($dom->find('.poi-tile-nodeal') as $k => $v) {
            $saleSrc = $v->find('img')[0]->attr['src'];
            $saleName = strip_tags((string)$v->find('.poi-tile__info .f3')[0]);
            $saleUrl = $v->find('.poi-tile__head')[0]->attr['href'];
            $saleComment = $v->find('.rate-stars')[0]->attr['style'];
            $queueData = ['saleSrc' => $saleSrc, 'saleComment' => $saleComment, 'saleName' => $saleName, 'saleUrl' => $saleUrl];
            $mt->addMt($queueData);
        }
    }
}
