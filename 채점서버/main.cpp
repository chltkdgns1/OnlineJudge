#include <WinSock2.h>
#include <iostream>
#include "findFile.h"
#include "anyData.h"
#include "splitData.h"
#include <string.h>
#include <fstream>
#include <TlHelp32.h>
#include <mysql.h>

using namespace std;
#pragma comment (lib , "ws2_32.lib")

BOOL ProcessAllKill(LPCTSTR szProcessName)
{
	//시스템 프로세스에 대한 전체 스냅샷 찍기
	HANDLE hndl = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);
	DWORD dwsma = GetLastError();
	HANDLE hHandle;

	DWORD dwExitCode = 0;

	//스냅샷 찍은 프로세스에 대한 실행파일 이름, 프로세스 정보, PPID에 대한 정보를 저장한 구조체
	PROCESSENTRY32 procEntry = { 0 };
	procEntry.dwSize = sizeof(PROCESSENTRY32);
	//스냅샷에 첫번째 프로세스를 검색
	Process32First(hndl, &procEntry);

	while (1)
	{
		//실행파일의 이름을 비교하여 같으면 OpenProcess 실행
		if (!lstrcmpi(procEntry.szExeFile, (szProcessName)))
		{
			//존재하는 프로세스 객체 열기(모든접근, 0, PID)
			hHandle = OpenProcess(PROCESS_ALL_ACCESS, 0, procEntry.th32ProcessID);

			//특정 프로세스의 종료 상태 검색(handle, 프로세스 상태값) - return nonzero
			if (GetExitCodeProcess(hHandle, &dwExitCode))
			{
				//특정 프로세스나 그것의 스레드 전부를 종료
				if (TerminateProcess(hHandle, dwExitCode))
				{
					return TRUE;
				}
			}
		}

		//시스템 스냅샷에 등록된 다음 프로세스에 대한 정보를 검색(스냅샷, processEntry32 구조체)
		if (!Process32Next(hndl, &procEntry))
		{
			return FALSE;
		}
	}

	return FALSE;
}

void showError(const char * msg) {
	std::cout << "error : " << msg << std::endl;
	exit(-1);
}

