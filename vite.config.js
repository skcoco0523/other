// vite.config.js

//npm run dev
//npm run build

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';
const CACHE_PREFIX = 'other-';

const domain = process.env.DOMAINS || 'localhost';
const isLocal = domain === 'localhost';

export default defineConfig({
    base: isLocal ? '/' : '/other/',  // 開発は /、本番は /other/
    //デバッグ用設定
    server: {
        host: '0.0.0.0', // 外部からのアクセスを許可
        port: 5173,     // other プロジェクトのViteポート
        hmr: {
            host: domain, // HMR のホストをドメイン名に設定
            protocol: domain === 'localhost' ? 'ws' : 'wss',
            clientPort: domain === 'localhost' ? 5173 : 443,
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss', // Sassファイル
                'resources/js/app.js',     // JavaScriptファイル
                'resources/css/app.css',   // CSSファイル
            ],
            refresh: true,
            detectTls: false,
            //detectTls: 'skcoco.com',
        }),
        vue(),
        VitePWA({
            registerType: 'autoUpdate', // サービスワーカーの自動更新
            //strategies: 'injectManifest',
            strategies: 'generateSW', 
            //srcDir: 'src', // カスタムサービスワーカーのソースが格納されているディレクトリ
            filename: 'sw.js', // サービスワーカーのファイル名
            //サービスワーカーのスコープを明示的にアプリのパスに設定
            scope: '/other/',
            // Service Worker登録スクリプトの自動挿入を無効化★
            injectRegister: null, 
            
            manifest: {
                name: process.env.VITE_APP_NAME || "その他",
                short_name: process.env.VITE_APP_NAME || "その他",
                description: "アプリリスト",
                start_url: "/other",
                display: "standalone",
                background_color: "#ffffff",
                theme_color: "#000000",
                icons: [
                    {
                        src: "/other/img/icon/home_icon_192_192.png",
                        sizes: "192x192",
                        type: "image/png"
                    },
                    {
                        src: "/other/img/icon/home_icon_512_512.png",
                        sizes: "512x512",
                        type: "image/png"
                    }
                ]
            },
            
            workbox: {
                globPatterns: [
                    //'**/*.{js,css,html,png,jpg,svg}', // キャッシュ対象のファイルパターン
                    '**/*.{js,css,html,png,jpg}', // キャッシュ対象のファイルパターン
                    'manifest.webmanifest',
                    // ルートURLも含める
                    '/other/'
                ],
                // ナビゲーションリクエスト（URL直接入力など）に対するフォールバック設定
                // Laravelが返す /other/ のHTMLページをフォールバック先とする
                navigateFallback: '/other/', 
                // ナビゲーションフォールバックの対象外とするパス
                navigateFallbackDenylist: [/\/api\//, /\/admin\//, /\/oauth\//], // APIや管理画面はフォールバックしない

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
                            cacheName: `${CACHE_PREFIX}static-assets-cache`,
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
                            cacheName: `${CACHE_PREFIX}image-cache`,
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
