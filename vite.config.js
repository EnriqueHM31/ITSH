import { defineConfig } from 'vite';
import path from 'path';

/* ... */

export default defineConfig({
    /* ... */
    resolve: {
        alias: {
            "@/": new URL("./src", import.meta.url).pathname
        }
    },
    /* ... */
});

