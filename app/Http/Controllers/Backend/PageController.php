<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $content = About::first();
        return view('backend.pages.about.index', compact('content'));
    }

    public function aboutUpdate(Request $request)
    {
        $validation = $request->validate([
            'content' => 'required',
        ]);
        About::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'content' => $request->content
            ]
        );
        toastr('Updated Successfully', 'success', 'Success');
        // return redirect()->route('admin.vendor-condition.index');
        return redirect()->back();
    }

    /**
     * terms and conditon
     */
    public function termsAndCondition()
    {
        $content = TermsAndCondition::first();
        return view('backend.pages.terms-and-condition.index', compact('content'));
    }

    public function termsAndConditionUpdate(Request $request)
    {
        $validation = $request->validate([
            'content' => 'required',
        ]);
        TermsAndCondition::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'content' => $request->content
            ]
        );
        toastr('Updated Successfully', 'success', 'Success');
        return redirect()->back();
    }
}
