<?php
	// костыль на костыле, надо будет это потом перевести на нормальные сокеты. считайте, что это был @todo
	
	if(isset($_GET['delete'])) 
	{
		Database::Remove("chat", array("_id" => toId($_GET['delete'])));
		ob_flush();
		echo "Chat message deleted";
	}
?>

<script>
	
	function timeConverter(timestamp) {
	  var d = new Date(timestamp * 1000),	// Convert the passed timestamp to milliseconds
			yyyy = d.getFullYear(),
			mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
			dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
			hh = d.getHours(),
			h = hh,
			min = ('0' + d.getMinutes()).slice(-2),		// Add leading 0.
			ampm = 'AM',
			time;
				
		if (hh > 12) {
			h = hh - 12;
			ampm = 'PM';
		} else if (hh === 12) {
			h = 12;
			ampm = 'PM';
		} else if (hh == 0) {
			h = 12;
		}
		
		// ie: 2013-02-18, 8:35 AM	
		time = yyyy + '-' + mm + '-' + dd + ', ' + h + ':' + min + ' ' + ampm;
			
		return time;
	}

	function deleteMessage(id) {
		return $.get("/admin/chat", {'delete': id}, function(data) { console.log('Message deleted'); }, "text")
	}
$(function(){
   
    var chat = $('#chat')[0]; // Окно чата
    var form = $('#chat-form')[0]; // форма
    
    // вешаем обработчик на отправку формы
    $('#chat-form').submit(function(event){
        
        // поле ввода
        var text = $(form).find('input[type="text"]');

        // выключаем форму пока не пришел ответ
        $(form).find('input').attr("disabled", true);
        
        // отправка сообщения
        update(text);
        
        // что бы форма не перезагружала страницу
        return false;
    });
    
    function update(text) {
        // что шлём
        var send_data = { last_id: $(chat).attr('data-last-id') };
        if (text)
            send_data.text = $(text).val();
        // шлём запрос
        $.post(
            '/messager/handler',
            send_data, // отдаём скрипту данные
            
            function (data) {
                data = eval('(' + data + ')');
                // ссылка пришла?
                if (data && $.isArray(data)) {
                    $(data).each(function (k) {

                        var msg = $('<div><a target="_blank" href="/admin/find?name=' + data[k].user + '">' + data[k].user + '</a>: ' + data[k].text + ' <b>[<a href="#" onclick="deleteMessage(\''+ data[k]['_id']['$id'] +'\'); this.parentElement.parentElement.innerHTML = \'<Сообщение удалено>\'; return false;">удалить</a>] [' + timeConverter(data[k].date) + ']</b></div>');
                        // и цепляем его к чату
                        $(chat).append(msg);
                        // если ласт ид меньше пришедшего
                        if (parseInt($(chat).attr('data-last-id')) < data[k].date)
                            // запоминаем новый ласт ид
                            $(chat).attr('data-last-id', data[k].date);
                    });
                    
                    // если это отправка, то при получении ответа, включаем форму
                    if (text) {
                        // включаем форму
                        $(form).find('input').attr("disabled", false);
                        // и очищаем текст
                        $(text).val('');
                    }

                    // обновим таймер 
                    update_timer();
                }
            },
            'text' // полученные данные рассматривать как JSON объект
        );
    }

    // что бы при загрузке получить данные в чат, вызываем сразу апдейт
    update();
    
    // что бы окно чата обновлялось раз в 5 секунд, прицепим таймер
    var timer;
    function update_timer() {
        if (timer) // если таймер уже был, сбрасываем
            clearTimeout(timer);
        timer = setTimeout(function () {
            update();
        }, 1000);
    }
    update_timer();
});
</script>

<style>
    #chat { overflow-x:none; overflow-y:scroll; height: 450px; }
</style>

<div id="chat" data-last-id="0"></div>
<form id="chat-form">
    <input type="text" id="chat-msg"/>
    <input type="submit" value="написать"/>
</form>