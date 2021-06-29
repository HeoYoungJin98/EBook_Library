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
    $UserID = $_POST['id'];
    $UserPWD = $_POST['pwd'];
    $ISBN = $_POST['isbn'];
    $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
    $SQL = "DELETE FROM RESERVE WHERE ISBN='$ISBN' AND CNO='$UserID'";
    //RESERVE테이블에서 넘겨받은 isbn과 id(cno)를 통해 값 삭제
    $state = oci_parse($conn,$SQL);
    $result = oci_execute($state);
    ?>
    <h3>예약이 취소되었습니다.</h3>
    <form method="post" action="./MyPage.php">
      <input type=hidden name = 'id' value=<?=$_POST['id']?>>
      <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
      <div class="botton">
        <button type="submit"> 마이페이지로 </button>
      </div>
    </form>

  </body>
