import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.jsx"],
            refresh: false, // production
            build: {
                outDir: "../public_html/build",
                emptyOutDir: true,
                manifest: true,
                rollupOptions: {
                    input: {
                        app: "resources/js/app.jsx",
                    },
                },
            },
        }),
        react(),
        tailwindcss(),
    ],
    build: {
        outDir: "../public_html/build",
        emptyOutDir: true,
        manifest: true,
        minify: "esbuild",
        sourcemap: false,
    },
    base: "/", // cPanel root
    assetsInclude: ["**/*.woff", "**/*.woff2", "**/*.ttf", "**/*.otf"],
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
