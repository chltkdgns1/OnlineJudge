#include <Windows.h>
#include <stdio.h>
#include <iostream>
using namespace std;

int main(){
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	ZeroMemory(&si, sizeof(si));
	si.cb = sizeof(si);

	ZeroMemory(&pi, sizeof(pi));

	// 프로세스생성. 여기"b.exe" 에외부프로그램이름을대입해서사용하면됨

	if (!CreateProcess("C:\\Windows\\System32\\cmd.exe"," /K hello.exe <input.txt >output.txt", NULL, NULL, 0, 0, NULL, NULL, &si, &pi)){ // 30시간 꼴아박아서 모든 경우를 다해본 결과물 ㅎㅎ 시발 
		printf("CreateProcess failed (%d)\n", GetLastError());
		return 1;
	}
	
	cout << "w";

	// Child process 가끝날때까지대기
	WaitForSingleObject(pi.hProcess, INFINITE);
	// 프로세스및핸들close

	cout << "wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww";
	CloseHandle(pi.hProcess);
	CloseHandle(pi.hThread);
	return 0;
}