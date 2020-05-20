<?php
session_start(); //세션 시작
//아이디가 빈칸이면 경고창과 함께 이전페이지로 이동.

if(!$_SESSION['id'] && !$_SESSION['name'] && !$_SESSION['pass']){
	echo("
		<script>
		window.alert('세션이 만료되었습니다.')
		<script>
			location.href='index.html';
		</script>
		</script>
		");
	exit;
}

$name = $id = $pass = "";

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

if($id == $_SESSION['id'] && $pass == $_SESSION['pass']){
	header("Location:data2.html");
}
else{
	echo("
		<script>
		window.alert('개인정보가 일치하지 않습니다.')
		history.go(-2)
		</script>
	");
}
