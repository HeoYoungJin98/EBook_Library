<!DOCTYPE html>
<html lang ="ko">
<head>
  <meta charset= "utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style> a {text-decoration: none;} </style>
  <title> TP 전자도서관 </title>
</head>
<body>
  <h1><a href="../main.php"> TP 전자도서관 </a></h2>
    <?php
    //도서 검색을 위한 페이지
      $UserID = $_POST['id'];
      $UserPWD = $_POST['pwd'];
      $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
      $SQL = "UPDATE EBOOK SET CNO = NULL, EXTTIMES = 0, DATERENTED = NULL, DATEDUE = NULL WHERE
                DATEDUE <= ANY (SELECT DATEDUE FROM EBOOK WHERE DATEDUE+1 < SYSDATE)";
                //데모를 위해 검색창 진입 시 반납 기간이 지난 도서 자동 반납되도록 구현.
                //특정 시간에 작동하는 문장은 Test.php에 구현
      $state = oci_parse($conn, $SQL);
      $result = oci_execute($state);

     ?>
    <h2> 도서 검색 </h2>
    <form method="post" action="./Result_Search.php"><!-- 검색 결과를 Result_Search.php로 post형식으로 전송 -->
      <div>
        <input type="text" name = 'TITLE' placeholder="도서명"><!--도서명을 text타입으로 받음. TITLE로 넘김. -->
      </div>
      <select name = "Option1"><!-- AND,OR,NOT 선택하는 옵션 -->
        <option value="AND">AND</option>
        <option value="OR">OR</option>
        <option value="NOT">NOT</option>
      </select>
      <div>
        <input tye="text" name = 'AUTHORS' placeholder="저자"/>
      </div>
      <select name = "Option2">
        <option value="AND">AND</option>
        <option value="OR">OR</option>
        <option value="NOT">NOT</option>
      </select>
      <div>
        <input type="text" name = 'PUBLISHER' placeholder="출판사"/>
      </div>
      <select name = "Option3">
        <option value="AND">AND</option>
        <option value="OR">OR</option>
        <option value="NOT">NOT</option>
      </select>
      <div>
        <input type="date" name = 'YEAR' placeholder="발행년도">
      </div>
      <div>
        <input type="date" name = 'YEAR2' placeholder="발행년도"/>
      </div>
      <div>
        <input type=hidden name = 'id' value=<?=$_POST['id']?>><!--전 페이지에서 받은 id를 다음 페이지에 id로 넘김 -->
      </div>
      <div>
        <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
      </div>
      <div class="botton">
        <button type="submit"> 검색 </button>
      </div>
    </form>
     <form method="post" action="./login-check.php">
        <input type=hidden name = 'id' value=<?=$_POST['id']?>>
        <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
       <div class="botton">
         <button type="submit"> 뒤로가기 </button>
       </div>
     </form>
</body>
