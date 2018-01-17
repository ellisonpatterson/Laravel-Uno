<?php

namespace App\Engine;

use Illuminate\Support\Facades\Session;

class HelperEngine
{
    public function fetchSessionMessages(&$message = false, &$messageType = false)
    {
        $message = $messageType = false;

        if (Session::has('errors')) {
            $message = Session::get('errors')->all();
            $messageType = 'alert';
        } elseif (session()->get('flash_success')) {
            $message = session()->get('flash_success');
            $messageType = 'success';
        } elseif (session()->get('flash_warning')) {
            $message = session()->get('flash_warning');
            $messageType = 'warning';
        } elseif (session()->get('flash_info')) {
            $message = session()->get('flash_info');
            $messageType = 'info';
        } elseif (session()->get('flash_danger')) {
            $message = session()->get('flash_danger');
            $messageType = 'danger';
        } elseif (session()->get('flash_message')) {
            $message = session()->get('flash_message');
            $messageType = 'message';
        }

        return [$message, $messageType];
    }
}