int main() {
	
	/*
	MYSQL conn;
	mysql_init(&conn); //mysql 초기화
	mysql_real_connect(&conn, NULL, "root", "gkftndlTek12!", "opent", NULL, NULL, 0);

	//string qurey = "select count(*) from scoring_status where timeline = '391 ms'";
	//string table = "s1";
	//string id = "tkdgns685";

	//string 	qurey = "insert into s1 (id) values('tkdgns685');";
	string qurey = "UPDATE problemslist SET  answer = if (submission = 0, 0, (collectnum + 1) / submission * 100) , collectnum = collectnum + 1 WHERE num = 1";
	mysql_query(&conn, qurey.c_str());


	
	//MYSQL_RES *res_sql;
	//MYSQL_ROW row_sql;
	//res_sql = mysql_store_result(&conn);
	//row_sql = mysql_fetch_row(res_sql);
	//cout << row_sql[0]<< "\n";

	system("pause");
	
	*/
	
	WSADATA data;
	::WSAStartup(MAKEWORD(2, 2), &data);

	SOCKET server = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);

	if (server == INVALID_SOCKET)
		showError("서버 생성 실패");

	sockaddr_in addr = { 0 };

	addr.sin_family = AF_INET;
	addr.sin_addr.s_addr = inet_addr("127.0.0.1");
	addr.sin_port = htons(23000);

	if (bind(server, (sockaddr *)&addr, sizeof(addr)) == SOCKET_ERROR)
		showError("바인딩 실패");

	if (listen(server, SOMAXCONN) == SOCKET_ERROR)
		showError("듣기 실패");

	MYSQL conn;

	mysql_init(&conn); //mysql 초기화
	mysql_real_connect(&conn, NULL, "x", "x", "x", NULL, NULL, 0);


	while (1) {
		cout << "Start" << "\n";
		string pnum, codes, id, mkdir_s, com_code, exe_code;
		string input, output, num, qurey;
		SOCKET client = accept(server, NULL, NULL);

		memset(buff, 0, sizeof(buff));
		recv(client, buff, 1024, 0);

		split(id, pnum, codes, num, buff);


		cout << "채점하고 있는 id : " << id << "\n";



		mkdir_s = "mkdir " + id;

		system(mkdir_s.c_str());

		ofstream out(id + "//a.cpp");
		out << codes << "\n";
		out.close();

		com_code = "g++ -o " + id + "//a " + id + "//a.cpp";

		int error = system(com_code.c_str());

		cout << "컴파일 중\n";

		if (error != 0) {
			cout << "컴파일 에러\n";
			qurey = "UPDATE scoring_status SET result = '" + ans[2] + "' ,  timeline = '0 ms' WHERE num = " + num;
			mysql_query(&conn, qurey.c_str());
			qurey = "UPDATE user_info SET compile = compile + 1 WHERE id = '" + id + "'";
			mysql_query(&conn, qurey.c_str());
			closesocket(client);
			continue;
		}

		cout << "컴파일 통과\n";

		vector<string> res = getFile(pnum + "\\*.in");

		int size = res.size();
		int time = 0, cnt = 0;


		//qurey = "UPDATE problemslist SET  if submission not 0 answer = (collectnum + 1) / submission * 100, submission = submission + 1 WHERE num = " + pnum;

		qurey = "UPDATE problemslist SET  answer = if (wrongans + collectnum = 0, 0 , collectnum / (wrongans + collectnum)  * 100 ), submission = submission + 1 WHERE num = " + pnum;
		mysql_query(&conn, qurey.c_str());

		
		for (auto a : res) {
	
			cnt++;
			string name;
			split(name, a);

			input = pnum + "//" + a;
			output = id + "//" + name + ".out";

			exe_code = " /K " + id + "\\a.exe <" + input + " >" + output;

			STARTUPINFO si;
			PROCESS_INFORMATION pi;

			ZeroMemory(&si, sizeof(si));
			si.cb = sizeof(si);
			si.lpReserved = NULL;
			si.lpReserved2 = NULL;
			si.cbReserved2 = 0;
			si.lpDesktop = NULL;
			si.lpTitle = NULL;
			si.dwFlags = STARTF_USESHOWWINDOW | STARTF_USESTDHANDLES;
			si.dwX = 0;
			si.dwY = 0;
			si.dwFillAttribute = 0;
			si.wShowWindow = SW_SHOW;

			ZeroMemory(&pi, sizeof(pi));

			if (!CreateProcess((LPCSTR)cmdPath.c_str(), (LPSTR)exe_code.c_str(), NULL, NULL, FALSE, 0, NULL, NULL, &si, &pi)) { // 30시간 꼴아박아서 모든 경우를 다해본 결과물 ㅎㅎ 시발 
				printf("CreateProcess failed (%d)\n", GetLastError());
				qurey = "UPDATE scoring_status SET result = 'process Error' ,  timeline = '0 ms' WHERE num = " + num;
				mysql_query(&conn, qurey.c_str());

				closesocket(client);
				break;
			}


			int a = GetTickCount();
			WaitForSingleObject(pi.hProcess, 2000);
			int t = GetTickCount() - a;

			if (t >= 2000) {
				cout << "시간 초과\n";
				if (ProcessAllKill(TEXT("a.exe"))) { cout << "성공\n"; }
				else cout << "실패\n";
				qurey = "UPDATE scoring_status SET result = '" + ans[3] + "' ,  timeline = 'time Limit' WHERE num = " + num;
				mysql_query(&conn, qurey.c_str());
				qurey = "UPDATE user_info SET timeout = timeout + 1 WHERE id = '" + id + "'";
				mysql_query(&conn, qurey.c_str());
				CloseHandle(pi.hProcess);
				CloseHandle(pi.hThread);
				closesocket(client);

				break;
			}

			cout << "컴파일 시과초과 통과\n";

			CloseHandle(pi.hProcess);
			CloseHandle(pi.hThread);

			cout << pnum << " " << id << "\n";
			int prans = getscore(pnum, id, name + ".out");

			if (prans == 1) {

				cout << "틀렸습니다.\n";
				string time = to_string(t) + " ms";
				qurey = "UPDATE scoring_status SET result = '" + ans[prans] + "' ,  timeline = '" + time + "' WHERE num = " + num;
				mysql_query(&conn, qurey.c_str());

				qurey = "UPDATE user_info SET wa = wa + 1 WHERE id = '" + id + "'";
				mysql_query(&conn, qurey.c_str());

				qurey = "UPDATE problemslist SET  answer = if (wrongans + collectnum = 0, 0, (collectnum) / (wrongans + collectnum + 1) * 100) , wrongans = wrongans + 1 WHERE num = " + pnum;
				mysql_query(&conn, qurey.c_str());
				break;
			}


			cout << "컴파일 시과초과 통과\n";
			time = time < t ? t : time;

			if (size == cnt) {

				cout << "맞았습니다.\n";
				string table = "s" + pnum;
				qurey = "CREATE TABLE IF NOT EXISTS " + table +" (\
					id varchar(32) not null,\
					primary key(id))";

				mysql_query(&conn, qurey.c_str());

				string tt = to_string(time) + " ms";
				qurey = "UPDATE scoring_status SET result = '" + ans[prans] + "' ,  timeline = '" + tt + "' WHERE num = " + num;
				mysql_query(&conn, qurey.c_str());

				qurey = "select count(*) from " + table + " where id = '" + id + "';";
				mysql_query(&conn, qurey.c_str());

				
				MYSQL_RES *res_sql;
				MYSQL_ROW row_sql;
				res_sql = mysql_store_result(&conn);
				row_sql = mysql_fetch_row(res_sql);
								
				if (stoi(row_sql[0])) break;
			
				qurey = "insert into " + table + " (id) values('" + id + "');";
				mysql_query(&conn, qurey.c_str());

				qurey = "insert into " + id + " (pnum) values(" + pnum + ")";
				mysql_query(&conn, qurey.c_str());

				qurey = "UPDATE user_info SET ac = ac + 1 WHERE id = '" + id + "'";
				mysql_query(&conn, qurey.c_str());


				qurey = "UPDATE problemslist SET  answer = if (wrongans + collectnum = 0, 100, (collectnum + 1) / (wrongans + collectnum) * 100) , collectnum = collectnum + 1 WHERE num = " + pnum;
				mysql_query(&conn, qurey.c_str());	

								
				break;
			}

			qurey = "UPDATE scoring_status SET result = '" + to_string(int(double(cnt) / double(size) * 100)) + "%" + "' ,  timeline = ' 0 ms ' WHERE num = " + num;
			mysql_query(&conn, qurey.c_str());	
			cout << "테스트케이스 통과.\n";
		}
		closesocket(client);
	}

	mysql_close(&conn);
	closesocket(server);
	::WSACleanup();
	
	
	return 0;
}

