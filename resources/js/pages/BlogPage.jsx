// BlogPage.jsx
import React from "react";
import { Link, usePage } from "@inertiajs/react";
import { BlogCardSkeleton } from "../components/Skeleton";

const BlogCard = ({ post, formatDate, truncate }) => {
  const [isImageLoaded, setIsImageLoaded] = React.useState(false);

  return (
    <article className="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col h-full relative">
      {!isImageLoaded && <BlogCardSkeleton />}
      <div className={`flex flex-col h-full ${!isImageLoaded ? 'invisible absolute inset-0' : 'visible'}`}>
        <div className="relative aspect-4/3 overflow-hidden">
          <img
            src={post.image ? `/storage/${post.image}` : "https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800"}
            alt={post.title}
            className="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
            onLoad={() => setIsImageLoaded(true)}
          />
          {post.category && (
            <div className="absolute top-4 left-4">
              <span className="bg-red text-white text-xs font-medium px-3 py-1 rounded-full">
                {post.category.name}
              </span>
            </div>
          )}
        </div>

        <div className="p-6 flex flex-col grow">
          <time className="text-sm text-gray-500 mb-2 block">
            {formatDate(post.created_at)}
          </time>

          <Link href={`/blog/${post.slug}`}>
            <h2 className="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-red transition-colors">
              {post.title}
            </h2>
          </Link>

          <p className="text-gray-600 mb-6 line-clamp-3 grow">
            {truncate(post.description, 120)}
          </p>

          <Link
            href={`/blog/${post.slug}`}
            className="inline-flex items-center text-red font-medium hover:text-red-800 transition-colors mt-auto"
          >
            Read more
            <span className="ml-2">→</span>
          </Link>
        </div>
      </div>
    </article>
  );
};

const BlogPage = () => {
  const { blogs, categories } = usePage().props;

  // Helper to truncate text
  const truncate = (text, length = 150) => {
    if (!text) return "";
    const strippedText = text.replace(/<[^>]*>/g, "");
    return strippedText.length > length
      ? strippedText.substring(0, length) + "..."
      : strippedText;
  };

  // Format date
  const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("en-US", options);
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header / Hero */}
      <div className="bg-linear-to-r from-red to-red-800 text-white">
        <div className="container mx-auto px-4 py-16 md:py-24">
          <h1 className="text-4xl md:text-5xl font-bold text-center mb-4">
            Danish Stories & Inspiration
          </h1>
          <p className="text-lg md:text-xl text-center opacity-90 max-w-3xl mx-auto">
            Discover the culture, design, food, and lifestyle behind our
            authentic Danish products
          </p>
        </div>
      </div>

      <div className="container mx-auto px-4 py-12">
        {/* Category Filter (Optional) */}
        {categories && categories.length > 0 && (
          <div className="mb-8 flex flex-wrap gap-3 justify-center">
            <Link
              href="/blog"
              className="px-4 py-2 rounded-full border border-gray-300 hover:border-red hover:text-red transition-colors"
            >
              All Posts
            </Link>
            {categories.map((category) => (
              <Link
                key={category.id}
                href={`/blog?category=${category.slug}`}
                className="px-4 py-2 rounded-full border border-gray-300 hover:border-red hover:text-red transition-colors"
              >
                {category.name}
              </Link>
            ))}
          </div>
        )}

        {/* Blog Grid */}
        {blogs.data && blogs.data.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            {blogs.data.map((post) => (
              <BlogCard 
                key={post.id} 
                post={post} 
                formatDate={formatDate} 
                truncate={truncate} 
              />
            ))}
          </div>
        ) : (
          <div className="text-center py-16">
            <p className="text-gray-500 text-lg">No blog posts found.</p>
          </div>
        )}

        {/* Pagination */}
        {blogs.links && blogs.links.length > 3 && (
          <div className="mt-12 flex justify-center items-center gap-3">
            {blogs.links.map((link, index) => {
              if (link.url === null) return null;

              return (
                <Link
                  key={index}
                  href={link.url}
                  className={`px-4 py-2 rounded-lg font-medium transition-colors ${
                    link.active
                      ? "bg-red text-white"
                      : "border border-gray hover:bg-gray-50"
                  }`}
                  dangerouslySetInnerHTML={{ __html: link.label }}
                />
              );
            })}
          </div>
        )}
      </div>
    </div>
  );
};

export default BlogPage;
