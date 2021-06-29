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
    <?php
    //예약이 동작하는 페이지
      $UserID = $_POST['id'];
      $UserPWD = $_POST['pwd'];
      $ISBN = $_POST['isbn'];
      $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
        $SQL1 = "INSERT INTO Reserve VALUES($ISBN, $UserID,SYSDATE)";
        //RESERVE 테이블에 cno,isbn,현재시간 입력 
        $state = oci_parse($conn, $SQL1);
        $result = oci_execute($state);
        ?>
        <h3>예약되었습니다.</h3>
        <form method="post" action="./MyPage.php">
           <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
            <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
          <div class="botton">
           <button type="submit"> 확인 </button>
           </div>
           </form>
           <?php
      oci_free_statement($state);
      oci_close($conn);
      ?>
      <form method="post" action="./SearchEbook.php">
         <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
          <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
        <div class="botton">
         <button type="submit"> 도서검색 </button>
         </div>
         </form>
</body>
