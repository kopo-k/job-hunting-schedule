# 就活スケジュール管理アプリ

就活の予定の被りを信号機カラー（🔴重複 / 🟡間隔狭）で可視化し、面接で答えられなかった
質問を全企業横断でリスト化するWebアプリ（Laravel + MySQL + Docker）。

## 動作環境

- Docker Desktop（Docker Compose v2）

## セットアップ手順

```bash
# 1. 依存パッケージのインストール（初回のみ・Docker経由でPHP不要）
docker run --rm -v "$(pwd)":/var/www/html -w /var/www/html laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# 2. 環境ファイルを用意
cp .env.example .env

# 3. コンテナ起動（app + mysql）
./vendor/bin/sail up -d

# 4. アプリキー生成
./vendor/bin/sail artisan key:generate

# 5. DB初期化スクリプト実行（テーブル作成＋初期データ投入）
./vendor/bin/sail artisan migrate:fresh --seed

# 6. フロントエンドビルド
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

ブラウザで http://localhost を開く。

## デモログイン

| メールアドレス | パスワード |
|---------------|-----------|
| demo@example.com | password |

## データベース

- DBユーザ: `sail`（root以外の一般ユーザ）
- 初期化スクリプト: `database/migrations/`（スキーマ）＋ `database/seeders/DatabaseSeeder.php`（初期データ）
- 再初期化: `./vendor/bin/sail artisan migrate:fresh --seed`

## 主な機能

- 予定のカレンダー管理（FullCalendar.js）と被り検出（🔴重複 / 🟡間隔60分未満）
- 応募企業の管理
- 面接の振り返り（質問・手応え・改善メモ）
- 答えられなかった質問の横断リスト

## コマンド対応表（sail = docker compose）

| 操作 | コマンド |
|------|---------|
| 起動 | `./vendor/bin/sail up -d`（= `docker compose up -d`）|
| 停止 | `./vendor/bin/sail down` |
| テスト | `./vendor/bin/sail artisan test` |

## 技術スタック

PHP / Laravel（Blade）/ Tailwind CSS / FullCalendar.js / MySQL / Docker（Laravel Sail）
