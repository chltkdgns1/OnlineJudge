#pragma once
#include <string>
#include <iostream>
#include <fstream>
using namespace std;

void split(string &id, string &a, string &b, string &num, char*c) {
	int ch = 0;
	for (int i = 0; c[i] != NULL; i++) {
		if (ch >= 3) {
			b += c[i];
			continue;
		}
		else if (ch == 2 && c[i] != ' ') a += c[i];
		else if (ch == 1 && c[i] != ' ') num += c[i];
		else if (c[i] != ' ') id += c[i];

		if (c[i] == ' ') ch++;
	}
}

int getscore(string a, string b,string output) {
	ifstream file1(a + "//" + output);
	ifstream file2(b + "//" + output);

	if (!file1.is_open()) return 3;
	if (!file2.is_open()) return 3;
	
	string s, ans; char c;
	while (file1.get(c)) s += c;
	while (file2.get(c)) ans += c;

	file1.close();
	file2.close();

	cout << s << "1\n";

	if (s == ans) return 0;
	if (s + ' ' == ans) return 0;
	if (s + '\n' == ans) return 0;
	if (s == ans + ' ') return 0;
	if (s == ans + '\n') return 0;
	return 1;
}