<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use Carbon\Carbon;
use App\LoginLog;

class LoginLogsEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_login_at = Carbon::now();
        $event->user->save();
        
        $login_log = new LoginLog;
        $login_log->created_by = Auth::id();
        $login_log->updated_by = Auth::id();
        $login_log->user_sys = \Request::ip();
        $login_log->created_at = Carbon::now();
        $login_log->updated_at = Carbon::now();
        $result = $login_log->save();
    }
}
