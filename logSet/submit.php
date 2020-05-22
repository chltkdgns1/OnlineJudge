<?php
//회원가입 페이지
//id or password or name이 빈칸이면, 경고창과 함께 이전 페이지로 이동.

$name = $id = $passwd = "";

if(!$_POST["id"] || !$_POST["passwd"] || !$_POST["name"] || !$_POST["cpasswd"]){
	echo("
		<script>
		window.alert('빈칸을 채워주세요.')
		history.go(-1)
		</script>
		");
	exit;
}

$name = $_POST['name'];
$id = $_POST['id'];
$passwd = $_POST['passwd'];
$cpasswd = $_POST['cpasswd'];

for($i = 0 ; $i < strlen($id); $i++){
	if(((ord($id[$i]) >= 48 && ord($id[$i]) <= 57)) || (ord($id[$i])>=65 && ord($id[$i])<=90)
	|| (ord($id[$i]) >= 97 && ord($id[$i]) <=122));
	else{
			echo("
		<script>
			window.alert('아이디에 특수문자 또는 한글은 들어갈 수 없습니다.')
			history.go(-1)
		</script>
		");
		exit;
	}
}


if ($passwd != $cpasswd){
	echo("
		<script>
		window.alert('비밀번호가 일치하지 않습니다.')
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

$servername = "localhost";
$username = "root";
$password = $dbpass;
$dbname = "opent";

$conn = mysqli_connect($servername,$username,$password,$dbname);

$sql="SELECT * FROM user_info WHERE id = '{$_POST['id']}'";

$result=mysqli_query($conn,$sql);


if(mysqli_num_rows($result)){
	echo("
		<script>
		window.alert('중복된 아이디가 존재합니다.')
		history.go(-1)
		</script>
		");
	exit;
}

$sql = "INSERT INTO user_info (id,pass,name) VALUES ('{$id}','{$passwd}','{$name}')";
$result = mysqli_query($conn,$sql);

if($result == false){
	echo("
		<script>
		window.alert('요청에.')
		history.go(-1)
		</script>
		");
	exit;
}

$sql = "CREATE TABLE ".$id." ( pnum int not null, primary key(pnum))";

$result = mysqli_query($conn,$sql);
mysqli_close($conn);

echo("
	<script>
	window.alert('회원가입이 정상적으로 완료되었습니다.')
	location.href='../index.html';
	</script>
");

?>
