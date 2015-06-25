function Highlight(code){
	var comments	= [];	// Тут собираем все каменты
	var strings		= [];	// Тут собираем все строки
	var res			= [];	// Тут собираем все RegExp
	var all			= { 'C': comments, 'S': strings, 'R': res };
	var safe		= { '<': '<', '>': '>', '&': '&' };

	return code

		.replace(/[<>&]/g, function (m)
			{ return safe[m]; })

		.replace(/\/\*[\s\S]*\*\//g, function(m)
			{ var l=comments.length; comments.push(m); return '~~~C'+l+'~~~';   })
		.replace(/([^\\])\/\/[^\n]*\n/g, function(m, f)
			{ var l=comments.length; comments.push(m); return f+'~~~C'+l+'~~~'; })

		.replace(/\/(\\\/|[^\/\n])*\/[gim]{0,3}/g, function(m)
			{ var l=res.length; res.push(m); return '~~~R'+l+'~~~';   })

		.replace(/([^\\])((?:'(?:\\'|[^'])*')|(?:"(?:\\"|[^"])*"))/g, function(m, f, s)
			{ var l=strings.length; strings.push(s); return f+'~~~S'+l+'~~~'; })

		.replace(/(var|function|typeof|new|return|if|for|in|while|break|do|continue|switch|case)([^a-z0-9\$_])/gi,
			'<span class="kwrd">$1</span>$2')

		.replace(/(\{|\}|\]|\[|\|)/gi,
			'<span class="gly">$1</span>')

		.replace(/([a-z\_\$][a-z0-9_]*)[\s]*\(/gi,
			'<span class="func">$1</span>(')

		.replace(/~~~([CSR])(\d+)~~~/g, function(m, t, i)
			{ return '<span class="'+t+'">'+all[t][i]+'</span>'; })

		.replace(/\n/g,
			'<br/>')

		.replace(/\t/g,
			'&nbsp;&nbsp;&nbsp;&nbsp;');
}

setTimeout( function() { alert("Если вы пишите код прямо в браузере, рекомендуем перебраться в Notepad++ - он более надёжный. Потом можно будет скопировать и вставить сюда"); }, 25000);