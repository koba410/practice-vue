---
name: Laravel Vue Docker
overview: 空の `/Users/koba/cursor/practice-vue` に、PHP 8.2 + Apache + MariaDB の Docker 環境を構築し、Laravel 11 プロジェクトを載せる。Vue は本業の素 PHP に近い「Blade でサーバー描画 + 必要な部分だけ Vue をマウント」方式で組み込む。
todos:
  - id: move-workspace
    content: move_agent_to_root で /Users/koba/cursor/practice-vue をワークスペースに設定
    status: in_progress
  - id: docker-files
    content: docker-compose.yml, docker/php/Dockerfile, docker/apache/000-default.conf, entrypoint.sh を作成
    status: completed
  - id: laravel-init
    content: コンテナ起動後に composer create-project で Laravel 11 を生成し .env を MariaDB 向けに設定
    status: in_progress
  - id: vue-setup
    content: Vue 3 + @vitejs/plugin-vue を導入し、Blade + 部分マウントのサンプルページを追加
    status: pending
  - id: readme
    content: README.md に起動手順・日常コマンド・本業環境の調べ方を記載
    status: pending
isProject: false
---

# Laravel + Vue Docker 環境構築プラン

## 方針

本業が**素の PHP（サーバー側で HTML を生成）**であれば、Laravel でも **Blade テンプレート + Vite + Vue（部分的マウント）** が最も近い練習環境です。

| 本業（素 PHP） | この練習環境（Laravel） |
|---|---|
| `include 'header.php'` | `@extends('layouts.app')` / `@include` |
| `<?php foreach ($users as $u): ?>` | `@foreach ($users as $user)`（Blade） |
| ページ末尾に `<script src="app.js">` | `@vite(['resources/js/pages/xxx.js'])` |
| `<div id="search-box">` に JS 連携 | `<div id="search-app">` に `createApp().mount()` |

**避ける構成（本業と差が大きい）**
- Inertia.js + Vue … 画面遷移ごと SPA 化（本業の PHP ページ遷移と異なる）
- Vue 単体 SPA + Laravel API のみ … フロントが完全分離（本業の「PHP が HTML を返す」形と異なる）

```mermaid
flowchart LR
  Browser[Browser]
  Apache[Apache_PHP82]
  Blade[Blade_Templates]
  Vue[Vite_Vue_Components]
  MariaDB[MariaDB]

  Browser -->|HTTP| Apache
  Apache --> Blade
  Blade -->|HTML_shell| Browser
  Blade -->|@vite| Vue
  Vue -->|HMR_dev| Browser
  Apache -->|Eloquent| MariaDB
```

## 作成するディレクトリ構成

[`/Users/koba/cursor/practice-vue`](/Users/koba/cursor/practice-vue)（現状空）に以下を追加:

```
practice-vue/
├── docker-compose.yml
├── docker/
│   ├── php/
│   │   ├── Dockerfile          # php:8.2-apache ベース
│   │   └── entrypoint.sh       # DB 待機 → composer/artisan 初回セットアップ
│   └── apache/
│       └── 000-default.conf    # DocumentRoot = public, AllowOverride All
├── .env.docker                 # Docker 用 DB 接続値の参照元（.env.example に反映）
├── Makefile                    # よく使うコマンド短縮（任意だが便利）
└── README.md                   # 起動手順・本業環境の調べ方
```

Laravel 本体は **コンテナ内で `composer create-project laravel/laravel .`** により生成（ホストに PHP/Composer がなくても可）。

## Docker サービス構成

[`docker-compose.yml`](/Users/koba/cursor/practice-vue/docker-compose.yml) に 4 サービス:

| サービス | イメージ | 役割 | ポート |
|---|---|---|---|
| `app` | 自作 Dockerfile (`php:8.2-apache`) | Laravel + Apache | `8080:80` |
| `node` | `node:20-alpine` | `npm run dev`（Vite HMR） | `5173:5173` |
| `db` | `mariadb:10.11` | MariaDB | `3306:3306` |
| `phpmyadmin` | `phpmyadmin:latest` | DB 管理 UI（練習用） | `8081:80` |

**`app` コンテナの Dockerfile 要点**
- ベース: `php:8.2-apache`
- 拡張: `pdo_mysql`, `mbstring`, `zip`, `bcmath`, `gd`, `intl`
- `a2enmod rewrite` + Apache VirtualHost を `public/` に向ける
- Composer 2 を同梱
- `www-data` 権限を `storage/`, `bootstrap/cache/` に付与

**ボリューム**
- `.:/var/www/html` … ソース同期
- `vendor/` と `node_modules/` は匿名ボリュームでホスト/macOS との権限・パフォーマンス問題を回避

