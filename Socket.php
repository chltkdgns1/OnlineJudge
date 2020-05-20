<?php
//The Client
session_start();


echo $GLOBALS['pass'];

exit;

if(empty($_SESSION['id']) || empty($_SESSION['problem_number']) || empty($_POST['demo-codemirror'])){
  echo "에러발생 ㅎ";
  exit;
}
$sourceCode = $_SESSION['id'] .' '. $_SESSION['problem_number']  .' '. $_POST['demo-codemirror'];


echo $sourceCode;

error_reporting(E_ALL);

$address = "127.0.0.1"; // 접속할 IP //
$port = 23000; // 접속할 PORT //
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // TCP 통신용 소켓 생성 //



if ($socket === false) {
    echo "socket_create() 실패! 이유: " . socket_strerror(socket_last_error()) . "\n";
    echo "<br>";
} else {
    echo "socket 성공적으로 생성.\n";
    echo "<br>";
}


echo "다음 IP '$address' 와 Port '$port' 으로 접속중...";
echo "<BR>";

echo "string";

$result = socket_connect($socket, $address, $port); // 소켓 연결 및 $result에 접속값 지정 //
if ($result === false) {
  echo "socket_connect() 실패.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
  echo "<br>";
}
else {
  echo "다음 주소로 연결 성공 : $address.\n";
  echo "<br>";
}

$i = $sourceCode; //보내고자 하는 전문 //
echo "서버로 보내는 전문 : $i|종료|.\n";

socket_write($socket, $i, strlen($i)); // 실제로 소켓으로 보내는 명령어 //
echo "<br>";
$input = socket_read($socket, 1024) or die("Could not read from Socket\n"); // 소켓으로 부터 받은 REQUEST 정보를 $input에 지정 //
echo "<br>";

echo "받기 " . $input; //REQUEST 정보 출력//
socket_close($socket);

if($input == 1){
  echo("
      <script>
        window.alert('맞았습니다.')
        history.go(-1)
       </script>
      ");
}
else if($input == 0){
  echo("
      <script>
        window.alert('틀렸습니다.')
        history.go(-1)
       </script>
      ");
}
else if($input == 2){
  echo("
      <script>
        window.alert('컴파일 에러')
        history.go(-1)
       </script>
      ");
}
else if($input == 3){
  echo("
      <script>
        window.alert('시간초과입니다.')
        history.go(-1)
       </script>
      ");
}
else if($input == 4){
  echo("
      <script>
        window.alert('예상하지 못한 에러가 발생했습니다.')
        history.go(-1)
       </script>
      ");
}
exit;
?>
