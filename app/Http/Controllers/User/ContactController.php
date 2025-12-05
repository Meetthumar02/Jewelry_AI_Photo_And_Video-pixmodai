<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return view('user.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'nullable',
            'subject'=>'nullable',
            'how_can_we_help'=>'required',
            'message'=>'required'
        ]);

        Contact::create([
            'user_id'=>Auth::id(),
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'subject'=>$request->subject,
            'how_can_we_help'=>$request->how_can_we_help,
            'message'=>$request->message
        ]);

        return back()->with('success','Your request has been submitted successfully!');
    }
}
