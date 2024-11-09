/**
 * jshint esversion: 6
 */
/*
    Create by: trinhdev
    Update at: 2022/08/04
    Contact: trinhhuynhdp@gmail.com
*/
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/**',
                'public/**',
            ],
        }),
        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ]
});
