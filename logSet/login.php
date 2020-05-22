<?php
session_start(); //세션 시작
//아이디가 빈칸이면 경고창과 함께 이전페이지로 이동.

$name = $id = $passwd = "";


	if(!$_POST["id"]){
	echo("
		<script>
		window.alert('아이디를 입력하세요.')
		history.go(-1)
		</script>
		");
	exit;
	}//패스워드가 빈칸이면 경고창과 함께 이전페이지로 이동.
	else if(!$_POST["passwd"]){
		echo("
			<script>
			window.alert('비밀번호를 입력하세요.')
			history.go(-1)
			</script>
			");
		exit;
	}

$id = $_POST['id'];
$pass = $_POST['passwd'];

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];  // 웹 서버의 root 경로

$fp = @fopen("$DOCUMENT_ROOT/pru.txt", "rb");

if(!$fp) {
	echo("
		<script>
		window.alert('액세스 접근에 실패하였습니다.')
		history.go(-1)
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

//입력된 아이디,패스워드와 비교를 위해 기존 계정 정보 찾아오는 sql문
$sql="SELECT * FROM user_info WHERE id = '{$_POST['id']}'";

$result=mysqli_query($conn,$sql);
//mysqli_num_rows($result);
//기존 계정 정보를 찾지 못했다면, 등록되지 않은 계정이므로 경고창과 함께 이전페이지 이동.
$row = mysqli_fetch_assoc($result);

if(mysqli_num_rows($result) != 1){
	echo("
		<script>
		window.alert('등록되지 않은 아이디입니다.')
		history.go(-1)
		</script>
		");
	#$row=
	//불러온 계정 정보의 패스워드와 입력된 패스워드가 다르면 경고창, 이전페이지.
}
else{
	if($row['pass'] != $pass){
		echo("
		<script>
		window.alert('비밀번호가 일치하지 않습니다.')
		history.go(-1)
		</script>
		");
	}
	else{
		$_SESSION['id']=$id;
		$_SESSION['pass'] =$pass;
		$_SESSION['name'] =$row["name"];
		//로그인이 성공한 후.
		$_POST["cnt"] = 1;
		echo("
		<script>
		window.alert('로그인하였습니다.')
		location.href='../index.html';
		</script>
		");
	}
}
mysqli_close($conn);
?>
