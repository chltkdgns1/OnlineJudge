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

$num = $_GET['num'];

if($num == 0){
	echo("
		 <script>
			 window.alert('액세스하는데 실패하였습니다.')
			 history.go(-1);
		 </script>
		 ");
	 exit;
}

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];  // 웹 서버의 root 경로

$fp = @fopen("$DOCUMENT_ROOT/pru.txt", "rb");

if(!$fp) {
	echo("
     <script>
       window.alert('액세스하는데 실패하였습니다.')
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

$sql = "SELECT * FROM notedata WHERE num = {$num}";
$result=mysqli_query($conn,$sql);

if($result == false){
	echo("
		 <script>
			 window.alert('요청에 실패하였습니다.')
			 history.go(-1);
		 </script>
		 ");
	 exit;
}

$row = mysqli_fetch_assoc($result);


if(strcmp($row['id'],$_SESSION['id'])){
echo("
		<script>
		window.alert('잘못된 접근입니다.')
		location.href='notelist.html';
		</script>
		");
	exit;
}

$sql = "DELETE FROM noterow WHERE notenum = {$num}";
$result=mysqli_query($conn,$sql);

if($result == false){  echo("
     <script>
       window.alert('요청에 실패하였습니다.')
       history.go(-1);
     </script>
     ");
   exit;}

$sql = "DELETE FROM notedata WHERE num = {$num}";
$result=mysqli_query($conn,$sql);

if($result == false){  echo("
     <script>
       window.alert('요청에 실패하였습니다.')
       history.go(-1);
     </script>
     ");
   exit;}

echo("
		<script>
		window.alert('성공적으로 삭제했습니다.')
		location.href='notelist.html';
		</script>
		");
exit;
?>