**ネットワーク**
- 全サービスを `practice-vue-network` に接続
- Laravel の `.env` で `DB_HOST=db`, `DB_DATABASE=practice_vue`, `DB_USERNAME=root`, `DB_PASSWORD=secret`

## Laravel + Vue の組み込み（本業に近い形）

Laravel 11 生成後、標準の Vite 連携を使い Vue 3 を追加:

1. `npm install vue @vitejs/plugin-vue`
2. [`vite.config.js`](/Users/koba/cursor/practice-vue/vite.config.js) に Vue プラグイン追加
3. 練習用サンプルを 1 ページ作成:
   - **Controller** … DB から一覧データ取得（素 PHP の `$users = ...` 相当）
   - **Blade** … 一覧の骨格 HTML をサーバー描画
   - **Vue コンポーネント** … 検索フィルタやモーダルなど「動きが必要な部分」のみ
   - Blade から初期データを `@json($users)` で `data-*` 属性に渡す

**Blade 側イメージ**
```blade
{{-- resources/views/demo/index.blade.php --}}
@extends('layouts.app')
@section('content')
  <h1>ユーザー一覧</h1>
  <table>...</table>  {{-- サーバー描画（本業の PHP echo 相当） --}}
  <div id="user-filter-app" data-users='@json($users)'></div>
@endsection
@vite(['resources/js/pages/user-filter.js'])
```

**Vue 側イメージ**
```js
import { createApp } from 'vue'
import UserFilter from '../components/UserFilter.vue'

const el = document.getElementById('user-filter-app')
createApp(UserFilter, { users: JSON.parse(el.dataset.users) }).mount(el)
```

開発時は `node` コンテナで Vite dev server、本番ビルド時は `npm run build` で `public/build/` に出力（Apache から配信）。

## 起動・初回セットアップ手順

1. Cursor ワークスペースを [`/Users/koba/cursor/practice-vue`](/Users/koba/cursor/practice-vue) に移動
2. Docker 関連ファイルを配置
3. `docker compose up -d --build`
4. `docker compose exec app composer create-project laravel/laravel . --prefer-dist`（空ディレクトリ前提）
5. `.env` を Docker 用に更新（`DB_HOST=db` 等）
6. `docker compose exec app php artisan key:generate`
7. `docker compose exec node npm install && npm install vue @vitejs/plugin-vue`
8. Vue/Vite 設定 + サンプルページ追加
9. `docker compose exec app php artisan migrate`
10. ブラウザ確認:
    - Laravel: http://localhost:8080
    - Vite HMR: http://localhost:5173（開発時）
    - phpMyAdmin: http://localhost:8081

## 本業環境の Vue 組み込み方を調べる方法

本業リポジトリ（またはステージング）で以下を確認すると、練習環境の構成を合わせられます:

1. **HTML ソースを見る**（ブラウザ DevTools → Elements / View Source）
   - `<div id="app">` や `data-v-` 属性 → Vue 使用
   - `<script src="...vue...">` や CDN URL → Vue の読み込み方法
   - ビルド済み `app.js` / `chunk-*.js` → Webpack/Vite 等のバンドル利用

2. **フロント資産の置き場所**
   - `public/js/`, `assets/`, `static/` などに `.js` / `.vue` があるか
   - `package.json`, `webpack.config.js`, `vite.config.js` の有無

3. **PHP テンプレートとの接続**
   - `.php` / `.blade.php` / `.twig` 内の `<script>` タグと `id="..."` の対応
   - PHP 変数を JS に渡すパターン（`json_encode($data)` 等）

4. **API の有無**
   - `fetch('/api/...')` や `axios` 呼び出し → 非同期 API 型
   - フォーム POST + ページリロード → 従来型（本業に多い）

5. **サーバー情報**
   - `phpinfo()` または `php -v` → PHP バージョン
   - Apache 設定（`.htaccess`, VirtualHost）→ DocumentRoot と rewrite ルール
   - DB 接続（MySQL/MariaDB, ホスト名）

6. **デプロイフロー**
   - CI/CD や Makefile で `npm run build` があるか → フロントビルドのタイミング

**判断の目安**
- ページごとに PHP が HTML を返し、一部だけ JS が動く → 今回の **Blade + 部分 Vue** が正解
- 画面遷移なしで URL が `#/` や History API 中心 → SPA 型（別構成が必要）
- Vue ファイルが無く jQuery のみ → Vue 練習は新規導入パターンの学習になる

## 注意点

- macOS では Docker Desktop が既に入っているため、そのまま利用可能
- Laravel の `storage/` 書き込み権限は entrypoint で毎回確認
- 本番相当の確認は `npm run build` 後に Apache のみで動作確認する（Vite dev server なし）
