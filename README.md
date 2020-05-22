</br>

# 온라인 저지

</br>

# 구현 환경
### apache , php , mysql , javacript , css , c++ , MinGW , redirect
</br>

# 구현 기간
# 5일

</br>

# 구현 이유

#### 알고리즘에 평소에도 관심이 많았기 때문에 온라인 저지에도 관심이 갈 수 밖에 없었습니다.
#### 특히, 채점 방식이나 채점 결과를 도출하는데 어떤 소스코드가 필요한지 궁금했습니다.
#### 그래서 초기 도커를 이용해서 오픈 소스를 사용한 결과물을 가지고 구현을 했었습니다.
#### 하지만 이러한 결과물은 수정하기 힘들고 제 입맛에 맞게 구현하기가 까다롭다는 생각을 했고 직접 구현하게 되었습니다.

</br>

#### 구현 환경으로는 apache , php , mysql , javacript , css , c++ , MinGW 가 되겠습니다.
#### apache 는 윈도우 환경에서 서버를 오픈하기 위해 사용하였고 php 는 mysql 과 apache 서버 그리고 apache 서버와 c++ 은 연결해주기 위해 사용했습니다. 
#### 그리고 c++ 로 채점 서버를 구현하였습니다.
#### javacript 는 채점시 % 구간이 동적으로 증가하는 것을 표현해주었는데 공부가 부족한지 속도면에서 효율이 낮은 것으로 보여 현재 미적용 중입니다.
#### css 같은 경우는 템플릿을 가져와서 사용했습니다.

</br>

# 온라인 저지 구현 설명

</br>

#### 이 오픈 소스를 보시는 분이 얼마나 되는지 모르겠고, 사실 올린 파일들도 짜집기해서 올린거라, 사용하시려면 각자의 정리가 필요할 것으로 보입니다. 제가 이렇게 올린 것들은 소스코드를 하나도 빠짐없이 올렸기 때문에 일정 부분은 여러분 나름대로 진행하셨으면 하는 바램입니다.
#### 잡설이 길었고 바로 시작하도록하겠습니다.
#### 기본적으로 html 과 php , css 를 통한 UI 를 구현하였습니다.
#### 따라서 source Code 를 입력할 수 있는 textArea 를 먼저 구현하는게 관건이었습니다.

