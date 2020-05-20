<?php session_start();

//로그인이 안되어있다면 로그인되지 않았습니다. 되었다면 로그아웃 구현.
if(!isset($_SESSION) ){
		Header("Location:../index.html");
}
if($_SESSION['id'] && $_SESSION['name'] && $_SESSION['pass']) {
	$_SESSION = array(); // session destory
	echo("
		<script>
		window.alert('로그아웃하였습니다.')
		location.href='../index.html';
		</script>
		");
}
else{
	echo("
	<script>
		location.href='../index.html';
	</script>
	");
}

?>
