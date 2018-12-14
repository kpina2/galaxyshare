<?php

namespace App\Http\Controllers\admin_hotel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index(){
        $messages = \App\Message::where('required', 0)->get();
        $default_messages = \App\Message::where('required', 1)->get();
        $hotel_setup = \App\HotelSetup::find(1);
        return view("admin_hotel/messages", [
            'hotel_setup' => $hotel_setup,
            'messages' => $messages,
            'default_messages' => $default_messages
        ]);
    }
}
