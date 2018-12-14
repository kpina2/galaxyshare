<?php

namespace App\Http\Controllers\admin_dms\hotel_controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HotelSetup;

class MessageController extends Controller
{

    public function index(){
        $messages = \App\Message::where('required', 0)->get();
        $default_messages = \App\Message::where('required', 1)->get();
        $hotel_setup = HotelSetup::find(1);
        return view("admin_dms/messages", [
            'hotel_setup' => $hotel_setup,
            'messages' => $messages,
            'default_messages' => $default_messages
        ]);
    }
    
    public function validate_form($form_data, $update = true){
        $rules = [
            'sort_order' => "required"
        ];
        if(!$update){
            $rules['message'] ='required|unique:mysql2.message';
        }else{
             $rules['message'] ='required';
        }
        
        $validator = \Validator::make($form_data, $rules);
       
        if ($validator->passes()) {
            return true;
        } else {
            return $validator->errors()->all();
        }
    }
    
    public function update(Request $request){
        $input = $request->all();
        $form_data = json_decode($input['form_data'], true);
        $response = new \stdClass;
        $response->success = false;
        
        $valid = $this->validate_form($form_data);
        
        if($valid === true){
            $message = \App\Message::find($form_data['id']);
            $message->update($form_data);
            $response->message = "Successfully Updated Message";
            $response->data = $message;
            $response->success = true;
        }else{
            $response->message = "Errors";
            $response->data = $valid;
        }
        
        
        return response()->json($response);
    }
    
    public function add_new(Request $request){
        $input = $request->all();
        $form_data = json_decode($input['form_data'], true);
        $response = new \stdClass;
        $response->success = false;
        
        $valid = $this->validate_form($form_data);
        
        if($valid === true){
            $new_message = \App\Message::create([
                'message' => $form_data['message'],
                'sku' => $form_data['sku'],
                'sort_order' => $form_data['sort_order'],
                'active' => 1
            ]);

            $response->message = "Successfully Created New Message";
            $response->data = $new_message;
            $response->success = true;
        }else{
            $response->message = "Errors";
            $response->data = $valid;
        }
        return response()->json($response);
    }
    
    public function batch_sort_update(Request $request){
        $input = $request->all();
        $form_data = json_decode($input['form_data'], true);
        $response = new \stdClass;
        $response->success = false;
        
        $saved_messgage = [];
        foreach($form_data as $message){
            // TODO: Rewrite to update all floors with one query 
            $message_update = \App\Message::find($message['id']);
            $message_update->sort_order = $message['sort_order'];
            $message_update->save();
            array_push($saved_messgage, $message_update);
        }
        
        $saved_message_count = count($saved_messgage);
        $response->message = "Successfully sorted messages";
        $response->data = $saved_messgage;
        $response->success = true;
        return response()->json($response);
    }
}
