<?php
session_start();
if(!$_SESSION['id'] && !$_SESSION['name'] && !$_SESSION['pass']){
  echo("
    <script>
      window.alert('세션이 만료되었습니다.')
      location.href='../index.html';
    </script>
    ");
  exit;
}

if(!$_POST['demo-name'] || !$_POST['demo-category']){
 echo("
    <script>
      window.alert('제목 또는 카테고리를 입력해주세요.')
      history.go(-1);
    </script>
    ");
  exit;
}

$file_tmp_name = "";
if($_FILES['files']['name']){
	$file_tmp_name = $_FILES['files']['tmp_name'];
	$dest= "./".$_FILES['files']['name'];
	if(!move_uploaded_file($file_tmp_name, $dest)){
		die("파일을 지정한 디렉토리에 업로드하는데 실패하였습니다.");
	}
}

$id = $_SESSION['id'];

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


$sql = "SELECT * FROM notedata";
$result=mysqli_query($conn,$sql);

/*
for($i = 0; $i < 1000; $i++){
    $arr[$i] = 0;
}

while($row = mysqli_fetch_assoc($result)){
  $arr[$row['num']] = 1;
}

$index = -1;
for($i = 0; $i < 1000; $i++){
    if(!$arr[$i]){
        $index = $i;
        break;
    }
}
*/

date_default_timezone_set('Asia/Seoul');
$timeString = date('Y-m-d H:i:s');

$sql="INSERT INTO notedata(id,notedata,contents,t,file,category)
VALUES('{$id}','{$_POST['demo-name']}','{$_POST['demo-message']}','{$timeString}'
,'{$_FILES['files']['name']}','{$_POST['demo-category']}')";

$result=mysqli_query($conn,$sql);

echo("
    <script>
      window.alert('성공적으로 완료되었습니다.')
      location.href='notelist.html';
    </script>
    ");
  exit;
?>
