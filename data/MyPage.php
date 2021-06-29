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
  <ol>
<?php
//마이페이지//
//대여중인 책과 예약중인 책 확인
//반납,연장,예약취소기능
  $UserID = $_POST['id'];
  $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
  $SQL = "SELECT * FROM EBOOK WHERE CNO = '$UserID'";
  //EBOOK에서 사용자의 cno와 일치하는 값들 가져옴
  $state = oci_parse($conn,$SQL);
  $result = oci_execute($state);

  echo "<h1> 대여중인 책 </h1>";
  echo "<TABLE border=1>";
  echo "<TR>";
  echo "<TH>ISBN</TH><TH>도서명</TH><TH>출판사</TH><TH>발행년도</TH><TH>CNO</TH><TH>연장횟수</TH><TH>대여날짜</TH><TH>반납날짜</TH><TH>반납</TH><TH>연장</TH>";
  echo "</TR>";

  while(($row = oci_fetch_array($state)) != false){
    ?>
    <TR>
      <TD> <?= $row['ISBN']?> </TD>
      <TD> <?= $row['TITLE']?> </TD>
      <TD> <?= $row['PUBLISHER']?> </TD>
      <TD> <?= $row['YEAR']?> </TD>
      <TD> <?= $row['CNO']?> </TD>
      <TD> <?= $row['EXTTIMES']?> </TD>
      <TD> <?= $row['DATERENTED']?> </TD>
      <TD> <?= $row['DATEDUE']?> </TD>
      <TD> <form method="post" action="./ReturnBook.php">
                    <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                    <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                    <input type="hidden" name = "isbn" value= <?= $row['ISBN']; ?>>
                    <input type="hidden" name = "rented" value = <?=$row['DATERENTED']; ?>>
                  <div class="botton">
                  <button type="submit"> 반납 </button>
                  </div>
                  </form></TD>
      <TD> <form method="post" action="./ExtendBook.php">
                    <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                    <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                    <input type="hidden" name = "isbn" value= <?= $row['ISBN']; ?>>
                  <div class="botton">
                  <button type="submit"> 연장 </button>
                  </div>
                  </form></TD>
    </TR>
    <?php
    //예약중인 도서의 정보 확인
   }
      oci_free_statement($state);
      $SQL2 = "SELECT * FROM RESERVE R, EBOOK E WHERE R.ISBN = E.ISBN AND R.CNO='$UserID'";
      //RESERVE 테이블과 EBOOK 테이블에서 사용자의 아이디와 일치하는 값들 가져옴
      $state = oci_parse($conn,$SQL2);
      $result = oci_execute($state);

      echo "<TABLE border=1>";
      echo "<TR>";
      echo "<TH>ISBN</TH><TH>도서명</TH><TH>출판사</TH><TH>발행년도</TH><TH>예약일</TH><TH>예약취소</TH><TH>대출하기</TH>";
      echo "</TR>";
      ?>
      <h1>예약중인 도서
        <?php
      while(($row = oci_fetch_array($state)) != false){
        ?>
        <TR>
          <TD> <?= $row['ISBN']?> </TD>
          <TD> <?= $row['TITLE']?> </TD>
          <TD> <?= $row['PUBLISHER']?> </TD>
          <TD> <?= $row['YEAR']?> </TD>
          <TD> <?= $row['DATETIME']?> </TD>
          <TD> <form method="post" action="./Cancel_Reserve.php">
                        <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                        <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                        <input type="hidden" name = "isbn" value= <?= $row['ISBN']; ?>>
                      <div class="botton">
                      <button type="submit"> 예약취소 </button>
                      </div>
                      </form></TD>
          <TD> <form method="post" action="./Borrow_Book.php">
                        <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                        <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                        <input type="hidden" name = "isbn" value= <?= $row['ISBN']; ?>>
                      <div class="botton">
                      <button type="submit"> 대출하기 </button>
                      </div>
                    </form></TD>
          </TR>
        </h1>
          <?php
      }
      oci_free_statement($state);
      oci_close($conn);

?>
  </ol>
  <form method="post" action="./login-check.php">
    <input type=hidden name = 'id' value=<?=$_POST['id']?>>
    <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
    <div class="botton">
      <button type="submit"> 뒤로가기 </button>
    </div>
  </form>
  <form method="post" action="./SearchEbook.php">
    <input type=hidden name = 'id' value=<?=$_POST['id']?>>
    <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
    <div class="botton">
      <button type="submit"> 도서검색 </button>
    </div>
  </form>
</body>
