// import "./bootstrap";
// import "../css/app.css";
// import { createInertiaApp } from "@inertiajs/react";
// import { createRoot } from "react-dom/client";
// import Layout from "./components/Layout/Layout";

// createInertiaApp({
//     resolve: (name) => {
//         const pages = import.meta.glob("./pages/**/*.jsx", { eager: true });
//         let page = pages[`./pages/${name}.jsx`];

//         // এখানে Layout কে pageProps সহ wrap করা
//         page.default.layout =
//             page.default.layout ||
//             ((pageElement, pageProps) => (
//                 <Layout {...pageProps}>{pageElement}</Layout>
//             ));

//         return page;
//     },
//     setup({ el, App, props }) {
//         createRoot(el).render(<App {...props} />);
//     },
// });

import "./bootstrap";
import "../css/app.css";

import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";

import Layout from "./pages/Layout";

createInertiaApp({
    title: (title) => title ? `${title}` : 'Danish Gift',
    resolve: (name) => {
        const pages = import.meta.glob("./pages/**/*.jsx");
        const page = pages[`./pages/${name}.jsx`];
        
        if (!page) {
             throw new Error(`Unknown page ${name}. Is it located under Pages with a .jsx extension?`);
        }

        return page().then(module => {
            module.default.layout = module.default.layout || (
                (pageElement, pageProps) => <Layout {...pageProps}>{pageElement}</Layout>
            );
            return module;
        });
    },

    progress: {
        color: '#EF4444', // Red to match site theme
        showSpinner: true,
    },

    setup({ el, App, props }) {
        createRoot(el).render(
            <>
                {/* Inertia App */}
                <App {...props} />
            </>,
        );
    },
});
