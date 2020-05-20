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
  echo "파일 엑세스를 실패하였습니다";
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



$arr = array();
$conn = mysqli_connect($servername,$username,$dbpasswd,$dbname);


$sql = "SELECT * FROM noterow";
$result=mysqli_query($conn,$sql);


$id = $_SESSION['id'];
$mess = $_POST['demo-message'];
date_default_timezone_set('Asia/Seoul');
$timeString = date('Y-m-d H:i:s');

$noteNum = $_SESSION['x'];

$_SESSION['x'] = "";

$sql = "INSERT INTO noterow(id,mess,t,notenum) VALUES('{$id}','{$mess}','{$timeString}',{$noteNum})";

$result=mysqli_query($conn,$sql);

if($result == false){
  echo("
    <script>
      window.alert('댓글 올리는 것을 실패했습니다.')
      history.go(-1)
    </script>
    ");
}
else{
  echo("
    <script>
      history.go(-1)
    </script>
    ");
  }

?>
