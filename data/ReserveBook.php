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
    //예약을 동작시키기 위한 페이지
      $UserID = $_POST['id'];
      $UserPWD = $_POST['pwd'];
      $ISBN = $_POST['isbn'];
      $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
      $SQL1 = "SELECT count(cno) FROM RESERVE WHERE cno='$UserID'";
      //RESERVE 테이블에서 사용자의 CNO와 같은 값들의 수를 가져옴
      $state = oci_parse($conn,$SQL1);
      $result = oci_execute($state);
      $count = oci_fetch_array($state);

      if($count[0] >= 3){//예약중인 책이 3권 이상일 경우

        ?>
        <h3>예약중인 책이 3권입니다.</h3>
        <?php
        $SQL2 = "SELECT E.ISBN, E.TITLE, E.PUBLISHER, E.YEAR, R.DATETIME FROM RESERVE R, EBOOK E
        WHERE R.ISBN= E.ISBN AND R.CNO='$UserID'";
        //RESERVE와 EBOOK에서 사용자의 CNO와 같은 값 가져옴 예약중인 도서의 정보 출력
        $state = oci_parse($conn,$SQL2);
        $result = oci_execute($state);

        echo "<TABLE border=1>";
        echo "<TR>";
        echo "<TH>ISBN</TH><TH>도서명</TH><TH>출판사</TH><TH>발행년도</TH><TH>예약날짜</TH><TH>예약취소</TH>";
        echo "</TR>";
        ?><h4>예약중인 도서
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
            </TR>
          </h4>
            <?php
          }
      }else{//예약중인 책 3권 미만일 경우
        // 예약중인 도서의 정보와 함께 예약 버튼 생성
        oci_free_statement($state);
        $SQL2 = "SELECT E.ISBN, E.TITLE, E.PUBLISHER, E.YEAR, R.DATETIME FROM RESERVE R, EBOOK E
        WHERE R.ISBN= E.ISBN AND R.CNO='$UserID'";
        //RESERVE와 EBOOK에서 사용자의 CNO와 같은 값 가져옴 예약중인 도서의 정보 출력
        $state = oci_parse($conn,$SQL2);
        $result = oci_execute($state);

        echo "<TABLE border=1>";
        echo "<TR>";
        echo "<TH>ISBN</TH><TH>도서명</TH><TH>출판사</TH><TH>발행년도</TH><TH>예약날짜</TH><TH>예약취소</TH><TH>대출하기</TH>";
        echo "</TR>";
        ?><h4>예약중인 도서
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
          </h4>
            <?php
            //예약을 위해 선택한 도서의 정보
        }
        oci_free_statement($state);
        $SQL3 = "SELECT * FROM EBOOK WHERE ISBN='$ISBN'";
        //넘겨받은 ISBN을 통해 선택한 도서의 정보 출력
        $state = oci_parse($conn,$SQL3);
        $result = oci_execute($state);
        echo "<TABLE border=1>";
        echo "<TR>";
        echo "<TH>ISBN</TH><TH>도서명</TH><TH>출판사</TH><TH>발행년도</TH><TH>예약</TH>";
        echo "</TR>";
        ?>
        <h4>선택한 도서
          <?php
        while(($row = oci_fetch_array($state)) != false){
          ?>
          <TR>
            <TD> <?= $row['ISBN']?> </TD>
            <TD> <?= $row['TITLE']?> </TD>
            <TD> <?= $row['PUBLISHER']?> </TD>
            <TD> <?= $row['YEAR']?> </TD>
            <TD> <form method="post" action="./Reserve.php"><!--예약이 실행되는 페이지-->
                          <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                          <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                          <input type="hidden" name = "isbn" value= <?= $row['ISBN']; ?>>
                        <div class="botton">
                        <button type="submit"> 예약하기 </button>
                        </div>
                        </form></TD>
            </TR>
          </h4>
            <?php
        }

      }
      oci_free_statement($state);
      oci_close($conn);
     ?>
</body>
<h5>
<form method="post" action="./SearchEbook.php">
   <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
    <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
  <div class="botton">
   <button type="submit"> 뒤로가기 </button>
   </div>
   </form></TD>
 </h5>
