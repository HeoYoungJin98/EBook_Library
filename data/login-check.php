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
    include "PHPMailer.php";
    include "SMTP.php";

    if(isset($_POST['id'])&&isset($_POST['pwd'])){////로그인 정보 DB에서 체크
      $UserID=$_POST['id'];
      $UserPW=$_POST['pwd'];
      $conn = oci_connect("ID", "password", "xe", "AL32UTF8");
      $SQL = "SELECT * FROM CUSTOMER WHERE CNO = '$UserID' AND PASSWD = '$UserPW'";
      $state = oci_parse($conn,$SQL);
      $result = oci_execute($state);
      $prevPage = $_SERVER['HTTP_REFERER'];

      if(oci_fetch_array($state) == false){//DB에 없을 경우
        header('location:'.$prevPage);//이전 페이지로
      }

      oci_free_statement($state);

      $SQL3 = "SELECT NAME, EMAIL, E.ISBN FROM RESERVE R, CUSTOMER C, EBOOK E WHERE R.CNO=C.CNO AND R.ISBN=E.ISBN
                AND R.ISBN = ANY(SELECT ISBN FROM RESERVE GROUP BY ISBN)
                AND DATETIME = ANY(SELECT MIN(DATETIME) FROM RESERVE GROUP BY ISBN)
                AND E.DATERENTED IS NULL
                ORDER BY EMAIL";
                //대여중이지 않은 책이며 RESERVE 테이블에 있는 책 중 DATETIME이 가장 빠른 책을 예약한 사람의
                //이름과 이메일을 가져옴.

      $state = oci_parse($conn,$SQL3);
      $result = oci_execute($state);
      while(($row=oci_fetch_array($state)) != false){//PHPMailer를 통해 메일을 보냄
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isSMTP();
        $ADD = $row['EMAIL'];
        $NAME = $row['NAME'];
        $IS = $row['ISBN'];
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host =  'smtp.naver.com';
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;
        $mail->Username = 'id';
        $mail->Password = 'password';
        $mail->setFrom('sd1927@naver.com', 'TP_ELIBRARY');
        $mail->addReplyTo('sd1927@naver.com', 'TP_ELIBRARY');
        $mail->addAddress($ADD);//받을사람
        $mail->Subject = 'Time to borrow the book you reserved.';//메일제목
        $mail->msgHTML("Please access the library and check out the book you booked.");
        $mail->AltBody = "Please access the library and check out the book you booked.";
        $mail->Send();
      }
      oci_free_statement($state);
      $SQL2 = "DELETE FROM RESERVE WHERE ISBN = ANY(SELECT ISBN FROM EBOOK WHERE DATERENTED IS NULL)
              AND DATETIME = ANY(SELECT MIN(DATETIME) FROM RESERVE GROUP BY ISBN)";
              //RESERVE 테이블에서 DATETIME이 가장 빠르고, 대여중이지 않은 책 삭제
              //데모를 위해 제작. 시간에 따른 기능은 Test.php에 구현
      $state = oci_parse($conn, $SQL2);
      $result = oci_execute($state);

      oci_free_statement($state);
      oci_close($conn);
    }
  ?>
</h2>
<ol>
  <form method="post" action="./SearchEbook.php">
      <input type=hidden name = 'id' value=<?=$_POST['id']?>>
      <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
    <div class="botton">
      <button type="submit"> 도서 검색 </button>
    </div>
  </form>
  <form method="post" action="./MyPage.php">
    <input type=hidden name = 'id' value=<?=$_POST['id']?>>
    <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
    <div class="botton">
      <button type="submit"> 마이페이지 </button>
    </div>
  </form>
  <form method="post" action="./Demo_Insert.php">
    <input type=hidden name = 'id' value=<?=$_POST['id']?>>
    <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
    <div class="botton">
      <button type="submit"> 자동반납 테스트 </button>
    </div>
  </form>
</ol>

<h3>
  <a href="../main.php">로그아웃</a>
</h3>
</body>
