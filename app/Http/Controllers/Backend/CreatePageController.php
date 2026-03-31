<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CreatePageDataTable;
use App\Http\Controllers\Controller;
use App\Models\CreatePage;
use Brian2694\Toastr\Facades\Toastr;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class CreatePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CreatePageDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.create_page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.create_page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'page_for' => 'required',
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
        CreatePage::create($input);
        Toastr::success('Page Created successfully');
        return redirect()->route('admin.create-page.index');
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
        $create_page = CreatePage::find($id);
        return view('backend.pages.create_page.edit', compact('create_page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'page_for'    => 'required',
            'name'        => 'required',
            'title'       => 'required',
            'description' => 'required',
        ]);
        $page = CreatePage::findOrFail($id);
        $validated['slug'] = strtolower(preg_replace('/\s+/', '-', $validated['name']));
        $page->update($validated);
        Toastr::success('Page Updated successfully');
        return redirect()->route('admin.create-page.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = CreatePage::findOrFail($id);
        $page->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    function changeStatus(Request $request)
    {
        $coupon = CreatePage::findOrFail($request->id);
        $coupon->status = $request->status == 'true' ? 1 : 0;
        $coupon->save();
        return response(['message' => 'Page Status has been Updated!',]);
    }
}
