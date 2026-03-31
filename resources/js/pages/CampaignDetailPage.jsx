import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from './Layout';
import ProductCard from '../components/ProductCard';

const CampaignDetailPage = ({ campaign, campaignProducts }) => {
    

    return (
        <Layout>
            <Head title={campaign.title || campaign.name} />
            
            {/* Hero Section */}
            <div className="relative h-[300px] md:h-[400px] lg:h-[500px] overflow-hidden">
                <img 
                    src={campaign.banner ? `/storage/${campaign.banner}` : (campaign.image ? `/storage/${campaign.image}` : '/placeholder-banner.jpg')} 
                    alt={campaign.name}
                    className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-black/40 flex items-center justify-center text-center p-4">
                    <div className="max-w-3xl">
                        <h1 className="text-3xl md:text-5xl lg:text-6xl font-black text-white mb-4 uppercase tracking-tighter">
                            {campaign.title || campaign.name}
                        </h1>
                        {campaign.sub_title && (
                            <p className="text-xl md:text-2xl text-white/90 font-medium mb-6">
                                {campaign.sub_title}
                            </p>
                        )}
                        <div className="inline-flex items-center gap-3 px-6 py-2 bg-red text-white font-bold rounded-full text-sm md:text-base">
                            <svg className="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                            </svg>
                            Ends: {new Date(campaign.end_date).toLocaleDateString()}
                        </div>
                    </div>
                </div>
            </div>

            <div className="container mx-auto px-4 py-12 md:py-20">
                {/* Description */}
                {campaign.description && (
                    <div className="max-w-3xl mx-auto mb-16 text-center text-gray-600 prose prose-red lg:prose-lg"
                         dangerouslySetInnerHTML={{ __html: campaign.description }} />
                )}

                {/* Products Grid */}
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-8">
                    {campaignProducts.length > 0 ? (
                        campaignProducts.map((cp) => (
                            <div key={cp.id} className="relative group">
                                <ProductCard product={cp.product} />
                            </div>
                        ))
                    ) : (
                        <div className="col-span-full py-12 text-center text-gray-500">
                            No products currently assigned to this campaign.
                        </div>
                    )}
                </div>

                <div className="mt-20 text-center">
                    <Link href="/campaign" className="inline-flex items-center text-red font-bold hover:gap-3 transition-all">
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to All Campaigns
                    </Link>
                </div>
            </div>
        </Layout>
    );
};

export default CampaignDetailPage;
