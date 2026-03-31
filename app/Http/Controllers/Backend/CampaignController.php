<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CampaignDataTable;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Product;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class CampaignController extends Controller
{
    use ImageUploadTrait;

    public function index(CampaignDataTable $dataTable)
    {
        return $dataTable->render('backend.campaign.index');
    }

    public function create()
    {
        return view('backend.campaign.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:2000'],
            'banner' => ['nullable', 'image', 'max:2000'],
            'name' => ['required', 'max:200'],
            'title' => ['required', 'max:200'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'status' => ['required']
        ]);

        $campaign = new Campaign();
        $imagePath = $this->upload_image($request, 'image', 'uploads');
        $bannerPath = $this->upload_image($request, 'banner', 'uploads');

        $campaign->image = $imagePath;
        $campaign->banner = $bannerPath;
        $campaign->name = $request->name;
        $campaign->slug = Str::slug($request->name);
        $campaign->title = $request->title;
        $campaign->sub_title = $request->sub_title;
        $campaign->description = $request->description;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->status = $request->status;
        $campaign->save();

        Toastr::success('Campaign Created Successfully!');

        return redirect()->route('admin.campaign.index');
    }

    public function edit(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('backend.campaign.edit', compact('campaign'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:2000'],
            'banner' => ['nullable', 'image', 'max:2000'],
            'name' => ['required', 'max:200'],
            'title' => ['required', 'max:200'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'status' => ['required']
        ]);

        $campaign = Campaign::findOrFail($id);
        $imagePath = $this->update_image($request, 'image', 'uploads', $campaign->image);
        $bannerPath = $this->update_image($request, 'banner', 'uploads', $campaign->banner);

        $campaign->image = $imagePath ?: $campaign->image;
        $campaign->banner = $bannerPath ?: $campaign->banner;
        $campaign->name = $request->name;
        $campaign->slug = Str::slug($request->name);
        $campaign->title = $request->title;
        $campaign->sub_title = $request->sub_title;
        $campaign->description = $request->description;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->status = $request->status;
        $campaign->save();

        Toastr::success('Campaign Updated Successfully!');

        return redirect()->route('admin.campaign.index');
    }

    public function destroy(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $this->deleteImage($campaign->image);
        $this->deleteImage($campaign->banner);
        $campaign->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function changeStatus(Request $request)
    {
        $campaign = Campaign::findOrFail($request->id);
        $campaign->status = $request->status == 'true' ? 1 : 0;
        $campaign->save();

        return response(['status' => 'success', 'message' => 'Status has been updated!']);
    }

    /** View for managing products in a campaign */
    public function productsIndex(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $products = Product::where('status', 1)->get();
        $campaignProducts = CampaignProduct::where('campaign_id', $id)->with('product')->get();
        return view('backend.campaign.products', compact('campaign', 'products', 'campaignProducts'));
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'unique:campaign_products,product_id,NULL,id,campaign_id,' . $request->campaign_id],
            'campaign_id' => ['required'],
            'discount_type' => ['required'],
            'discount_value' => ['required', 'numeric']
        ]);

        $campaignProduct = new CampaignProduct();
        $campaignProduct->campaign_id = $request->campaign_id;
        $campaignProduct->product_id = $request->product_id;
        $campaignProduct->discount_type = $request->discount_type;
        $campaignProduct->discount_value = $request->discount_value;
        $campaignProduct->save();

        Toastr::success('Product Added Successfully!');

        return redirect()->back();
    }

    public function removeProduct(string $id)
    {
        $campaignProduct = CampaignProduct::findOrFail($id);
        $campaignProduct->delete();

        return response(['status' => 'success', 'message' => 'Product Removed Successfully!']);
    }
}
