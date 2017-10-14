<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();
        return view('message', ['messages'=> $messages,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|max:50',
            'email' => 'bail|required|email|max:50',
            'message' => 'bail|required',
        ]);

        if ($validator->fails()) {

            return view('fail_form_answer')->withErrors($validator);
        }

        $message = new Message;
        $message->name = $request->name;
        $message->email = $request->email;
        $message->name = $request->name;
        $message->text = $request->message;
        $message->ip = $request->ip();

        $message->save();

        return view('succes_form_answer');
    }

}


