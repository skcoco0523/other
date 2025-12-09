// vite.config.js

//npm run dev
//npm run build

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

// .env から環境変数を読み込み
const host          = process.env.VITE_SUB_DOMAIN || 'localhost'; 
const appName       = process.env.VITE_APP_NAME || 'default';

const isLocal       = host === 'localhost' || host.includes('127.0.0.1');
const protocol      = isLocal ? 'ws' : 'wss';
const clientPort    = isLocal ? 5173 : 443;
// ベースURLパスを定義 (Apache設定に基づき '/' に統一)
const baseUrlPath   = '/';
const cachePrefix = `${appName.toLowerCase()}-`;

export default defineConfig({
    // アセットのベースパス
    base: baseUrlPath,
    //デバッグ用設定
    server: {
        host: '0.0.0.0', // 外部からのアクセスを許可
        port: 5173,     // app01 プロジェクトのViteポート
        hmr: {
            host: host, // HMR のホストをサブドメイン名に設定
            protocol:protocol, // HTTPS対応
            clientPort: clientPort, // HTTPS環境では443ポート
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
            //strategies: 'generateSW', 
            strategies: 'injectManifest',   // カスタムサービスワーカーを使用
            srcDir: 'resources/js', // カスタムサービスワーカーのソースが格納されているディレクトリ
            filename: 'custom-sw.js', // サービスワーカーのファイル名
            //サービスワーカーのスコープを明示的にアプリのパスに設定
            scope: baseUrlPath,
            // Service Worker登録スクリプトの自動挿入を無効化★
            //injectRegister: null, 
            injectManifest: {
                globPatterns: ['**/*.{js,css,html,png,svg}'], // precache対象 
            },
            /* web.php側で動的に設定するため、ここではコメントアウト
            manifest: {
                name: process.env.VITE_APP_NAME || 'SK_HOME',
                short_name: process.env.VITE_APP_NAME || 'SK_HOME',
                description: 'スマートリモコン',
                start_url: baseUrlPath,
                display: 'standalone',
                background_color: '#ffffff',
                theme_color: '#000000',
                icons: [
                    {
                        src: baseUrlPath+'img/icon/home_icon_192_192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: baseUrlPath+'img/icon/home_icon_512_512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            },
            */
            
            workbox: {
                globPatterns: [
                    //'**/*.{js,css,html,png,jpg,svg}', // キャッシュ対象のファイルパターン
                    '**/*.{js,css,html,png,jpg}', // キャッシュ対象のファイルパターン
                    'manifest.webmanifest',
                    // ルートURLも含める
                    baseUrlPath
                ],
                // ナビゲーションリクエスト（URL直接入力など）に対するフォールバック設定
                // Laravelが返す /app01/ のHTMLページをフォールバック先とする
                navigateFallback: baseUrlPath, 
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
                            cacheName: `${cachePrefix}static-assets-cache`,
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
                            cacheName: `${cachePrefix}image-cache`,
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