![image](https://user-images.githubusercontent.com/49302859/82625889-4db85000-9c21-11ea-87db-cd4af8094f4a.png)

#### 위의 사진은 codemirror api 를 이용해서 구현한 모습입니다.
#### 저도 백준님 홈페이지에서 코드 제출하는 부분의 소스 코드에 class? id? 둘 중 하나의 이름이 codemirror 인 것을 보고 구글링을 했는데 이런 좋은 오픈소스가 있다는 것을 깨달았고, 이를 사용했습니다.
</br>
#### 이렇게 입력할 창까지 준비가 되었다면, 소스코드 입력시에 c++ 로 통신할 수 있는 소켓이 필요합니다.
#### http 통신은 단방향이기 때문에 서버에서 쿼리를 받고 ans 를 도출하는 방식입니다. 그렇기 때문에 먼저 다른 서버로 데이터를 보낼 수 가 없습니다.
#### 그래서 사용한 것이 소켓이며, 소켓은 양방향 통신이 가능합니다. 따라서 입력받은 소스와 몇가지 정보를 c++ 서버로 보내주고 보내주면서 php 로 mysql에 데이터를 저장합니다. 
#### (이 부분 수정이 필요한데 소스코드를 mysql 에 넣어서 사용했습니다만, 그렇게 사용하면 안된다는 것을 알고 있습니다.)
#### (이유는 소스코드 내부에 " ", ' ' 들이 사용되는데 sql 쿼리문에 문자열은 ' ' 나 " " 를 감싸줘야하는데 소스코드 내부에 외부랑 동일하게 사용되는 " ", ' ' 가 있다면 쿼리문이 실행되지 않는 오류가 발생했습니다. 이 문제는 추후에 수정하겠습니다.)
</br>
#### 이 상태에서 소스코드는 채점 서버로 넘어가게 되며, 채점 서버에서는 프로세스를 생성해서 채점을 진행하게 됩니다.
#### (현재 서버는 멀티 스레드 구현 방식이 아니라, 동시 처리가 어렵습니다.)
#### 제출한 소스 코드를  MinGW 를 사용해서 exe 파일로 만들어서 실행을 했습니다.
#### 그리고 os 에 내장되어 있는 redirect 를 사용해서 파일 스트림을 표준 스트림으로 바꿔주어 입력했습니다.

#### 이 부분이 중요한데, 보통 온라인 저지 사이트들에서 cin ,cout , scanf , printf 들에 대해서 시간 차이를 체킹합니다.
#### 그리고 fast io 같은 것들이 실제로 정상적으로 작동하는데 그 이유는 redirect 를 이용하기 때문입니다. 
#### in , out 파일을 생성해서 in 파일을 표준 스트림으로 입력받을 수 있습니다. 이것을 적용하기 위해서 걸린 시간이 40시간은 되는 것 같습니다.
#### 이 부분은 소스코드에서도 나와있으니 보면 될 것 같습니다.

</br>

#### 이렇게 exe 파일과 해당 exe 파일 실행을 프로세스로 만들어서 in (테스트케이스) 파일을 exe 가 실행하게 하고 다시 out (테스트케이스 결과) 파일을 생성하게 합니다.
#### 문제의 정답에 해당하는 out 파일과 결과물로 도출된 out 의 string 을 비교해서 답이 같은지 확인합니다.
#### (도중에 mysql 이 있는데, 사실 mysql 의 사용이 미숙해서 입맛에 맞게 변경하시길 바랍니다.)
#### 테스트 케이스의 수 제한은 없고, 채점 시간은 엄청 빠르진 않습니다. 이유는 프로세스가 생성되고 사라지는 시간이 기본 400~700ms 정도 되는 것으로 판단되고 있고, 리눅스의 fork() 와 exec() 을 통한 구현이 훨씬 빠를 것이라는 예상입니다.
#### 실제로 백준님은 리눅스의 fork() 와 exec() 계통의 함수를 사용하셔서 구현했습니다.

</br>

#### 컴파일 에러는 system 함수로 minGW 를 목적파일로 만들 때 생기는 에러로 결과값을 도출할 수 있었고, 시간 초과에서는 무한 루프를 돌거나 정해진 시간을 초과하면 시간초과가 발생하게 했습니다.
#### 여기서 문제점은 cmd 를 통해서 redirect 를 진행하기 때문에 실제 실행되는 exe 파일은 자식의 자식 프로세스라고 생각할수도 있지만, 채점 서버가 부모라면 부모 -> 자식 -> ? 가 됩니다. 즉, 저희가 실행하는 exe 파일은 사실 자식 프로세스가 아닌 외부 프로세스라는 것입니다. 그 이유는 부모가 cmd를 실행하면 cmd 에 대한 자식프로세스 정보가 부모한데 전달되고 cmd 는 다시 exe 를 실행하는 구조기 때문에 (공부가 부족해서 얻어올 수 있는 방법이 있는지 모르지만) exe 는 외부 프로그램에 해당합니다. 그래서 시간 초과 시에 자식 프로세스만 종료시키면 exe 파일은 사라지지 않고 무한루프를 돌고 있습니다. (ㅎㄷㄷ) 그렇기 때문에 os 위에 돌고 있는 exe 프로그램을 강제 종료해주는 소스코드가 필요하고 실제로 삽입해서 사용했습니다. 

</br>

# 추가 사항
#### 추가 사항은 많지만 추후 개발 예정은 없습니다.
#### 이정도로 만족해버렸습니다... 이걸 정말 사용하진 않은 것이기 때문에..
#### 그 이유는 샌드박스를 들 수 있는데, 실제 제 채점 서버는 샌드박스 기술이 적용되있지 않아서 제출하는 소스코드에 악성 소스코드를 집어넣어 버리면, 초유의 사태가 발생할 수 있습니다. 이 부분에 대한 공부가 더 진행된다면 정말 서버로 사용해보겠습니다. ㅎㅎ


</br>

#### 현재 서버는 닫혀있고, 앞으로도 닫혀있을 예정입니다. 따라서 실행화면만 youtube로 올리도록하겠습니다.

https://youtu.be/GCqekSJ0W-I
https://youtu.be/Sc5pD_WSOik











