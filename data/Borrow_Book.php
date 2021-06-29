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
  <h2>
    <?php
    $UserID = $_POST['id'];
    $UserPWD = $_POST['pwd'];
    $ISBN = $_POST['isbn'];
    $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
    $SQL4 = "SELECT * FROM RESERVE WHERE ISBN = '$ISBN'";
    //예약된 도서인지 확인하기 위한 SQL문
    $state = oci_parse($conn,$SQL4);
    $result = oci_execute($state);

    if(oci_fetch_array($state) != false){// 예약중인 도서일 경우
      oci_free_statement($state);
      $SQL5= "SELECT * FROM RESERVE WHERE ISBN = '$ISBN'
              AND DATETIME = ANY(SELECT MIN(DATETIME) FROM RESERVE GROUP BY ISBN)
              AND CNO='$UserID'";
              //사용자가 선택된 책의 1순위 예약자일 경우 결과가 나오고 아닐경우 나오지 않는다.
      $state = oci_parse($conn,$SQL5);
      $result = oci_execute($state);
      if(oci_fetch_array($state) != false){//예약중인 책이고 사용자가 1순위 예약자일 경우
        //일반적인 대출 시도와 같은 상황
        oci_free_statement($state);
        $SQL1 = "SELECT CNO FROM EBOOK WHERE CNO = '$UserID' AND ISBN = '$ISBN'";
        //넘겨받은 id와 isbn을 통해 EBOOK 테이블에서 검색.
        $state = oci_parse($conn,$SQL1);
        $result = oci_execute($state);

        if(oci_fetch_array($state) == false){//Ebook의 cno값이 없다 = 대여중인 책이 아니다.
          //예약중인 책이며 1순위 예약자이고 대여중인 책이 아닐 경우
          oci_free_statement($state);
          $SQL2 = "SELECT CNO FROM EBOOK WHERE CNO = '$UserID'";
          //EBOOK 테이블에 있는 CNO의 값을 받아옴.
          $state = oci_parse($conn,$SQL2);
          $result = oci_execute($state);
          $count = oci_fetch_all($state,$row);
          if($count>=3){//예약중인 책이며 1순위 예약자이고 대여중인 책이 아님.
            //책은 대여중이 아니지만 현재 대여중인 책이 3권 이상일떄
            ?>
            <h5>대여중인 책이 3권이상입니다.<br> 3권 이상 대여할 수 없습니다.</h5>
            <form method="post" action="./SearchEbook.php">
              <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
              <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
              <div class="botton">
                <button type="submit"> 확인 </button>
              </div>
            </form>
            <?php
          }
          else if($count<3){//예약중인 책이며 1순위 예약자이고 대여중인 책이 아님.
            //대여된 책이 3권 미만임.
            oci_free_statement($state);
            $SQL3 = "UPDATE EBOOK SET CNO='$UserID', DATERENTED=SYSDATE, DATEDUE=SYSDATE+10 WHERE ISBN='$ISBN'";
            //EBOOK 테이블의 CNO, DATERENTED,DATEDUE값 업데이트를 통해 대여중임을 표시.
            //대여기간이 10일이기에 DATEDUE값은 현시간에서 10일을 더함.
            $state = oci_parse($conn,$SQL3);
            $result = oci_execute($state);
            oci_commit($conn);

            ?>
            <h3>대출되었습니다.</h3>
            <form method="post" action="./SearchEbook.php">
                <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                <div class="botton">
                  <button type="submit"> 검색창으로 이동 </button>
                </div>
              </form>
              <form method="post" action="./MyPage.php">
                <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                <div class="botton">
                  <button type="submit"> 마이페이지 </button>
                </form>
                <?php
            }
          }else{//책은 예약중이며 본인이 1순위 예약자이지만
          //빌리고자 하는 책이 대여중일때
              ?>
              <h5>대여중인 도서입니다.</h5>
              <form method="post" action="./SearchEbook.php">
                <input type=hidden name = 'id' value=<?=$_POST['id']?>>
                <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
                <div class="botton">
                  <button type="submit"> 뒤로가기 </button>
                </div>
              </form>
              <form method="post" action="./ReserveEbook.php">
                <input type=hidden name = 'id' value=<?=$_POST['id']?>>
                <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
                <input type=hidden name = 'isbn' value=<?=$_POST['isbn']?>>
                <div class="botton">
                  <button type="submit"> 예약하기 </button>
                </div>
              </form>
              <?php
              oci_free_statement($state);
              oci_close($conn);
         }
      }else{//책은 예약된 상태이고 1순위 예약자가 아님.
          ?>
          <h3>예약된 도서입니다.</h3>
          <form method="post" action="./SearchEbook.php">
              <input type=hidden name = 'id' value=<?=$_POST['id']?>>
              <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
              <div class="botton">
                <button type="submit"> 도서검색 </button>
              </div>
            </form>
            <form method="post" action="./ReserveBook.php">
              <input type=hidden name = 'id' value=<?=$_POST['id']?>>
              <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
              <input type=hidden name = 'isbn' value=<?=$_POST['isbn']?>>
              <div class="botton">
                <button type="submit"> 예약 </button>
              </div>
            </form>
            <?php
       }
    }else{//예약중인 도서가 아닐경우
        oci_free_statement($state);
        $SQL1 = "SELECT CNO FROM EBOOK WHERE CNO = '$UserID' AND ISBN = '$ISBN'";
        //넘겨받은 id와 isbn을 통해 EBOOK 테이블에서 검색.
        $state = oci_parse($conn,$SQL1);
        $result = oci_execute($state);

        if(oci_fetch_array($state) == false){//Ebook의 cno값이 없다 = 대여중인 책이 아니다.
          oci_free_statement($state);
          $SQL2 = "SELECT CNO FROM EBOOK WHERE CNO = '$UserID'";
          //EBOOK 테이블에 있는 CNO의 값을 받아옴.
          $state = oci_parse($conn,$SQL2);
          $result = oci_execute($state);
          $count = oci_fetch_all($state,$row);
          if($count>=3){//책은 대여중이 아니지만 현재 대여중인 책이 3권 이상일떄
            ?>
            <h5>대여중인 책이 3권이상입니다.<br> 3권 이상 대여할 수 없습니다.</h5>
            <form method="post" action="./SearchEbook.php">
              <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
              <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
              <div class="botton">
                <button type="submit"> 확인 </button>
              </div>
            </form>
            <?php
          }
          else if($count<3){//대여된 책이 3권 미만이고 대여중인 책이 아닐때
            oci_free_statement($state);
            $SQL3 = "UPDATE EBOOK SET CNO='$UserID', DATERENTED=SYSDATE, DATEDUE=SYSDATE+10 WHERE ISBN='$ISBN'";
            //EBOOK 테이블의 CNO, DATERENTED,DATEDUE값 업데이트를 통해 대여중임을 표시.
            //대여기간이 10일이기에 DATEDUE값은 현시간에서 10일을 더함.
            $state = oci_parse($conn,$SQL3);
            $result = oci_execute($state);
            oci_commit($conn);

            ?>
            <h3>대출되었습니다.</h3>
            <form method="post" action="./SearchEbook.php">
                <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                <div class="botton">
                  <button type="submit"> 검색창으로 이동 </button>
                </div>
              </form>
              <form method="post" action="./MyPage.php">
                <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                <div class="botton">
                  <button type="submit"> 마이페이지 </button>
                </form>
                <?php
              }
            }else{//빌리고자 하는 책이 대여중일때
              ?>
              <h5>대여중인 도서입니다.</h5>
              <form method="post" action="./SearchEbook.php">
                <input type=hidden name = 'id' value=<?=$_POST['id']?>>
                <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
                <div class="botton">
                  <button type="submit"> 뒤로가기 </button>
                </div>
              </form>
              <form method="post" action="./ReserveEbook.php">
                <input type=hidden name = 'id' value=<?=$_POST['id']?>>
                <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
                <input type=hidden name = 'isbn' value=<?=$_POST['isbn']?>>
                <div class="botton">
                  <button type="submit"> 예약하기 </button>
                </div>
              </form>
              <?php
              oci_free_statement($state);
              oci_close($conn);
    }
  }
    ?>
  </h2>
</body>
