<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Contact;
use App\Models\About;
use App\Models\Branch;
use App\Models\EmailConfiguration;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        $about = About::select('content')->get();
        return response()->json($about);
    }
    public function  termsAndCondition()
    {
        $termsAndCondition = TermsAndCondition::select('content')->get();
        return response()->json($termsAndCondition);
    }
    public function handleContactForm(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|max:1000',
            'subject' => 'nullable|max:256',
        ]);

        // Get admin email from settings
        $setting = EmailConfiguration::first();

        // Prepare data for mail
        $mailData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'subject' => $validated['subject'],
        ];

        // Send mail
        Mail::to($setting->email)->send(new Contact($mailData));

        // Return API response
        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent successfully'
        ]);
    }

    public function branch()
    {
        $branch = Branch::where('status', 1)->get(['id', 'name', 'location_url', 'description']);
        return response()->json($branch);
    }
}
