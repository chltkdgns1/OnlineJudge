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

if(!$_POST['passwd']){
	echo("
		<script>
		window.alert('변경할 비밀번호를 입력해주세요.')
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

$_SESSION['pass'] = $_POST['passwd'];


$id = $_SESSION['id'];

$servername="localhost";
$username="root";
$dbpasswd=$dbpass;
$dbname="opent";

$conn = mysqli_connect($servername,$username,$dbpasswd,$dbname);

$sql="UPDATE user_info SET pass = '{$_SESSION['pass']}' WHERE id = '{$id}'";

$result=mysqli_query($conn,$sql);

if($result){
	echo("
		<script>
		window.alert('비밀번호를 성공적으로 변경했습니다.')
		location.href = '../index.html'
		</script>
		");
	exit;
}
else{
	echo("
		<script>
		window.alert('비밀번호 변경에 실패했습니다.')
		history.go(-1)
		</script>
		");
	exit;
}
?>
