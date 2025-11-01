import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/admin.css",
                "resources/js/app.js",
                "resources/js/dashboard.js",
                "resources/js/loader.js",
                "resources/js/toast.js",
                "resources/js/checkbox.js",
                "resources/js/offcanvas.js",
                "resources/js/admin.js",
            ],
            refresh: true,
        }),
    ],
});
