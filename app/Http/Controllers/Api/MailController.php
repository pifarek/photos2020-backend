<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Message;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'message' => ['required'],
        ]);

        \Mail::to('marcinpiwarski@gmail.com')->send(new Message($request->input('name'), $request->input('email'), $request->input('message')));

        return response()->json(['status' => 'ok']);
    }
}
