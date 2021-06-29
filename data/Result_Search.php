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
    <h2><a href= "../data/login-check.php"> 도서 검색 </a></h2>
    <form method="post" action="./Result_Search.php">
      <div>
        <input type="text" name = 'TITLE' placeholder="도서명">
      </div>
      <select name = "Option1">
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
        <input type=hidden name = 'id' value=<?=$_POST['id']?>>
      </div>
      <div>
        <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
      </div>
      <div class="botton">
        <button type="submit"> 검색 </button>
      </div>
    </form>

    <?php
    // 검색 결과를 나타내기 위한 페이지//
    $conn=oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
    $TITLE = $_POST['TITLE'];
    $AUTHORS = $_POST['AUTHORS'];
    $PUBLISHER = $_POST['PUBLISHER'];
    $YEAR_1 = $_POST['YEAR'];
    $YEAR_2 = $_POST['YEAR2'];
    $Option1 = $_POST['Option1'];
    $Option2 = $_POST['Option2'];
    $Option3 = $_POST['Option3'];


    /* 조건이 없거나 한 개의 조건이 입력 */
    if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE  E.ISBN = A.ISBN AND E.TITLE LIKE '%$TITLE%'"; //제목만 입력된 경우
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0 && $Option1 != "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND A.AUTHOR LIKE '%$AUTHORS%'";//저자만 입력된 경우
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0 && $Option1 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND A.AUTHOR != '$AUTHORS'";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 != "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND E.PUBLISHER LIKE '%$PUBLISHER%'";//출반사만 입력된 경우
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == 'NOT'){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND E.PUBLISHER != '$PUBLISHER'";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option3 != "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2)";//날짜만 입력된 경우
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option3 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2)";
    }

    /* TITLE과 AUTHORS 두 개의 조건이 입력 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0 && $Option1 == "AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0 && $Option1 == "OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) ==0 && $Option1 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%')";
    }

    /* TITLE과 PUBLISHER 두 개의 조건이 입력 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == "AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == "OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER != '%$PUBLISHER%')";
    }

    /* TITLE과 YEAR 두 개의 조건이 입력 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option3 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    /* AUTHORS와 PUBLISHER 두 개 조건이 입력 */
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == "AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == "OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option2 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%')";
    }

    /* AUTHORS와 YEAR 두 개 조건이 입력 */
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option3 == "AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option3 == "OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option3 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    /* PUBLISHER와 YEAR 두 개 조건이 입력*/
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option3 == "AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option3 == "OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option3 == "NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    /* TITLE과 PUBLISHER, AUTHORS 세 개 조건이 입력 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "AND" && $Option2 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER = '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "AND" && $Option2 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "AND" && $Option2 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER = '%$PUBLISHER%')";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "NOT" && $Option2 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "NOT" && $Option2 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "NOT" && $Option2 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%')";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "OR" && $Option2 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "OR" && $Option2 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%')";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) ==0 && $Option1 == "OR" && $Option2 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%')";
    }

    /* TITLE과 AUTHORS, YEAR 3개 조건이 입력 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "AND" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "AND" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "AND" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "NOT" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "NOT" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "NOT" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "OR" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "OR" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) == 0 && strlen($YEAR_1) !=0 && $Option1 == "OR" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }

    /* TITLE과 PUBLISHER, YEAR 3개 조건을 선택 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "AND" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "AND" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "AND" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "NOT" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "NOT" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER != '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "NOT" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "OR" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "OR" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) == 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1 == "OR" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }

    /* AUTHORS와 PUBLISHER, YEAR 3개의 검색 조건 사용 */
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "AND" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "AND" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "AND" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }

    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "NOT" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "NOT" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "NOT" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "OR" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "OR" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) == 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option2 == "OR" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }

    /* 4개의 검색 조건을 모두 사용한 경우 */
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "AND" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "NOT" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "OR" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "AND" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "AND" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "NOT" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "NOT" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "OR" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "AND" && $Option2 == "OR" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "AND" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "NOT" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR !!= '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "OR" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "AND" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "AND" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "NOT" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "NOT" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "OR" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "NOT" && $Option2 == "OR" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' AND A.AUTHOR != '%$AUTHORS%' OR E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "AND" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "NOT" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR != '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "OR" && $Option3 =="AND"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "AND" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "AND" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "NOT" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "NOT" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' AND E.PUBLISHER != '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "OR" && $Option3 =="OR"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' OR (E.YEAR>=$YEAR_1 AND E.YEAR<=$YEAR_2))";
    }
    else if(strlen($TITLE) != 0 && strlen($AUTHORS) != 0 && strlen($PUBLISHER) != 0 && strlen($YEAR_1) !=0 && $Option1== "OR" && $Option2 == "OR" && $Option3 =="NOT"){
      $SQL = "SELECT * FROM EBOOK E, AUTHORS A WHERE E.ISBN = A.ISBN AND (E.TITLE LIKE '%$TITLE%' OR A.AUTHOR LIKE '%$AUTHORS%' OR E.PUBLISHER LIKE '%$PUBLISHER%' AND (E.YEAR<$YEAR_1 OR E.YEAR>$YEAR_2))";
    }

    $state = oci_parse($conn,$SQL);
    $result = oci_execute($state);

    $ID= $_POST['id'];
    $PWD= $_POST['pwd'];

    echo "<h1> 도서 조회 결과   </h1>";
    echo "<TABLE border=1>";
    echo "<TR>";
    echo "<TH>ISBN</TH><TH>도서명</TH><TH>저자</TH><TH>출판사</TH><TH>발행년도</TH><TH>CNO</TH><TH>연장횟수</TH><TH>대여날짜</TH><TH>반납날짜</TH><TH>대출</TH><TH>예약</TH>";
    echo "</TR>";

    while(($row = oci_fetch_array($state)) != false){
      ?>
      <TR>
        <TD> <?= $row['ISBN']?> </TD>
        <TD> <?= $row['TITLE']?> </TD>
        <TD> <?= $row['AUTHOR']?> </TD>
        <TD> <?= $row['PUBLISHER']?> </TD>
        <TD> <?= $row['YEAR']?> </TD>
        <TD> <?= $row['CNO']?> </TD>
        <TD> <?= $row['EXTTIMES']?> </TD>
        <TD> <?= $row['DATERENTED']?> </TD>
        <TD> <?= $row['DATEDUE']?> </TD>
        <TD> <form method="post" action="./Borrow_Book.php">
                      <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
                      <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
                      <input type="hidden" name = "isbn" value= <?= $row['ISBN']; ?>>
                    <div class="botton">
                    <button type="submit"> 대출 </button>
                    </div>
                    </form></TD>
        <TD> <form method="post" action="./ReserveBook.php">
                      <input type= "hidden" name = "id" value= <?= $_POST['id']; ?>>
                      <input type= "hidden" name = "pwd" value=<?= $_POST['pwd']; ?>>
                      <input type= "hidden" name = "isbn" value=<?= $row['ISBN']; ?>>
                    <div class="botton">
                    <button type="submit"> 예약 </button>
                    </div>
                    </form></TD>
        </TR>
        <?php
    }
    oci_free_statement($state);
    oci_close($conn);
    ?>

    <form method="post" action="./login-check.php">
       <input type=hidden name = 'id' value=<?=$_POST['id']?>>
       <input type=hidden name = 'pwd' value=<?=$_POST['pwd']?>>
      <div class="botton">
        <button type="submit"> 뒤로가기 </button>
      </div>
    </form>
    <form method="post" action="./MyPage.php">
       <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
        <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
      <div class="botton">
       <button type="submit"> 마이페이지 </button>
     </form>
</body>
