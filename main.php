<head>
  <meta content="text/html; charset=utf-8">
</head>
<?php
  $db_user = 'D201702089';
  $db_pwd = 'baechu143';
  $db_sid = "xe";
  $db_charset = "AL32UTF8";
  $conn = oci_connect($db_user, $db_pwd, $db_sid, $db_charset);//데이터 베이스에 접속
  ?>

<!DOCTYPE html>
<html lang ="ko">
<head>
  <meta charset= "utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style> a {text-decoration: none;} </style>
  <title> TP 전자도서관 </title>
</head>
<body>
  <h1> TP 전자도서관 </h2>
  <h2>Login Page</h2>
  <form method="post" action="data/login-check.php">
    <!-- data/login-check.php 페이지에 post형식으로 전송 -->
    <div>
      <input type="text" name = 'id' placeholder="ID(CNO)"><!--  id값은 cno값 -->
    </div>
    <div>
      <input type="text" name = 'pwd' placeholder="PASSWORD">
    </div>
    <div class="botton">
      <button type="submit"> Login </button>
    </div>
  </form>
</body>
