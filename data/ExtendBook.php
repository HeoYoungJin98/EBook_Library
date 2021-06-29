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
  // 도서 연장 페이지//
    $UserID = $_POST['id'];
    $UserPWD = $_POST['pwd'];
    $ISBN = $_POST['isbn'];
    $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
    $SQL = "SELECT DATETIME FROM RESERVE WHERE ISBN = '$ISBN'";
    $state = oci_parse($conn,$SQL);
    $result = oci_execute($state);

    if(($row=oci_fetch_array($state)) != false){//예약한 사람이 있는 도서일 경우
          ?>
          <h3>예약중인 도서이므로 연장할 수 없습니다.</h3>
          <form method="post" action="./MyPage.php">
            <input type=hidden name = 'id' value=<?=$_POST['id']?>>
            <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
            <div class="botton">
              <button type="submit"> 확인 </button>
            </div>
          </form>
          <?php
    }else{//예약한 사람이 없는 도서일 경우.
      oci_free_statement($state);
      $SQL = "SELECT EXTTIMES FROM EBOOK WHERE ISBN='$ISBN'";
      //선택한 도서의 연장 횟수를 가져옴
      $state = oci_parse($conn,$SQL);
      $result = oci_execute($state);
      $row = oci_fetch_array($state);

      if($row[0]<2){//도서의 연장횟수가 2회보다 적은 경우
        oci_free_statement($state);
        $SQL = "UPDATE EBOOK SET DATEDUE = DATEDUE+10, EXTTIMES = EXTTIMES+1 WHERE ISBN='$ISBN'";
        //EBOOK의 반납일을 +10일 하고 연장횟수를 +1함.
        $state = oci_parse($conn,$SQL);
        $result = oci_execute($state);
        ?>
        <h3>연장되었습니다.</h3>
        <form method="post" action="./MyPage.php">
          <input type=hidden name = 'id' value=<?=$_POST['id']?>>
          <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
          <div class="botton">
            <button type="submit"> 확인 </button>
          </div>
        </form>
        <?php
      }else{//도서의 연장횟수가 2회보다 같거나 큰 경우
        ?>
        <h3>최대 연장 가능 횟수를 사용하였습니다.</h3>
        <form method="post" action="./MyPage.php">
          <input type=hidden name = 'id' value=<?=$_POST['id']?>>
          <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
          <div class="botton">
            <button type="submit"> 확인 </button>
          </div>
        </form>
        <?php
      }
    }
    oci_free_statement($state);
    oci_close($conn);
    ?>
</body>
<form method="post" action="./MyPage.php">
  <input type=hidden name = 'id' value=<?=$_POST['id']?>>
  <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
  <div class="botton">
    <button type="submit"> 뒤로가기 </button>
  </div>
</form>
