<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function index()
    {
        $now = now();
        $campaigns = Campaign::where('status', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('id', 'desc')
            ->get();

        $featuredCampaign = $campaigns->first();
        $featuredProducts = [];

        if ($featuredCampaign) {
            $featuredProducts = CampaignProduct::where('campaign_id', $featuredCampaign->id)
                ->with(['product' => function($query) use ($now) {
                    $query->active()->withReview()->with(['campaignProducts' => function($cq) use ($now) {
                        $cq->with('campaign')->whereHas('campaign', function($ccq) use ($now) {
                            $ccq->where('status', 1)
                                ->where('start_date', '<=', $now)
                                ->where('end_date', '>=', $now);
                        });
                    }]);
                }])
                ->limit(6)
                ->get();
        }

        return Inertia::render('CampaignPage', [
            'campaigns' => $campaigns,
            'featuredCampaign' => $featuredCampaign,
            'featuredProducts' => $featuredProducts
        ]);
    }

    public function show(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $campaignProducts = CampaignProduct::where('campaign_id', $campaign->id)
            ->with(['product' => function($query) use ($campaign) {
                $now = now();
                $query->active()->withReview()->with(['campaignProducts' => function($cq) use ($now) {
                    $cq->with('campaign')->whereHas('campaign', function($ccq) use ($now) {
                        $ccq->where('status', 1)
                            ->where('start_date', '<=', $now)
                            ->where('end_date', '>=', $now);
                    });
                }]);
            }])
            ->get();

        return Inertia::render('CampaignDetailPage', [
            'campaign' => $campaign,
            'campaignProducts' => $campaignProducts
        ]);
    }

    public function allCampaignProducts(Request $request)
    {
        $now = now();
        $query = Product::active()
            ->whereHas('campaignProducts.campaign', function($query) use ($now) {
                $query->where('status', 1)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            })
            ->select('id', 'name', 'slug', 'category_id', 'thumb_image', 'price', 'offer_price', 'offer_start_date', 'offer_end_date', 'qty');

        // Basic sorting
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'lowtohigh':
                    $query->orderBy('price', 'asc');
                    break;
                case 'hightolow':
                    $query->orderBy('price', 'desc');
                    break;
                case 'recommended':
                    $query->withAvg(['reviews' => fn($q) => $q->where('status', 1)], 'rating')->orderByDesc('reviews_avg_rating');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with(['category:id,name', 'campaignProducts' => function($cq) use ($now) {
                 $cq->with('campaign')->whereHas('campaign', function($ccq) use ($now) {
                    $ccq->where('status', 1)
                        ->where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now);
                });
            }])
            ->withCount([
                'reviews' => fn($q) => $q->where('status', 1),
                'colors' => fn($q) => $q->active(),
                'sizes' => fn($q) => $q->active(),
                'productImageGalleries' => fn($q) => $q->whereNotNull('color_id'),
            ])
            ->paginate(30)
            ->withQueryString();

        return Inertia::render('AllCampaignProductsPage', [
            'products' => $products,
            'filters' => $request->all()
        ]);
    }
}
