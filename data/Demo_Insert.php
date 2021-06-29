<!DOCTYPE html>
<html lang ="ko">
<head>
  <meta charset= "utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style> a {text-decoration: none;} </style>
  <title> TP 전자도서관 </title>
</head>
<body>
  <h1><a href="../main.php">TP 전자도서관</a></h1>
  <h2>데모 확인용입니다.</h2>
    <?php
      $UserID = $_POST['id'];
      $UserPWD = $_POST['pwd'];
     ?>
     <form method="post" action="./Demo_Insert_check.php">
         <input type="number" name = 'isbn' placeholder="1012">
         <input type="date" name = 'datedue' placeholder="DATEDUE">
         <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
          <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
       <div class="botton">
         <button type="submit"> 입력 </button><!-- 데모용 대여할 값들 입력. -->
       </div>
     </form>
     <form method="post" action="./login-check.php">
        <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
         <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
       <div class="botton">
        <button type="submit"> 뒤로가기 </button>
        </div>
        </form>
</body>
