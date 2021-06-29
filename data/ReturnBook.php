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
  //도서 반납 페이지
    $UserID = $_POST['id'];
    $Book = $_POST['isbn'];
    $RTD = $_POST['rented'];
    $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
    $SQL1 = "INSERT INTO PREVIOUSRENTAL VALUES($Book,'$RTD',SYSDATE,$UserID)";
    //반납시 PREVIOUSRENTAL 테이블에 ISBN,DATERENTED, CNO 값 입력
    $state = oci_parse($conn,$SQL1);
    $result = oci_execute($state);

    oci_free_statement($state);
    $SQL2 = "UPDATE EBOOK SET CNO = NULL, DATERENTED = NULL, DATEDUE = NULL, EXTTIMES = 0
     WHERE ISBN = '$Book'";
     //또한 EBOOK의 CNO,DATERENTED, DATEDUE값을 NULL로 EXTTIMES를 0으로 만들며 대여중이지 않음을 표시
    $state = oci_parse($conn,$SQL2);
    $result = oci_execute($state);
    oci_free_statement($state);
    oci_close($conn);
   ?><h3>반납되었습니다.</h3>
   <form method="post" action="./MyPage.php">
     <input type=hidden name = 'id' value=<?=$_POST['id']?>>
     <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
     <div class="botton">
       <button type="submit"> 확인 </button>
     </div>
   </form>
</body>
