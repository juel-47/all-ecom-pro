import { Link, usePage } from "@inertiajs/react";
import React from "react";

const Breadcrumb = ({ customItems = [] }) => {
  const { url } = usePage();

  // Default path-based items (you can override with customItems)
  const getDefaultPathItems = () => {
    const path = url.split('?')[0]; // Get pathname without query string
    if (path === "/" || path === "") return [];

    const segments = path.split("/").filter(Boolean);

    return segments.map((segment, index) => {
      const segmentUrl = "/" + segments.slice(0, index + 1).join("/");
      // Optional: make it more human-readable
      const name = segment
        .split("-")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");

      return { name, url: segmentUrl };
    });
  };

  const items = customItems.length > 0 ? customItems : getDefaultPathItems();

  if (items.length === 0) return null;

  return (
    <nav aria-label="Breadcrumb" className="mb-6">
      <ol className="flex flex-wrap items-center gap-2 text-sm text-gray-600">
        <li>
          <Link
            href="/"
            className="hover:text-red transition-colors"
          >
            Home
          </Link>
        </li>

        {items.map((item, index) => (
          <li key={index} className="flex items-center gap-2">
            <span className="text-gray-400">/</span>
            {index === items.length - 1 ? (
              <span className="text-gray-900 font-medium" aria-current="page">
                {item.name}
              </span>
            ) : (
              <Link
                href={item.url}
                className="hover:text-red transition-colors truncate max-w-[180px]"
              >
                {item.name}
              </Link>
            )}
          </li>
        ))}
      </ol>
    </nav>
  );
};

export default Breadcrumb;
