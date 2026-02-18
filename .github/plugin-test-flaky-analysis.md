# プラグインテスト flaky test 調査メモ

## 対象

- GitHub Actions Run: https://github.com/EC-CUBE/ec-cube/actions/runs/22138996632/job/63999701489
- ブランチ: `fix/plugin-test-flaky-waitfortext`
- テスト: `EA10PluginCest` の `_local` 系テスト全般

## 現象

- **全ての `_local` テストが失敗、全ての `_store` テストは成功**
- フレーキーに落ちる（常に落ちるわけではない）
- 失敗時のスクリーンショットには `plugin already installed.` エラーが表示される

## 根本原因

2つの問題が重なっている:

### 1. `waitForText` がページ遷移をまたぐと検出に失敗する（フレーキー）

`PluginLocalInstallPage::アップロード()` 内で、フォーム送信（POST）直後に `waitForText('プラグインをインストールしました。', 120, PluginManagePage::完了メッセージ)` を呼んでいた。

ローカルプラグインのインストールはフォームPOSTによるフルページナビゲーション（`/store/plugin/install` → 302リダイレクト → `/store/plugin`）を伴う。WebDriver/ChromeDriver のページ遷移ハンドリングにより、`waitForText` がリダイレクト後のページのフラッシュメッセージを検出できないことがある。

**サーバーサイドログで確認した事実:**
- POST `/admin/store/plugin/install` は約4秒で正常完了（composer は含まない）
- リダイレクト先 GET `/admin/store/plugin` も正常に完了
- しかし `waitForText` は120秒タイムアウトしてテキストを見つけられなかった

**Store テストが成功する理由:**
Store テストではページ遷移を伴わない同一ページ上の操作（AJAX等）後にフラッシュメッセージを `waitForText` で待つため、この問題が発生しない。

### 2. リトライが逆効果

`nick-invision/retry@v3` で `max_attempts: 2` を設定していたが:

1. 1回目: プラグインインストールは成功（DBに登録済み）→ `waitForText` がタイムアウトで失敗
2. 2回目（リトライ）: `_before()` はプラグインのクリーンアップをしない → 再度アップロードすると「plugin already installed.」エラー → 必ず失敗

キャプチャされるスクリーンショットは2回目（リトライ）のもので、「plugin already installed.」エラーが表示される。

## 修正方針

1. **リトライを削除** — DBクリーンアップなしのリトライは逆効果
2. **`waitForText` をページ遷移後に移動** — `PluginLocalInstallPage::アップロード()` からフラッシュメッセージ確認を削除し、`PluginManagePage::at()` でページ遷移完了を確認した後に `Local_Plugin::インストール()` 内で確認する
3. ページ遷移の検出は `PluginManagePage::at()` の `waitForText($pageTitle, 30, '.c-pageTitle')` に任せる（`.c-pageTitle` は遷移前後のページ両方に存在するが、テキスト内容が異なるため正しいページを検出できる）

## 技術的な補足

- `AcceptanceTester::click()` は click 後に `wait(1)` を自動挿入する
- `AcceptanceTester::waitForText()` は呼び出し前に `wait(0.1)` を自動挿入する
- ローカルプラグインのインストールは composer を含まない（サーバー処理は約4秒）
- CSSセレクタ `PluginManagePage::完了メッセージ` とフラッシュメッセージのテキストは正しい（テンプレート・翻訳ファイルで確認済み）
