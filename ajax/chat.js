
var chatManager = new function(){

	var idle 		= true;
	var interval	= 500;
	var xmlHttp		= new XMLHttpRequest();
	var finalDate	= '';

	// Ajax Setting
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
		{
			// JSON 포맷으로 Parsing
			res = JSON.parse(xmlHttp.responseText);
			finalDate = res.date;
			
			// 채팅내용 보여주기
			chatManager.show(res.data);
			
			// 중복실행 방지 플래그 OFF
			idle = true;
		}
	}

	// 채팅내용 가져오기
	this.proc = function()
	{
		// 중복실행 방지 플래그가 ON이면 실행하지 않음
		if(!idle)
		{
			return false;
		}

		// 중복실행 방지 플래그 ON
		idle = false;

		// Ajax 통신
		xmlHttp.open("GET", "proc.php?date=" + encodeURIComponent(finalDate), true);
		xmlHttp.send();
	}

	// 채팅내용 보여주기
	this.show = function(data)
	{
		var o = document.getElementById('list');
		var dt, dd;

		// 채팅내용 추가
		for(var i=0; i<data.length; i++)
		{
			dt = document.createElement('dt');
			dt.appendChild(document.createTextNode(data[i].name));
			o.appendChild(dt);

			dd = document.createElement('dd');
			dd.appendChild(document.createTextNode(data[i].msg));
			o.appendChild(dd);
		}

		// 가장 아래로 스크롤
		o.scrollTop = o.scrollHeight;
	}

	// 채팅내용 작성하기
	this.write = function(frm)
	{
		var xmlHttpWrite	= new XMLHttpRequest();
		var name			= frm.name.value;
		var msg				= frm.msg.value;
		var param			= [];
		
		// 이름이나 내용이 입력되지 않았다면 실행하지 않음
		if(name.length == 0 || msg.length == 0)
		{
			return false;
		}
		
		// POST Parameter 구축
		param.push("name=" + encodeURIComponent(name));
		param.push("msg=" + encodeURIComponent(msg));
				
		// Ajax 통신
		xmlHttpWrite.open("POST", "write.php", true);
		xmlHttpWrite.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttpWrite.send(param.join('&'));
		
		// 내용 입력란 비우기
		frm.msg.value = '';
		
		// 채팅내용 갱신
		chatManager.proc();
	}

	// interval에서 지정한 시간 후에 실행
	setInterval(this.proc, interval);
}