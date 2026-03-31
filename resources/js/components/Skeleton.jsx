import React from 'react';

const Skeleton = ({ className = "" }) => {
  return (
    <div className={`bg-gray-200 animate-pulse rounded-lg ${className}`}></div>
  );
};

export const ProductCardSkeleton = () => {
    return (
        <div className="bg-white rounded-xl overflow-hidden border border-black/30 shadow-sm animate-pulse">
            {/* Image Placeholder */}
            <div className="aspect-square bg-gray-200"></div>
            
            {/* Content Placeholder */}
            <div className="p-3 sm:p-4 space-y-3">
                <div className="h-4 bg-gray-200 rounded w-3/4"></div>
                <div className="h-4 bg-gray-200 rounded w-1/2"></div>
                
                <div className="flex gap-2">
                    <div className="h-6 bg-gray-200 rounded w-16"></div>
                    <div className="h-6 bg-gray-200 rounded w-12"></div>
                </div>
                
                <div className="space-y-2 mt-4">
                    <div className="h-10 bg-gray-200 rounded w-full"></div>
                    <div className="h-10 bg-gray-200 rounded w-full"></div>
                </div>
            </div>
        </div>
    );
}

export const CategorySkeleton = () => {
  return (
    <div className="flex flex-col items-center animate-pulse">
      <div className="aspect-square w-full rounded-xl bg-gray-200"></div>
      <div className="mt-3 h-5 bg-gray-200 rounded w-24"></div>
    </div>
  );
};

export const BlogCardSkeleton = () => {
  return (
    <div className="bg-white rounded-xl overflow-hidden shadow-md animate-pulse h-full flex flex-col">
      {/* Image Placeholder */}
      <div className="aspect-4/3 bg-gray-200"></div>
      
      {/* Content Placeholder */}
      <div className="p-6 space-y-4 grow flex flex-col">
        <div className="h-4 bg-gray-200 rounded w-1/4"></div>
        <div className="space-y-2">
          <div className="h-6 bg-gray-200 rounded w-full"></div>
          <div className="h-6 bg-gray-200 rounded w-3/4"></div>
        </div>
        <div className="space-y-2 grow">
          <div className="h-4 bg-gray-200 rounded w-full"></div>
          <div className="h-4 bg-gray-200 rounded w-full"></div>
          <div className="h-4 bg-gray-200 rounded w-2/3"></div>
        </div>
        <div className="h-4 bg-gray-200 rounded w-24"></div>
      </div>
    </div>
  );
};

export default Skeleton;
