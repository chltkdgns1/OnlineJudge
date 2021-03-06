<?php
//The Client
session_start();

if(empty($_SESSION['id']) || empty($_SESSION['problem_number']) || empty($_POST['demo-codemirror'])){

  exit;
}


$id = $_SESSION['id'] ;
$pnum =  $_SESSION['problem_number'] ;
$sourceCode = $_POST['demo-codemirror'];

if(empty($id) || empty($pnum) || empty($sourceCode)){
  echo("
    <script>
      window.alert('잘못된 접근입니다.')
      history.go(-1)
    </script>
    ");
  exit;
}

error_reporting(E_ALL);

$address = "127.0.0.1"; // 접속할 IP //
$port = 23000; // 접속할 PORT //
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // TCP 통신용 소켓 생성 //


if ($socket === false) {
    echo "socket_create() 실패! 이유: " . socket_strerror(socket_last_error()) . "\n";
}


$result = socket_connect($socket, $address, $port); // 소켓 연결 및 $result에 접속값 지정 //
if ($result === false) {
  socket_strerror(socket_last_error($socket));
  echo("
    <script>
      window.alert('액세스에 실패했습니다.')
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
      window.alert('액세스에 실패했습니다.')
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

$sql = "SELECT MAX(num) as num FROM scoring_status";
$result=mysqli_query($conn,$sql);

if($result == false){
  echo("
    <script>
      window.alert('요청에 실패했습니다.')
      history.go(-1)
    </script>
    ");
  exit;
}

$row = mysqli_fetch_assoc($result);

$num = $row['num'] + 1;

$i = $id .' '.$num.' '.$pnum .' '. $sourceCode;

socket_write($socket, $i, strlen($i)); // 실제로 소켓으로 보내는 명령어 //

socket_close($socket);


$wait = '기다리는 중...';
$time = 'Infinity ms';

$sql = "INSERT INTO scoring_status(id,pnum,result,timeline,source,nowtime)
    VALUES('{$id}',{$pnum},'{$wait}','{$time}','{$sourceCode}',NOW())";


$result=mysqli_query($conn,$sql);


echo("
      <script>
        window.alert('제출 성공')
        location.href='scoreStatus.html?num=$pnum'
      </script>
    ");

  /*
  echo("
        <script>
          window.alert('제출 성공')
          location.href='scoreStatus.html?num=$pnum'
        </script>
      ");
      */
?>
