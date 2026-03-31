<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\JobApplicationReceived;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|email|max:200',
            'phone' => 'required|max:20',
            'position' => 'required|max:100',
            'resume' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,csv,txt|max:5120',
            'video_cv' => 'required|file|mimes:mp4,mov,avi|max:51200',
            'cover_letter' => 'nullable|string',
        ]);

        // Prevent duplicate submission by same email + position
        $exists = JobApplication::where('email', $validated['email'])
            ->where('position', $validated['position'])
            ->first();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already submitted an application for this position.'
            ], 409); // 409 = Conflict
        }

        // File upload path
        $uploadPath = public_path('uploads/applications');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // File upload
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            // Save relative path
            $validated['resume'] = 'uploads/applications/' . $filename;
        }

        if ($request->hasFile('video_cv')) {
            $video = $request->file('video_cv');
            $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $videoUploadPath = public_path('uploads/video_cvs');

            if (!file_exists($videoUploadPath)) {
                mkdir($videoUploadPath, 0777, true);
            }

            $video->move($videoUploadPath, $videoName);

            $validated['video_cv'] = 'uploads/video_cvs/' . $videoName;
        }

        // Create application
        $application = JobApplication::create($validated);

        Mail::to($application->email)->send(new JobApplicationReceived($application));

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully',
            'data' => $application
        ]);
    }
}
