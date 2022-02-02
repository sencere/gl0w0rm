<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Http\Requests;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function show(Channel $channel)
    {
        return view('channel.show', [
            'channel' => $channel,
        ]);
    }
}
