・Laravel Breezeを利用して認証を実装する手順
composer require laravel/breeze --devコマンドを実行してLaravel Breezeパッケージを追加する
php artisan breeze:installコマンドを実行してLaravel Breezeパッケージをインストールする
php artisan migrateコマンドを実行してusersテーブルとpassword_reset_tokensテーブルを作成する
npm installコマンドを実行して依存関係をインストールする
npm run buildコマンドを実行してアセット（CSSやJavaScriptなど）をビルドする
・認証を日本語化する手順
日本語化ファイルをダウンロードし、laravel-posting-appフォルダ内のファイルに上書きする。
config/app.phpファイルを開き、'locale'を'ja'（日本）に設定する
resources/langフォルダの中に新しくja.jsonというファイルを作成し、__()ヘルパーで指定されているキーと値（日本語）を設定する
config/app.phpファイルを編集をしたら、次にresourcesフォルダの中にlang/ja.jsonファイルを開き、__()ヘルパー関数で使った内容を日本語に編集をする。

Laravelでメール認証機能を実装するにはMustVerifyEmailインターフェースをつくる
UserモデルクラスにimplementsするだけであとはLaravel側が実装をしてくれる

テーブルの作成手順
マイグレーションファイルを準備する
php artisan make:migration マイグレーションファイル名 --create=作成するテーブル名
マイグレーションファイルを編集し、カラムを追加する
php artisan migrate
その際にforeignId()->constrained()->cascadeOnDelete()メソッドを使ってusersテーブルのレコードが作成されたらpostsテーブルのレコードも削除されるようにする

php artisan make:model モデル名

一対多のリレーションシップの設定方法
外部キーをもつ「多」側のモデルでhasMany()、1側のモデルでhasMany()メソッドを使う

・コントローラ
Authファサードを利用してAuth::user()と記述することで、現在ログイン中のユーザー（Userモデルのインスタンス）を取得できる
アクションの引数でモデルの型宣言を行うことで、アクション内でモデルのインスタンスを直接受け取ることができる
例：public function show(Post $post) { // アクション内の処理 }
・ビュー
アクション内でモデルのインスタンスを受け取る場合、ビュー内のroute()ヘルパ関数の第2引数にモデルのインスタンスを渡す
例：<a href="{{ route('posts.show', $post) }}">詳細</a>
・ルーティング
middleware()メソッドには配列を使って複数のエイリアス（別名、あだ名）を渡すことができる
例：Route::get('/posts', [PostController::class, 'index'])->middleware(['auth', 'verified']);
・テスト
テストクラス内でRefreshDatabaseトレイトのuse宣言を行うことで、テストを実行する度にデータベースをリセットしてくれる
テストクラス内では、ファクトリを使ってダミーデータを生成できる
actingAs()メソッドの引数にUserモデルのインスタンスを渡すことで、「そのユーザーとしてログインする」という振る舞いを実現できる
実務ではどこまで細かく振る舞いを検証するか、人員や工数、納期などを考慮しながら判断するのが一般的である

・コントローラ
アクションの引数でフォームリクエストの型宣言を行うことで、アクションを実行する前に自動的にバリデーションをおこなってくれる
リダイレクト時にwith()メソッドを使い、第1引数にキー名、第2引数に値を指定することで、セッションにそのデータを保存できる
・ビュー
old()ヘルパーの引数にフォームのname属性の値を指定することで、そのフォームの直前の入力値を取得できる（存在しない場合はNULLを返す）
例：<input type="text" id="title" name="title" value="{{ old('title') }}">
・テスト
POSTリクエストを送信するpost()メソッドでは、第2引数に連想配列を指定することでデータを送信できる
