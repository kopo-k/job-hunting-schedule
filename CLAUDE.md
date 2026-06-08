# CLAUDE.md

このリポジトリで作業するときの指針。

## プロジェクト概要

就活スケジュール管理アプリ。就活の面接・インターン・説明会の予定を一元管理し、
**予定の被りを信号機カラー（🔴重複 / 🟡間隔狭）で可視化**する。
さらに面接で実際にされた質問を記録し、**うまく答えられなかった質問を全企業横断でリスト化**して次に活かす。

大学の期末課題（**DB必須・Docker必須**）。

詳細設計: `docs/superpowers/specs/2026-06-08-job-hunting-schedule-design.md`

## 技術スタック

- PHP / Laravel（Blade によるサーバーサイドレンダリング）＋ Tailwind CSS
- カレンダーUI: FullCalendar.js（予定をJSONで渡して描画、信号機カラーを割り当て）
- MySQL
- 認証: Laravel Breeze
- インフラ: Docker（Laravel Sail：app / mysql の2コンテナ）

## アーキテクチャ方針

- Blade モノリス構成。API+SPA にはしない（期末課題には過剰なため）。
- データはユーザーごとに完全分離（マルチユーザー）。クエリには必ず `user_id` のスコープを効かせる。

## 主要データモデル

- `users`（Breeze標準）
- `companies`：応募先企業（user_id, name, status, memo）
- `events`：予定（user_id, company_id?, title, type, start_at, end_at, location）
- `interview_questions`：面接の質問記録（user_id, event_id, question, result, improvement_memo）

## 被り検出ロジック

- 🔴 赤（重複）: `A.start_at < B.end_at AND B.start_at < A.end_at`
- 🟡 黄（移動リスク）: 重複なし かつ 前予定終了〜次予定開始の間隔が 60分未満
- 優先順位: 赤 > 黄 > 通常

## 開発時の注意

- 言語: 返答・コメント・コミットメッセージは日本語。
- コミットに `Co-Authored-By` や AI 共著表記を入れない。
- PR を勝手にマージしない（マージはユーザーが手動で行う）。
- スコープ外（今回作らない）: メール/プッシュ通知、外部カレンダー連携、質問ランキング、スコアのグラフ可視化。
