<?php

namespace App\Jobs;

use File;
use Image;
use Storage;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadImage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $channel;

    public $fileId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Channel $channel, $fileId)
    {
        $this->channel = $channel;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName = $this->fileId;
        $path = storage_path() . '/uploads/' . $fileName;

        Image::make($path)->encode('png')->fit(40, 40, function ($c) {
            $c->upsize();
        })->save();

        $this->channel->image_filename = $fileName;
        $this->channel->save();
    }
}
