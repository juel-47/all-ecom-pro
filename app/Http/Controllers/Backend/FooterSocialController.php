<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\FooterSocialDataTable;
use App\Http\Controllers\Controller;
use App\Models\FooterSocial;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FooterSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FooterSocialDataTable $dataTable)
    {
        return $dataTable->render('backend.footer.footer-socials.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.footer.footer-socials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|unique:footer_socials,name',
            'icon' => 'nullable',
            'url' => 'required',
            'status' => 'required',
            'icon_extra' => 'nullable',
            'serial_no' => 'required|unique:footer_socials,serial_no',
        ]);
        FooterSocial::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'icon_extra' => $request->icon_extra,
            'url' => $request->url,
            'serial_no' => $request->serial_no,
            'status' => $request->status
        ]);
        Toastr::success('Social Created Successfully', 'Success');
        return redirect()->route('admin.footer-socials.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $footerSocial = FooterSocial::find($id);
        return view('backend.footer.footer-socials.edit', compact('footerSocial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:footer_socials,name,' . $id,
            'icon' => 'nullable',
            'url' => 'required',
            'status' => 'required',
            'icon_extra' => 'nullable',
            'serial_no' => 'required|unique:footer_socials,serial_no,' . $id,
        ]);
        FooterSocial::find($id)->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'icon_extra' => $request->icon_extra,
            'url' => $request->url,
            'serial_no' => $request->serial_no,
            'status' => $request->status
        ]);
        Toastr::success('Social Updated Successfully', 'Success');
        return redirect()->route('admin.footer-socials.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        FooterSocial::find($id)->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    /**
     * Change status social link
     */
    public function changeStatus(Request $request)
    {
        $footer = FooterSocial::findOrFail($request->id);
        $footer->status = $request->status == 'true' ? 1 : 0;
        $footer->save();
        Cache::forget('footer_social');
        return response(['message' => 'Status has been Updated!',]);
    }
}
