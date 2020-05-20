<?php session_start();

//세션값이 있다면 세션값 출력
if(isset($_SESSION)){
	echo "\$_SESSION['id'] 값: " . $_SESSION["id"] . "<br>";
	echo "\$_SESSION['passwd'] 값: " . $_SESSION["passwd"] . "<br>";
	echo "\$_SESSION['name'] 값: " . $_SESSION["name"] . "<br>";
	echo "<p style='text-align: center;'>환영합니다. " . $_SESSION["id"] . "님</p>";
	echo "<p style='text-align: center;'><a href='logout.php'>로그아웃 하기</a></p>";
}
else{
	echo "<p style='text-align: center;'>로그인되지 않았습니다.</p>";
	echo "<p style='text-align: center;'><a href='login_form.html'>로그인하기</a></p>";
}
?>
