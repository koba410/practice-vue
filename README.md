# Practice Vue — Laravel + Vue Docker 練習環境

本業の素 PHP（サーバー側 HTML 生成）に近い **Blade + 部分 Vue マウント** 構成の Laravel 練習環境です。

## 前提

- Docker Desktop（macOS）
- ホストに PHP / Composer / Node は不要

## 重要: Composer の実行場所

`vendor/` はプロジェクト直下をコンテナにマウントしているため、**コンテナ内で `composer` を実行するとホスト側の `vendor/` も同時に更新されます**。

**Composer は必ず Docker コンテナ内で実行してください**（PHP 8.2 環境を揃えるため。ホストの PHP バージョンが異なると不整合が起きます）。

```bash
# 正しい例
docker compose exec app composer install
docker compose exec app composer require barryvdh/laravel-debugbar --dev
```

Artisan も同様に `docker compose exec app php artisan ...` で実行してください（日常コマンド表を参照）。

`vendor_data` ボリュームを使っていた場合は、一度コンテナを再作成してください。

```bash
docker compose down
docker volume rm practice-vue_vendor_data 2>/dev/null || true
docker compose up -d
docker compose exec app composer install
```

## 初回セットアップ

```bash
# 1. コンテナ起動
docker compose up -d --build

# 2. Laravel が未生成の場合（初回のみ）
docker compose exec app composer create-project laravel/laravel /tmp/laravel --prefer-dist --no-interaction
docker compose exec app bash -c 'shopt -s dotglob && cp -a /tmp/laravel/. /var/www/html/'

# 3. .env を Docker 用に設定（.env.example を参照）
cp .env.example .env
docker compose exec app php artisan key:generate

# 4. DB マイグレーション & シード
docker compose exec app php artisan migrate:fresh --seed

# 5. Vue 依存関係インストール
docker compose exec node npm install
docker compose exec node npm install vue @vitejs/plugin-vue

# 6. node コンテナ再起動（Vite dev server）
docker compose restart node
```

## 日常コマンド

| 操作 | コマンド |
|---|---|
| 起動 | `docker compose up -d` または `make up` |
| 停止 | `docker compose down` または `make down` |
| ログ確認 | `docker compose logs -f` または `make logs` |
| Artisan | `docker compose exec app php artisan <command>` |
| Composer | `docker compose exec app composer <command>` |
| npm | `docker compose exec node npm <command>` |
| マイグレーション | `make migrate` |
| シード | `make seed` |
| DB リセット | `make fresh` |

## アクセス URL

| サービス | URL |
|---|---|
| Laravel | http://localhost:8080 |
| デモページ（Blade + Vue） | http://localhost:8080/demo |
| Vite HMR（開発時） | http://localhost:5173 |
| phpMyAdmin | http://localhost:8081 |

## 構成のポイント

### Blade + 部分 Vue（本業に近い）

- **Controller** … DB からデータ取得（素 PHP の `$users = ...` 相当）
- **Blade** … 一覧テーブルをサーバー描画
- **Vue** … 検索フィルタなど動きが必要な部分のみ `createApp().mount()`

デモページ: `resources/views/demo/index.blade.php`  
Vue コンポーネント: `resources/js/components/UserFilter.vue`

### 本番相当の確認

Vite dev server なしで動作確認する場合:

```bash
docker compose exec node npm run build
```

ビルド成果物は `public/build/` に出力され、Apache から配信されます。

## 本業環境の Vue 組み込み方を調べる方法

本業リポジトリで以下を確認すると、練習環境の構成を合わせられます。

1. **HTML ソース**（DevTools → Elements / View Source）
   - `<div id="app">` や `data-v-` 属性 → Vue 使用
   - `<script src="...vue...">` → Vue の読み込み方法
   - `app.js` / `chunk-*.js` → Webpack/Vite 等のバンドル

2. **フロント資産の置き場所**
   - `public/js/`, `assets/`, `static/` 内の `.js` / `.vue`
   - `package.json`, `webpack.config.js`, `vite.config.js` の有無

3. **PHP テンプレートとの接続**
   - `.php` / `.blade.php` 内の `<script>` と `id="..."` の対応
   - PHP 変数を JS に渡すパターン（`json_encode($data)` 等）

4. **API の有無**
   - `fetch('/api/...')` → 非同期 API 型
   - フォーム POST + ページリロード → 従来型（本業に多い）

5. **サーバー情報**
   - `php -v` → PHP バージョン
   - Apache 設定（`.htaccess`, VirtualHost）
   - DB 接続（MySQL/MariaDB）

6. **デプロイフロー**
   - CI/CD や Makefile で `npm run build` があるか

**判断の目安**

- ページごとに PHP が HTML を返し、一部だけ JS が動く → **Blade + 部分 Vue**（この環境）
- 画面遷移なしで URL が `#/` 中心 → SPA 型
- Vue ファイルが無く jQuery のみ → Vue 練習は新規導入パターンの学習

## 技術スタック

| 項目 | バージョン |
|---|---|
| PHP | 8.2 |
| Laravel | 12.x（PHP 8.2 対応版） |
| MariaDB | 10.11 |
| Node | 20 |
| Vue | 3 |
