# submission_manager

### PHP 学習用 APP

teacher と student で提出物管理を行う APP

##### DB構造
- https://drive.google.com/file/d/1Ylt0ujqT6bC6yxtghruXLtg9ZAAPm6R_/view?usp=sharing


##### 機能
1. student

- 過去の提出物を教科別に参照可能。
- 1 週間前後の提出物が Home に表示される。
- teacher 側がつけた評価が確認できる。
- 年度が変わると新しい学年を登録できる。

2. teacher

- 提出物を各クラスごとに作成できる。
- 提出物の評価を入力できる。
- 生徒を学年、クラス、氏名で検索できる。
- 生徒情報を編集できる。
- 年度が変わるとクラスの登録ができる。

### 参考
- ちゃんと学ぶ、PHP+MySQL（MariaDB）入門講座
  https://www.udemy.com/course/php7basic/

- これだけは知っておきたい！Smarty の使い方
  https://qiita.com/sano1202/items/1f49f407f310f2e493ff

- GD を利用したサムネイル画像の作成方法
  https://webkaru.net/php/image-thumbnail/

### 学習順序
1. 機能要件をもとにDBのテーブル構成を考える、ワイヤーフレームを作成。
2. PHPのみで作成、DBへの接続やMAMPの設定、非公開フォルダ、環境変数などの設定。
3. テンプレートエンジンSmartyを使いPHP, htmlを分離して記載。
4. MVC構造を理解するためにDB周りの処理をmodelDirへ分けてクラス化。<-イマココォ