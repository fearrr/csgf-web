<?php

namespace App\Http\Controllers;

use Auth;
use LRedis;
use App\User;
use App\CCentrifugo;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public $user;
    public $redis;
    public $title;

    public function __construct()
    {
        $this->setTitle('Title not stated');
        if(Auth::check()){
            $this->user = Auth::user();
        } else {
            $this->user = User::find(1);
        }
        view()->share('u', $this->user);
        $time = time();
        $token = CCentrifugo::generateClientToken($this->user->steamid64, $time, '');
        view()->share('ctoken', $token);
        view()->share('ctime', $time);
        $this->redis = LRedis::connection();
        view()->share('steam_status', $this->getSteamStatus());
        $this->redis->publish('new_user', $this->user->steamid64);
    }

    public function  __destruct()
    {
        $this->redis->disconnect();
    }

    public function setTitle($title)
    {
        $this->title = $title;
        view()->share('title', $this->title);
    }

    public function getSteamStatus()
    {
        $inventoryStatus = $this->redis->get('steam.inventory.status');
        $communityStatus = $this->redis->get('steam.community.status');

        if($inventoryStatus == 'normal' && $communityStatus == 'normal') return 'good';
        if($inventoryStatus == 'normal' && $communityStatus == 'delayed') return 'normal';
        if($inventoryStatus == 'critical' || $communityStatus == 'critical') return 'bad';
        return 'good';
    }
}
