//npm run dev
//npm run build

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss', // Sassファイル
                'resources/js/app.js',     // JavaScriptファイル
                'resources/css/app.css',   // CSSファイル
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate', // サービスワーカーの自動更新
            strategies: 'injectManifest',
            srcDir: 'src', // カスタムサービスワーカーのソースが格納されているディレクトリ
            filename: 'sw.js', // サービスワーカーのファイル名
            injectManifest: {
              swSrc: 'src/sw.js', // カスタムサービスワーカーのソースファイル
              swDest: 'public/build/sw.js' // 自動生成されるサービスワーカーの出力先
            },
            //manifest.json　は本番と検証で分けるため　別ファイル
            /*
            manifest: {
                name: process.env.VITE_APP_NAME || "歌share",
                short_name: process.env.VITE_APP_NAME || "歌share",
                description: "フレンド間で音楽を共有するアプリケーションです。",
                start_url: "/app01",
                display: "standalone",
                background_color: "#ffffff",
                theme_color: "#000000",
                icons: [
                    {
                        src: "/app01/img/icon/home_icon_192_192.png",
                        sizes: "192x192",
                        type: "image/png"
                    },
                    {
                        src: "/app01/img/icon/home_icon_512_512.png",
                        sizes: "512x512",
                        type: "image/png"
                    }
                ]
            },
            */
            workbox: {
                globPatterns: [
                    //'**/*.{js,css,html,png,jpg,svg}', // キャッシュ対象のファイルパターン
                    '**/*.{js,css,html,png,jpg}', // キャッシュ対象のファイルパターン
                ],
                runtimeCaching: [
                    /*
                    {
                        urlPattern: /\.(?:html)$/, // HTMLのキャッシュ設定
                        handler: 'StaleWhileRevalidate', // または 'CacheFirst' など
                        options: {
                            cacheName: 'static-assets-cache',
                            expiration: {
                                maxEntries: 50, // 最大エントリ数 ※ファイル数
                                maxAgeSeconds: 24 * 60 * 60, // 1週間（7日間）
                            },
                        },
                    },
                    */
                    {
                        urlPattern: /\.(?:js|css)$/, // JavaScript, CSSのキャッシュ設定
                        handler: 'StaleWhileRevalidate', //
                        options: {
                            cacheName: 'static-assets-cache',
                            expiration: {
                                maxEntries: 50, // 最大エントリ数 ※ファイル数
                                maxAgeSeconds: 30 * 24 * 60 * 60, // 30日
                            },
                        },
                    },
                    {
                        urlPattern: /\.(?:png|jpg|jpeg|svg)$/, // 画像のキャッシュ設定例
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'image-cache',
                            expiration: {
                                maxEntries: 50, // 最大エントリ数 ※ファイル数
                                maxAgeSeconds: 30 * 24 * 60 * 60, // 30日
                            },
                        },
                    },
                    /*
                    {
                        urlPattern: /^https:\/\/api\.example\.com\/.*$/, // APIリクエストのキャッシュ設定例
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-cache',
                            expiration: {
                                maxEntries: 10,
                                maxAgeSeconds: 24 * 60 * 60, // 1日
                            },
                        },
                    },
                    */
                ],
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    define: {
        'process.env.BASE_URL': JSON.stringify('/app01/')
    },
    build: {
        rollupOptions: {
            output: {
                entryFileNames: '[name].[hash].js',
                chunkFileNames: '[name].[hash].js',
                assetFileNames: '[name].[hash][extname]',
            },
        },
    },
});
