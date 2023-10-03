<?php
require_once ABSPATH . 'template.php';

begin_html_page('SLO Shogi Koma Design Comp', ['index.css']);
?>

<a id="backButton" href="/">≪<span style="font-size: 6vw;color: grey;">トップへ戻る</span></a><br><br><br><br>
<img src="/public/images/komaCompBanner.jpg">
<br>
<h1>コンペ概要</h1>

<h2>自分がデザインしたオリジナルの駒で対局してみませんか！</h2>

<p class='paragraphText'>自分がデザインした将棋駒で対局したら、将棋愛が爆上がりすること間違いない！
    SLO Shogiはみんなの将棋ライフをよりディープに楽しくするために、本コンペを企画しました。
    <br>是非、駒のデザインを通じて皆さんの将棋観を教えてください！
</p>

<h2>募集要項</h2>
<P class='paragraphText'>
    <span style="color:black">テンプレート:</span><br><br>
    以下のテンプレートをご使用ください（右クリックして画像を保存してください）。
</p>
<img src="/public/images/koma_comp_template.png" href="/koma-comp-template" stlye="width:10vw;">
<p class='paragraphText'>
    <span style="color:black">駒デザインの規格:</span><br><br>
    ・ファイル形式:PNG<br>
    ・サイズ:100px✖️100px<br>
    ・種類:①下記の14種類。玉と王を分けるのも可。<br>
    玉、歩、と金、金、銀、成銀、桂馬、成桂、香車、成香、飛車、龍、角、馬<br>
    　　　　②または、駒背景。<br>
    　　　 文字はテンプレートからこちらで作成します。<br>
    ・その他形式:全ての駒に枠をつけてください。<br>
    　　　　　　　※枠がそのまま将棋盤になります<br>

</p>

<p class='paragraphText'>
    <span style="color:black">デザインについて:</span><br><br>
    ・別の文字に変えてしまってもOKです！<br>
    ・絵にしてしまってもOKです！<br>
    →SLO Shogiをもっと自由なスペースにするため、自由に将棋愛を表現してください！<br><br>

    ※公序良俗に反しない範囲でお願いします。<br><br>
    ※皆様の作品について、SLO Shogi以外で使用いたしません。<br><br>

    <span style="color:black">選考基準:</span><br><br>
    ・Chris & Kikentaroが選考します。<br>
    ・評価基準はズバリ、将棋愛です！<br><br>
    将棋愛が伝わる作品をどしどし応募ください！<br>

</p>
<h2>賞品</h2>
<p class='paragraphText'>
    <span style="color:black">大賞: 1名</span><br>
    大賞作品をプリントした将棋セットを大賞者宛にプレゼントします！<br><br>

    <span style="color:black">参加賞:参加者全員</span><br>
    SLO Shogiで自分が作成した駒デザインを
    　　　選択できるようになります！<br>


</p>

<div id="compForm">
    <h2>デザインをアップロード：</h2>
    <form action="/settings/submit-comp" method="post">
        <label for="uploadFile" required>自作デザインの画像ファイル（.png）を選択</label>
        <input type="file" id="uploadFile" name="uploadFile">
        <br><br>
        <label for="emailAddress">メールアドレス　*必須</label>
        <input type="email" id="emailAddress" name="emailAddress" required>
        <br><br>
        <label for="twitterHandle">Twitter アカウント名</label>
        <input type="text" id="twitterHandle" name="twitterHandle">
        <br><br>
        <input type="submit" value="提出">
    </form>
</div>
<h1><a href="/feedback-form" id="feedback">バグ報告・Report a bug</a></h1>

<?php
end_html_page();
