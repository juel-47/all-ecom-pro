<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Contact;
use App\Models\About;
use App\Models\Branch;
use App\Models\EmailConfiguration;
use App\Models\FooterInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PageController extends Controller
{
    public function about()
    {
        $about = About::select('content')->get();
        return Inertia::render('About', [
            'aboutContent' => $about,
        ]);
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

        return to_route('contact')->with('success', 'Your message has been sent successfully');
    }

    // public function branch()
    // {
    //     $branch = Branch::where('status', 1)->get(['id', 'name', 'location_url', 'description']);
    //     $footer = Cache::remember('footer_data', 3600, function () {
    //         return FooterInfo::select('logo', 'phone', 'email', 'address', 'copyright')
    //             ->get()
    //             ->map(function ($item) {
    //                 return $item->only(['logo', 'phone', 'email', 'address', 'copyright']);
    //             })->first();
    //     });
    //     return Inertia::render('ContactPage', [
    //         'branches' => $branch,
    //         'footer_info' => $footer
    //     ]);
    // }

    public function locateStore()
    {
        $branches = Branch::where('status', 1)->get(['id', 'name', 'image', 'email', 'phone', 'address', 'location_url', 'description']);
        return Inertia::render('LocateStore', [
            'branches' => $branches
        ]);
    }

    public function contact(){
        return Inertia::render('ContactPage');
    }
}
