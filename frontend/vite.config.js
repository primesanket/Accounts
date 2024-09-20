import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    define: {
        "process.env": process.env,
    },
    server: {
        host: true, // This will expose the server to the network
        port: 3000, // You can specify a port if you want, default is 3000
    },
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./src", import.meta.url)),
        },
    },
});
