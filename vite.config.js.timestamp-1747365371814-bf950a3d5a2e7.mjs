// vite.config.js
import { defineConfig } from "file:///opt/bitnami/apache/htdocs/other/node_modules/vite/dist/node/index.js";
import laravel from "file:///opt/bitnami/apache/htdocs/other/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///opt/bitnami/apache/htdocs/other/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { VitePWA } from "file:///opt/bitnami/apache/htdocs/other/node_modules/vite-plugin-pwa/dist/index.js";
var vite_config_default = defineConfig({
  server: {
    https: {
      // Apache が HTTPS を処理するため、Vite 自身での HTTPS 設定は不要
      ///opt/bitnami/apache2/conf/bitnami/bitnami-ssl.conf
      //「mkcert skcoco.com www.skcoco.com 43.206.183.58」で以下を作成
      //home/bitnami/.local/share/mkcert/rootCA.pem　をアクセス元端末でインポートする必要がある
      //key: '/home/bitnami/htdocs/other/skcoco.com+2-key.pem',
      //cert: '/home/bitnami/htdocs/other/skcoco.com+2.pem',
    },
    port: 5173,
    // Vite が生成するリソースのURLをリバースプロキシのURLに合わせる
    origin: "https://skcoco.com",
    host: "0.0.0.0",
    hmr: {
      host: "skcoco.com",
      //net::ERR_CERT_AUTHORITY_INVALIDになるから追加
      protocol: "wss"
      // HMR も HTTPS (WebSocket Secure) で通信するようにプロトコルを指定
    }
  },
  plugins: [
    laravel({
      input: [
        "resources/sass/app.scss",
        // Sassファイル
        "resources/js/app.js"
        // JavaScriptファイル
        //'resources/css/app.css',   // CSSファイル
      ],
      refresh: true
      //detectTls: 'skcoco.com',
    }),
    vue(),
    VitePWA({
      registerType: "autoUpdate",
      // サービスワーカーの自動更新
      strategies: "injectManifest",
      srcDir: "src",
      // カスタムサービスワーカーのソースが格納されているディレクトリ
      filename: "sw.js",
      // サービスワーカーのファイル名
      injectManifest: {
        swSrc: "src/sw.js",
        // カスタムサービスワーカーのソースファイル
        swDest: "public/build/sw.js"
        // 自動生成されるサービスワーカーの出力先
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
          "**/*.{js,css,html,png,jpg}"
          // キャッシュ対象のファイルパターン
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
            urlPattern: /\.(?:js|css)$/,
            // JavaScript, CSSのキャッシュ設定
            handler: "StaleWhileRevalidate",
            //
            options: {
              cacheName: "static-assets-cache",
              expiration: {
                maxEntries: 50,
                // 最大エントリ数 ※ファイル数
                maxAgeSeconds: 30 * 24 * 60 * 60
                // 30日
              }
            }
          },
          {
            urlPattern: /\.(?:png|jpg|jpeg|svg)$/,
            // 画像のキャッシュ設定例
            handler: "CacheFirst",
            options: {
              cacheName: "image-cache",
              expiration: {
                maxEntries: 50,
                // 最大エントリ数 ※ファイル数
                maxAgeSeconds: 30 * 24 * 60 * 60
                // 30日
              }
            }
          }
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
        ]
      }
    })
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js"
    }
  },
  base: "/other/",
  define: {
    "process.env.BASE_URL": JSON.stringify("/other/")
  },
  build: {
    rollupOptions: {
      output: {
        entryFileNames: "[name].[hash].js",
        chunkFileNames: "[name].[hash].js",
        assetFileNames: "[name].[hash][extname]"
      }
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvb3B0L2JpdG5hbWkvYXBhY2hlL2h0ZG9jcy9vdGhlclwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL29wdC9iaXRuYW1pL2FwYWNoZS9odGRvY3Mvb3RoZXIvdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL29wdC9iaXRuYW1pL2FwYWNoZS9odGRvY3Mvb3RoZXIvdml0ZS5jb25maWcuanNcIjsvLyB2aXRlLmNvbmZpZy5qc1xuXG4vL25wbSBydW4gZGV2XG4vL25wbSBydW4gYnVpbGRcblxuaW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7IFZpdGVQV0EgfSBmcm9tICd2aXRlLXBsdWdpbi1wd2EnO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIHNlcnZlcjoge1xuICAgICAgICBodHRwczoge1xuICAgICAgICAgICAgLy8gQXBhY2hlIFx1MzA0QyBIVFRQUyBcdTMwOTJcdTUxRTZcdTc0MDZcdTMwNTlcdTMwOEJcdTMwNUZcdTMwODFcdTMwMDFWaXRlIFx1ODFFQVx1OEVBQlx1MzA2N1x1MzA2RSBIVFRQUyBcdThBMkRcdTVCOUFcdTMwNkZcdTRFMERcdTg5ODFcbiAgICAgICAgICAgIC8vL29wdC9iaXRuYW1pL2FwYWNoZTIvY29uZi9iaXRuYW1pL2JpdG5hbWktc3NsLmNvbmZcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgLy9cdTMwMENta2NlcnQgc2tjb2NvLmNvbSB3d3cuc2tjb2NvLmNvbSA0My4yMDYuMTgzLjU4XHUzMDBEXHUzMDY3XHU0RUU1XHU0RTBCXHUzMDkyXHU0RjVDXHU2MjEwXG4gICAgICAgICAgICAvL2hvbWUvYml0bmFtaS8ubG9jYWwvc2hhcmUvbWtjZXJ0L3Jvb3RDQS5wZW1cdTMwMDBcdTMwOTJcdTMwQTJcdTMwQUZcdTMwQkJcdTMwQjlcdTUxNDNcdTdBRUZcdTY3MkJcdTMwNjdcdTMwQTRcdTMwRjNcdTMwRERcdTMwRkNcdTMwQzhcdTMwNTlcdTMwOEJcdTVGQzVcdTg5ODFcdTMwNENcdTMwNDJcdTMwOEJcbiAgXG4gICAgICAgICAgICAvL2tleTogJy9ob21lL2JpdG5hbWkvaHRkb2NzL290aGVyL3NrY29jby5jb20rMi1rZXkucGVtJyxcbiAgICAgICAgICAgIC8vY2VydDogJy9ob21lL2JpdG5hbWkvaHRkb2NzL290aGVyL3NrY29jby5jb20rMi5wZW0nLFxuICBcbiAgICAgICAgfSxcbiAgICAgICAgcG9ydDogNTE3MyxcbiAgICAgICAgLy8gVml0ZSBcdTMwNENcdTc1MUZcdTYyMTBcdTMwNTlcdTMwOEJcdTMwRUFcdTMwQkRcdTMwRkNcdTMwQjlcdTMwNkVVUkxcdTMwOTJcdTMwRUFcdTMwRDBcdTMwRkNcdTMwQjlcdTMwRDdcdTMwRURcdTMwQURcdTMwQjdcdTMwNkVVUkxcdTMwNkJcdTU0MDhcdTMwOEZcdTMwNUJcdTMwOEJcbiAgICAgICAgb3JpZ2luOiAnaHR0cHM6Ly9za2NvY28uY29tJyxcbiAgICAgICAgaG9zdDogJzAuMC4wLjAnLFxuICAgICAgICBobXI6IHtcbiAgICAgICAgICAgIGhvc3Q6ICdza2NvY28uY29tJywgLy9uZXQ6OkVSUl9DRVJUX0FVVEhPUklUWV9JTlZBTElEXHUzMDZCXHUzMDZBXHUzMDhCXHUzMDRCXHUzMDg5XHU4RkZEXHU1MkEwXG4gICAgICAgICAgICBwcm90b2NvbDogJ3dzcycsICAgICAvLyBITVIgXHUzMDgyIEhUVFBTIChXZWJTb2NrZXQgU2VjdXJlKSBcdTMwNjdcdTkwMUFcdTRGRTFcdTMwNTlcdTMwOEJcdTMwODhcdTMwNDZcdTMwNkJcdTMwRDdcdTMwRURcdTMwQzhcdTMwQjNcdTMwRUJcdTMwOTJcdTYzMDdcdTVCOUFcbiAgICAgICAgfSxcbiAgICB9LFxuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgbGFyYXZlbCh7XG4gICAgICAgICAgICBpbnB1dDogW1xuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvc2Fzcy9hcHAuc2NzcycsIC8vIFNhc3NcdTMwRDVcdTMwQTFcdTMwQTRcdTMwRUJcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL2pzL2FwcC5qcycsICAgICAvLyBKYXZhU2NyaXB0XHUzMEQ1XHUzMEExXHUzMEE0XHUzMEVCXG4gICAgICAgICAgICAgICAgLy8ncmVzb3VyY2VzL2Nzcy9hcHAuY3NzJywgICAvLyBDU1NcdTMwRDVcdTMwQTFcdTMwQTRcdTMwRUJcbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICAgICAgLy9kZXRlY3RUbHM6ICdza2NvY28uY29tJyxcbiAgICAgICAgfSksXG4gICAgICAgIHZ1ZSgpLFxuICAgICAgICBWaXRlUFdBKHtcbiAgICAgICAgICAgIHJlZ2lzdGVyVHlwZTogJ2F1dG9VcGRhdGUnLCAvLyBcdTMwQjVcdTMwRkNcdTMwRDNcdTMwQjlcdTMwRUZcdTMwRkNcdTMwQUJcdTMwRkNcdTMwNkVcdTgxRUFcdTUyRDVcdTY2RjRcdTY1QjBcbiAgICAgICAgICAgIHN0cmF0ZWdpZXM6ICdpbmplY3RNYW5pZmVzdCcsXG4gICAgICAgICAgICBzcmNEaXI6ICdzcmMnLCAvLyBcdTMwQUJcdTMwQjlcdTMwQkZcdTMwRTBcdTMwQjVcdTMwRkNcdTMwRDNcdTMwQjlcdTMwRUZcdTMwRkNcdTMwQUJcdTMwRkNcdTMwNkVcdTMwQkRcdTMwRkNcdTMwQjlcdTMwNENcdTY4M0NcdTdEMERcdTMwNTVcdTMwOENcdTMwNjZcdTMwNDRcdTMwOEJcdTMwQzdcdTMwQTNcdTMwRUNcdTMwQUZcdTMwQzhcdTMwRUFcbiAgICAgICAgICAgIGZpbGVuYW1lOiAnc3cuanMnLCAvLyBcdTMwQjVcdTMwRkNcdTMwRDNcdTMwQjlcdTMwRUZcdTMwRkNcdTMwQUJcdTMwRkNcdTMwNkVcdTMwRDVcdTMwQTFcdTMwQTRcdTMwRUJcdTU0MERcbiAgICAgICAgICAgIGluamVjdE1hbmlmZXN0OiB7XG4gICAgICAgICAgICAgICAgc3dTcmM6ICdzcmMvc3cuanMnLCAvLyBcdTMwQUJcdTMwQjlcdTMwQkZcdTMwRTBcdTMwQjVcdTMwRkNcdTMwRDNcdTMwQjlcdTMwRUZcdTMwRkNcdTMwQUJcdTMwRkNcdTMwNkVcdTMwQkRcdTMwRkNcdTMwQjlcdTMwRDVcdTMwQTFcdTMwQTRcdTMwRUJcbiAgICAgICAgICAgICAgICBzd0Rlc3Q6ICdwdWJsaWMvYnVpbGQvc3cuanMnIC8vIFx1ODFFQVx1NTJENVx1NzUxRlx1NjIxMFx1MzA1NVx1MzA4Q1x1MzA4Qlx1MzBCNVx1MzBGQ1x1MzBEM1x1MzBCOVx1MzBFRlx1MzBGQ1x1MzBBQlx1MzBGQ1x1MzA2RVx1NTFGQVx1NTI5Qlx1NTE0OFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIC8vbWFuaWZlc3QuanNvblx1MzAwMFx1MzA2Rlx1NjcyQ1x1NzU2QVx1MzA2OFx1NjkxQ1x1OEEzQ1x1MzA2N1x1NTIwNlx1MzA1MVx1MzA4Qlx1MzA1Rlx1MzA4MVx1MzAwMFx1NTIyNVx1MzBENVx1MzBBMVx1MzBBNFx1MzBFQlxuICAgICAgICAgICAgLypcbiAgICAgICAgICAgIG1hbmlmZXN0OiB7XG4gICAgICAgICAgICAgICAgbmFtZTogcHJvY2Vzcy5lbnYuVklURV9BUFBfTkFNRSB8fCBcIlx1NkI0Q3NoYXJlXCIsXG4gICAgICAgICAgICAgICAgc2hvcnRfbmFtZTogcHJvY2Vzcy5lbnYuVklURV9BUFBfTkFNRSB8fCBcIlx1NkI0Q3NoYXJlXCIsXG4gICAgICAgICAgICAgICAgZGVzY3JpcHRpb246IFwiXHUzMEQ1XHUzMEVDXHUzMEYzXHUzMEM5XHU5NTkzXHUzMDY3XHU5N0YzXHU2OTdEXHUzMDkyXHU1MTcxXHU2NzA5XHUzMDU5XHUzMDhCXHUzMEEyXHUzMEQ3XHUzMEVBXHUzMEIxXHUzMEZDXHUzMEI3XHUzMEU3XHUzMEYzXHUzMDY3XHUzMDU5XHUzMDAyXCIsXG4gICAgICAgICAgICAgICAgc3RhcnRfdXJsOiBcIi9hcHAwMVwiLFxuICAgICAgICAgICAgICAgIGRpc3BsYXk6IFwic3RhbmRhbG9uZVwiLFxuICAgICAgICAgICAgICAgIGJhY2tncm91bmRfY29sb3I6IFwiI2ZmZmZmZlwiLFxuICAgICAgICAgICAgICAgIHRoZW1lX2NvbG9yOiBcIiMwMDAwMDBcIixcbiAgICAgICAgICAgICAgICBpY29uczogW1xuICAgICAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzcmM6IFwiL2FwcDAxL2ltZy9pY29uL2hvbWVfaWNvbl8xOTJfMTkyLnBuZ1wiLFxuICAgICAgICAgICAgICAgICAgICAgICAgc2l6ZXM6IFwiMTkyeDE5MlwiLFxuICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogXCJpbWFnZS9wbmdcIlxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzcmM6IFwiL2FwcDAxL2ltZy9pY29uL2hvbWVfaWNvbl81MTJfNTEyLnBuZ1wiLFxuICAgICAgICAgICAgICAgICAgICAgICAgc2l6ZXM6IFwiNTEyeDUxMlwiLFxuICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogXCJpbWFnZS9wbmdcIlxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICovXG4gICAgICAgICAgICB3b3JrYm94OiB7XG4gICAgICAgICAgICAgICAgZ2xvYlBhdHRlcm5zOiBbXG4gICAgICAgICAgICAgICAgICAgIC8vJyoqLyoue2pzLGNzcyxodG1sLHBuZyxqcGcsc3ZnfScsIC8vIFx1MzBBRFx1MzBFM1x1MzBDM1x1MzBCN1x1MzBFNVx1NUJGRVx1OEM2MVx1MzA2RVx1MzBENVx1MzBBMVx1MzBBNFx1MzBFQlx1MzBEMVx1MzBCRlx1MzBGQ1x1MzBGM1xuICAgICAgICAgICAgICAgICAgICAnKiovKi57anMsY3NzLGh0bWwscG5nLGpwZ30nLCAvLyBcdTMwQURcdTMwRTNcdTMwQzNcdTMwQjdcdTMwRTVcdTVCRkVcdThDNjFcdTMwNkVcdTMwRDVcdTMwQTFcdTMwQTRcdTMwRUJcdTMwRDFcdTMwQkZcdTMwRkNcdTMwRjNcbiAgICAgICAgICAgICAgICBdLFxuICAgICAgICAgICAgICAgIHJ1bnRpbWVDYWNoaW5nOiBbXG4gICAgICAgICAgICAgICAgICAgIC8qXG4gICAgICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHVybFBhdHRlcm46IC9cXC4oPzpodG1sKSQvLCAvLyBIVE1MXHUzMDZFXHUzMEFEXHUzMEUzXHUzMEMzXHUzMEI3XHUzMEU1XHU4QTJEXHU1QjlBXG4gICAgICAgICAgICAgICAgICAgICAgICBoYW5kbGVyOiAnU3RhbGVXaGlsZVJldmFsaWRhdGUnLCAvLyBcdTMwN0VcdTMwNUZcdTMwNkYgJ0NhY2hlRmlyc3QnIFx1MzA2QVx1MzA2OVxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uczoge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhY2hlTmFtZTogJ3N0YXRpYy1hc3NldHMtY2FjaGUnLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGV4cGlyYXRpb246IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbWF4RW50cmllczogNTAsIC8vIFx1NjcwMFx1NTkyN1x1MzBBOFx1MzBGM1x1MzBDOFx1MzBFQVx1NjU3MCBcdTIwM0JcdTMwRDVcdTMwQTFcdTMwQTRcdTMwRUJcdTY1NzBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbWF4QWdlU2Vjb25kczogMjQgKiA2MCAqIDYwLCAvLyAxXHU5MDMxXHU5NTkzXHVGRjA4N1x1NjVFNVx1OTU5M1x1RkYwOVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICAqL1xuICAgICAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgICAgICB1cmxQYXR0ZXJuOiAvXFwuKD86anN8Y3NzKSQvLCAvLyBKYXZhU2NyaXB0LCBDU1NcdTMwNkVcdTMwQURcdTMwRTNcdTMwQzNcdTMwQjdcdTMwRTVcdThBMkRcdTVCOUFcbiAgICAgICAgICAgICAgICAgICAgICAgIGhhbmRsZXI6ICdTdGFsZVdoaWxlUmV2YWxpZGF0ZScsIC8vXG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25zOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY2FjaGVOYW1lOiAnc3RhdGljLWFzc2V0cy1jYWNoZScsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZXhwaXJhdGlvbjoge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXhFbnRyaWVzOiA1MCwgLy8gXHU2NzAwXHU1OTI3XHUzMEE4XHUzMEYzXHUzMEM4XHUzMEVBXHU2NTcwIFx1MjAzQlx1MzBENVx1MzBBMVx1MzBBNFx1MzBFQlx1NjU3MFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXhBZ2VTZWNvbmRzOiAzMCAqIDI0ICogNjAgKiA2MCwgLy8gMzBcdTY1RTVcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICAgICAgdXJsUGF0dGVybjogL1xcLig/OnBuZ3xqcGd8anBlZ3xzdmcpJC8sIC8vIFx1NzUzQlx1NTBDRlx1MzA2RVx1MzBBRFx1MzBFM1x1MzBDM1x1MzBCN1x1MzBFNVx1OEEyRFx1NUI5QVx1NEY4QlxuICAgICAgICAgICAgICAgICAgICAgICAgaGFuZGxlcjogJ0NhY2hlRmlyc3QnLFxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uczoge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhY2hlTmFtZTogJ2ltYWdlLWNhY2hlJyxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBleHBpcmF0aW9uOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1heEVudHJpZXM6IDUwLCAvLyBcdTY3MDBcdTU5MjdcdTMwQThcdTMwRjNcdTMwQzhcdTMwRUFcdTY1NzAgXHUyMDNCXHUzMEQ1XHUzMEExXHUzMEE0XHUzMEVCXHU2NTcwXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1heEFnZVNlY29uZHM6IDMwICogMjQgKiA2MCAqIDYwLCAvLyAzMFx1NjVFNVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICAvKlxuICAgICAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgICAgICB1cmxQYXR0ZXJuOiAvXmh0dHBzOlxcL1xcL2FwaVxcLmV4YW1wbGVcXC5jb21cXC8uKiQvLCAvLyBBUElcdTMwRUFcdTMwQUZcdTMwQThcdTMwQjlcdTMwQzhcdTMwNkVcdTMwQURcdTMwRTNcdTMwQzNcdTMwQjdcdTMwRTVcdThBMkRcdTVCOUFcdTRGOEJcbiAgICAgICAgICAgICAgICAgICAgICAgIGhhbmRsZXI6ICdOZXR3b3JrRmlyc3QnLFxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uczoge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhY2hlTmFtZTogJ2FwaS1jYWNoZScsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZXhwaXJhdGlvbjoge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXhFbnRyaWVzOiAxMCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbWF4QWdlU2Vjb25kczogMjQgKiA2MCAqIDYwLCAvLyAxXHU2NUU1XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgICovXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0pLFxuICAgIF0sXG4gICAgcmVzb2x2ZToge1xuICAgICAgICBhbGlhczoge1xuICAgICAgICAgICAgdnVlOiAndnVlL2Rpc3QvdnVlLmVzbS1idW5kbGVyLmpzJyxcbiAgICAgICAgfSxcbiAgICB9LFxuICAgIGJhc2U6ICcvb3RoZXIvJyxcbiAgICBkZWZpbmU6IHtcbiAgICAgICAgJ3Byb2Nlc3MuZW52LkJBU0VfVVJMJzogSlNPTi5zdHJpbmdpZnkoJy9vdGhlci8nKVxuICAgIH0sXG4gICAgYnVpbGQ6IHtcbiAgICAgICAgcm9sbHVwT3B0aW9uczoge1xuICAgICAgICAgICAgb3V0cHV0OiB7XG4gICAgICAgICAgICAgICAgZW50cnlGaWxlTmFtZXM6ICdbbmFtZV0uW2hhc2hdLmpzJyxcbiAgICAgICAgICAgICAgICBjaHVua0ZpbGVOYW1lczogJ1tuYW1lXS5baGFzaF0uanMnLFxuICAgICAgICAgICAgICAgIGFzc2V0RmlsZU5hbWVzOiAnW25hbWVdLltoYXNoXVtleHRuYW1lXScsXG4gICAgICAgICAgICB9LFxuICAgICAgICB9LFxuICAgIH0sXG59KTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFLQSxTQUFTLG9CQUFvQjtBQUM3QixPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBQ2hCLFNBQVMsZUFBZTtBQUV4QixJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4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
