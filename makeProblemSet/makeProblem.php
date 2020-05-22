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

$arr = array();
$conn = mysqli_connect($servername,$username,$dbpasswd,$dbname);

$sql = "SELECT id FROM addmin_list WHERE id = '{$id}'";
$result = mysqli_query($conn,$sql);

if($result == false){
  echo("
     <script>
       window.alert('요청에 실패하였습니.')
       history.go(-1);
     </script>
     ");
   exit;
}

if(mysqli_num_rows($result) == 0){
  echo("
     <script>
       window.alert('권한이 없습니다.')
       history.go(-1);
     </script>
     ");
   exit;
}


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

$sql = "INSERT INTO problemslist(name, tag , collectnum ,wrongans, submission , answer)
VALUES('{$name}','{$category}',{$a},{$a},{$b},{$c})";

$result = mysqli_query($conn,$sql);

if($result == false){
  echo("
     <script>
       window.alert('요청에 실패했습니다.')
       history.go(-1);
     </script>
     ");
   exit;
}


$sql = "SELECT num FROM problemslist WHERE name = '{$name}'";
$result = mysqli_query($conn,$sql);

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
       window.alert('요청에 실패했습니다.')
       history.go(-1);
     </script>
     ");
 }
exit;
?>
