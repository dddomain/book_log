<?php

//関数宣言
function validate($reviews){
  
}

function createReview()
{
  //ログを登録(入力値を変数に格納)
  echo '読書ログを登録してください'.PHP_EOL;
  echo '書籍名：';
  $title = trim(fgets(STDIN));
  echo '著者名：';
  $author = trim(fgets(STDIN));
  echo '読書状況（未読,読んでいる,読了）：';
  $status = trim(fgets(STDIN));
  echo '評価（5点満点の整数値）：';
  $score = trim(fgets(STDIN));
  echo '感想：';
  $impression = trim(fgets(STDIN));
  echo '登録が完了しました！'.PHP_EOL.PHP_EOL;

  //バリデーション
  $validated = validate($reviews);

  //各変数の値をデータベースに追加
  try{
    $dbh = new PDO(
      'mysql:host=db;dbname=book_log;charset=utf8',
      'book_log',
      'pass',
      array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
      )
    );
  
    echo 'データベースに接続できました。'.PHP_EOL;

    // データの追加 1の最後にで実行
    try{
      //SQLの作成
      $sql = <<<EOT
      INSERT INTO reviews (
        title,
        author,
        status,
        score,
        impression
      )VALUES(
        "{$title}",
        "{$author}",
        "{$status}",
        {$score},
        "{$impression}"
      );
    EOT;

    // SQLの実行
    $res = $dbh->query($sql);
    echo "データベースを追加しました。".PHP_EOL;

    //データ追加の例外処理
    }catch(PDOException $e){
      echo 'データを追加できませんでした。'.PHP_EOL;
      exit($e -> getMessage());
    }
    
  //接続の例外処理
  } catch (PDOException $e) {
    echo 'データベースに接続できませんでした。'.PHP_EOL;
    exit($e -> getMessage());
  }
  //切断
  $dbh = null;
  echo 'データベースから切断しました。'.PHP_EOL;

  //$reviwes配列に、これらの情報をまとめた配列を返すして多次元配列にする
  return [
    'title' => $title,
    'author' => $author,
    'status' => $status,
    'score' => $score,
    'impression' => $impression
  ];
}

function displayReviews($reviews) //スコープが関数内になるので、引数が必要
{
  echo '登録されている読書ログを表示します' .PHP_EOL;

    //ログを表示
    foreach($reviews as $review){
      echo '' .PHP_EOL;
      echo '書籍名：' . $review['title'] .PHP_EOL;
      echo '著者名：' . $review['author'] .PHP_EOL;
      echo '読書状況：' . $review['status'] .PHP_EOL;
      echo '評価：' . $review['score'] .PHP_EOL;
      echo '感想：' . $review['impression'] .PHP_EOL;
      echo '------------------------------' .PHP_EOL;
    };
}

// 配列の初期化
$reviews = [];

//ループ処理
while(true){
  //メニューを表示
  echo '1.読書ログを登録'.PHP_EOL . '2.読書ログを表示' .PHP_EOL . '9.アプリケーションを終了'.PHP_EOL . '番号を選択してください(1,2,9)：';
  //値を取得
  $num = trim(fgets(STDIN));

  //条件分岐
  if($num === '1'){
    //ログを登録
    $reviews[] = createReview();
  }elseif($num === '2'){
    //ログを表示
    displayReviews($reviews); //引数が必要
  }elseif($num === '9'){
    //アプリケーションを終了
    break;
  }

}