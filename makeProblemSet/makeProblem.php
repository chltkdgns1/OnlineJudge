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

if(!$_POST['demo-name'] || !$_POST['demo-category'] || !$_POST['demo-contents']
 || !$_POST['demo-input'] || !$_POST['demo-output']
){
 echo("
    <script>
      window.alert('내용이 누락된 부분이 있습니다. 확인해주세요.')
      history.go(-1);
    </script>
    ");
  exit;
}

$name = $_POST['demo-name'];
$category = $_POST['demo-category'];
$contents = $_POST['demo-contents'];
$input = $_POST['demo-input'];
$output = $_POST['demo-output'];
$inputdata = $_POST['demo-inputdata'];
$outputdata = $_POST['demo-outputdata'];

$id = $_SESSION['id'];

$servername="localhost";
$username="root";
$dbpasswd="gkftndlTek12!";
$dbname="opent";

$arr = array();
$conn = mysqli_connect($servername,$username,$dbpasswd,$dbname);


echo $name;
echo $category;
echo $contents;
echo $input;
echo $output;

$a = 0;
$b = 0;
$c = 0.0;
/*
$sql = "INSERT INTO user_info (id,pass,name) VALUES ('{$id}','{$passwd}','{$name}')";
*/

$sql = "INSERT INTO problemsList(name, tag , collectnum , submission , answer)
VALUES('{$name}','{$category}',{$a},{$b},{$c})";

$result = mysqli_query($conn,$sql);


$sql = "SELECT num FROM problemsList WHERE name = '{$name}'";
$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) != 1){
	echo("
		<script>
		window.alert('에러에러 발생')
		history.go(-1)
		</script>
		");
	#$row=
	//불러온 계정 정보의 패스워드와 입력된 패스워드가 다르면 경고창, 이전페이지.
}

$row = mysqli_fetch_assoc($result);

$sql="INSERT INTO problems_data (name,maintext,input,output,linknum,inputdata,outputdata)
VALUES('{$name}','{$contents}','{$input}','{$output}',{$row['num']},'{$inputdata}','{$outputdata}')";

$result=mysqli_query($conn,$sql);



if($result){
  echo("
      <script>
        window.alert('성공적으로 완료되었습니다.')
        location.href='../index.html';
      </script>
      ");
}
else{
echo("
    <script>
      window.alert('실패했습니다.')
      location.href='../index.html';
    </script>
    ");
  }
  exit;

?>
