<?php

session_start();
if(!$_SESSION['id'] && !$_SESSION['name'] && !$_SESSION['pass']){
  echo("
    <script>
      window.alert('로그인 후 이용해주세요.')
      history.go(-1)
    </script>
    ");
  exit;
}

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];  // 웹 서버의 root 경로

$fp = @fopen("$DOCUMENT_ROOT/pru.txt", "rb");

if(!$fp) {
  echo("
     <script>
       window.alert('액세스에 실패하였습니다.')
       history.go(-1);
     </script>
     ");
   exit;
}

$dbpass = "";
while(!feof($fp)) {
  $dbpass = fgets($fp, 999);
}

$servername="localhost";
$username="root";
$dbpasswd=$dbpass;
$dbname="opent";

$conn = mysqli_connect($servername,$username,$dbpasswd,$dbname);

$num = $_GET['num'];

$sql = "DELETE FROM noterow WHERE num = '{$num}'";
$result=mysqli_query($conn,$sql);

if($result == false){
  echo("
     <script>
       window.alert('액세스에 실패하였습니다.')
       history.go(-1);
     </script>
     ");
   exit;
}

echo("
    <script>
      window.alert('성공적으로 삭제되었습니다.')
      history.go(-1)
     </script>
    ");
exit;
?>
