<?php
session_start();
if(!$_SESSION['id'] && !$_SESSION['name'] && !$_SESSION['pass']){
  echo("
    <script>
      window.alert('세션이 만료되었습니다.')
      location.href='index.html';
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
    echo("
       <script>
         window.alert('파일 업로드에 실패하였습니다.')
       </script>
       ");
     exit;
	}
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

$num = $_SESSION['num'];
$_SESSION['num'] = '';

$conn = mysqli_connect($servername,$username,$dbpasswd,$dbname);

date_default_timezone_set('Asia/Seoul');
$timeString = date('Y-m-d H:i:s');

$sql = "UPDATE notedata SET  notedata = '{$_POST['demo-name']}',
category = '{$_POST['demo-category']}' , contents = '{$_POST['demo-message']}',
t = '{$timeString}' WHERE num = {$num}";

$result=mysqli_query($conn,$sql);

if($reslt){
  echo("
      <script>
        window.alert('요청이 실패하였습니다.')
        location.href='notelist.html';
      </script>
      ");
    }
else{
echo("
    <script>
      window.alert('작성이 완료되었습니다.')
      location.href='notelist.html';
    </script>
    ");
}
  exit;
?>
