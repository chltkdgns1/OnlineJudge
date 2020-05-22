#pragma once
#include <iostream>
#include <io.h>
#include <vector>
#include <Windows.h>
#include <string>
using namespace std;


void split(string &a, string b) {
	int len = b.length();
	for (int i = 0; i < len; i++) {
		if (b[i] == '.') break;
		a += b[i];
	}
}

vector<string> getFile(string folder)
{
	vector<string> names;
	WIN32_FIND_DATA fd;
	HANDLE hFind = ::FindFirstFile(folder.c_str(), &fd);
	if (hFind != INVALID_HANDLE_VALUE) {
		do {

			if (!(fd.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
				names.push_back(fd.cFileName);
			}
		} while (::FindNextFile(hFind, &fd));
		::FindClose(hFind);
	}
	return names;
}