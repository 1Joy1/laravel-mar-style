<?php

namespace App\Http\Controllers\Admin;

use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return view('admin_message', ['messages' => $messages]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|max:50',
            'text' => 'bail|required',
        ]);

        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);
        }

        $message->name = $request->name;
        $message->text = $request->text;

        $message->save();

        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $message->delete();

        $jsonResponse = ['message' => [__('Delete completed.')] ];

        return $jsonResponse;
    }
}
