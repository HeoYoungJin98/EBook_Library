<?php
//특정 시간이 되면 실행되는 페이지
//자동 예약 취소와 자동 반납의 기능 구현
/*
date_default_timezone_set('Asia/Seoul');
$New_Time = time() + (6 * 60 * 60);
$Hour = date('y/m/d',time());
$Check_Time = date("H:i:ss",time());

if($Check_Time == '00:00:00'){
  $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
  $SQL2 = "DELETE FROM RESERVE WHERE ISBN = ANY(SELECT ISBN FROM EBOOK WHERE DATERENTED IS NULL)
          AND DATETIME = ANY(SELECT MIN(DATETIME) FROM RESERVE GROUP BY ISBN)";
          //대여중이지 않은 책들 중에 예약 시간이 가장 빠른 값 제거
  $state = oci_parse($conn, $SQL2);
  $result = oci_execute($state);
  oci_free_statement($state);
  $SQL3 = "SELECT NAME, EMAIL, E.ISBN FROM RESERVE R, CUSTOMER C, EBOOK E WHERE R.CNO=C.CNO AND R.ISBN=E.ISBN
            AND R.ISBN = ANY(SELECT ISBN FROM RESERVE GROUP BY ISBN)
            AND DATETIME = ANY(SELECT MIN(DATETIME) FROM RESERVE GROUP BY ISBN)
            AND E.DATERENTED IS NULL
            ORDER BY EMAIL";
            //값이 제거 된 후 가장 예약 순번이 빠른 사람에게 이메일 전송.
  $state = oci_parse($conn,$SQL3);
  $result = oci_execute($state);
  $row = oci_fetch_array($state);

  while(($row=oci_fetch_array($state)) != false){
    $mail = new PHPMailer();
    $mail ->isSMTP();
    $ADD = $row['EMAIL'];
    $NAME = $row['NAME'];
    echo $ADD;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->Host =  'smtp.naver.com';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->SMTPAuth = true;
    $mail->Username = 'sd1927';
    $mail->Password = 'zxcv0419';
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('sd1927@naver.com', 'TP_ELIBRARY');
    $mail->addReplyTo('sd1927@naver.com', 'TP_ELIBRARY');
    $mail->addAddress($ADD,$NAME);//받을사람
    $mail->Subject = '예약한 도서를 대여할 차례입니다.';//메일제목
    $mail->msgHTML("예약중인 도서의 대출순번입니다.");
    $mail->AltBody = "예약중인 도서의 대출순번입니다.";
  }
  oci_free_statement($state);
  $SQL = "UPDATE EBOOK SET CNO = NULL, EXTTIMES = 0, DATERENTED = NULL, DATEDUE = NULL WHERE
            DATEDUE <= ANY (SELECT DATEDUE FROM EBOOK WHERE DATEDUE+1 < SYSDATE)";
            //자동 반납 기능.
  $state = oci_parse($conn, $SQL);
  $result = oci_execute($state);
  oci_close($conn);
    echo "Success!";
}else echo "No";
*/
?>
