<?php

namespace App\Http\Controllers;

use App\Mail\NewContact;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function SendEmail(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'name' => ['required', 'max:40'],
                'email' => ['required', 'max:60'],
                'message' => ['required'],
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'success' => true,
                'errors' => $validate->errors(),
            ]);
        }
        $new_lead = Lead::create($data);

        Mail::to('mariosantoro124@gmail.com')->send(new NewContact($new_lead));
        return response()->json([
            'success' => true,
        ]);
    }
}
