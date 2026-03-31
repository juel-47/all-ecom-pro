<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\JobApplicationDataTable;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(JobApplicationDataTable $dataTable)
    {
        return $dataTable->render('backend.applications.index');
    }

    // Download resume/pdf
    public function download(string $id)
    {
        $application = JobApplication::findOrFail($id);
        $filePath = public_path($application->resume);

        if (!file_exists($filePath)) {
            // return response(['status' => 'error', 'message' => 'File not found.']);
            Toastr::error('The requested file could not be found. It may have been removed or is temporarily unavailable.');
            return back();
        }

        return response()->download($filePath);
    }

    // View PDF in browser
    public function viewPdf(string $id)
    {
        $application = JobApplication::findOrFail($id);
        $filePath = public_path($application->resume);

        if (!file_exists($filePath)) {
            // return back()->with('error', 'File not found.');
            Toastr::error('The requested file could not be found. It may have been removed or is temporarily unavailable.');
            return back();
        }

        return response()->file($filePath);
    }
    public function destroy(string $id)
    {
        $application = JobApplication::findOrFail($id);

        // Delete file if exists
        if ($application->resume && file_exists(public_path($application->resume))) {
            unlink(public_path($application->resume));
        }
        // Delete video_cv file if exists
        if ($application->video_cv && file_exists(public_path($application->video_cv))) {
            unlink(public_path($application->video_cv));
        }

        // Delete record
        $application->delete();

        // Return JSON response
        return response(['status' => 'success', 'message' => 'Application Deleted Successfully!']);
    }
    public function coverLetter(string $id)
    {
        return view('backend.applications.cover_letter', compact('id'));
    }
}
