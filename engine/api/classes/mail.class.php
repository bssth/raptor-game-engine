<?php

class Mail 
{

    public $method;
    private $smtp;

    public function __construct()
    {
        $this->smtp = $GLOBALS['smtp'];
        $this->method = $GLOBALS['mail_method'];
    }

    public function sendMail($info = array())
    {
        if (!empty($info)) 
		{

            switch ($this->method)
            {
                /**
                 * @todo Сделать поддержку нескольких способов отправки письма
                 */
                case 'mail':
                    return $this->raw_mail($info);
                    break;
                case 'smtp':
                    return $this->smtpmail($info['to'], $info['subject'], $info['message']);
                    break;
                default:
                    return $this->raw_mail($info);
                    break;
            }
        }
    }

    private function raw_mail($info)
    {
        if (mail($info['to'], $info['subject'], $info['message'])) 
		{
            return true;
        } 
		else
		{
            return false;
        }
    }

    private function smtpmail($to, $subject, $message)
    {
        header('Content-Type: text/plain;');
        ob_implicit_flush();
        try {

// Создаем сокет
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket < 0) {
                throw new Exception('socket_create() failed: ' . socket_strerror(socket_last_error()) . "\n");
            }

// Соединяем сокет к серверу
            echo 'Connect to \'' . $this->smtp['smtp_host'] . ':' . $this->smtp['smtp_port'] . '\' ... ';
            $result = socket_connect($socket, $this->smtp['smtp_host'], $this->smtp['smtp_port']);
            if ($result === false) {
                throw new Exception('socket_connect() failed: ' . socket_strerror(socket_last_error()) . "\n");
            } else {
                echo "OK\n";
            }

// Читаем информацию о сервере
            $this->read_smtp_answer($socket);

// Приветствуем сервер
            $this->write_smtp_response($socket, 'EHLO ' . $this->smtp['smtp_username']);
            $this->read_smtp_answer($socket); // ответ сервера

            echo 'Authentication ... ';

// Делаем запрос авторизации
            $this->write_smtp_response($socket, 'AUTH LOGIN');
            $this->read_smtp_answer($socket); // ответ сервера
// Отравляем логин
            $this->write_smtp_response($socket, base64_encode($this->smtp['smtp_username']));
            $this->read_smtp_answer($socket); // ответ сервера
// Отравляем пароль
            $this->write_smtp_response($socket, base64_encode($this->smtp['smtp_password']));
            $this->read_smtp_answer($socket); // ответ сервера

            echo "OK\n";
            echo "Check sender address ... ";

// Задаем адрес отправителя
            $this->write_smtp_response($socket, 'MAIL FROM:<' . $this->smtp['smtp_from'] . '>');
            $this->read_smtp_answer($socket); // ответ сервера

            echo "OK\n";
            echo "Check recipient address ... ";

// Задаем адрес получателя
            $this->write_smtp_response($socket, 'RCPT TO:<' . $to . '>');
            $this->read_smtp_answer($socket); // ответ сервера

            echo "OK\n";
            echo "Send message text ... ";

// Готовим сервер к приему данных
            $this->write_smtp_response($socket, 'DATA');
            $this->read_smtp_answer($socket); // ответ сервера
// Отправляем данные
            $message = "To: $to\r\n" . $message; // добавляем заголовок сообщения "адрес получателя"
            $message = "Subject: $subject\r\n" . $message; // заголовок "тема сообщения"
            $this->write_smtp_response($socket, $message . "\r\n.");
            $this->read_smtp_answer($socket); // ответ сервера

            echo "OK\n";
            echo 'Close connection ... ';

// Отсоединяемся от сервера
            $this->write_smtp_response($socket, 'QUIT');
            $this->read_smtp_answer($socket); // ответ сервера

            echo "OK\n";
        } catch (Exception $e) {
            echo "\nError: " . $e->getMessage();
        }

        if (isset($socket)) {
            socket_close($socket);
        }
    }

// Функция для чтения ответа сервера. Выбрасывает исключение в случае ошибки
    function read_smtp_answer($socket)
    {
        $read = socket_read($socket, 1024);

if ($read{0} != '2' && $read{0} != '3') {
            if (!empty($read)) {
                throw new Exception('SMTP failed: ' . $read . "\n");
            } else {
                throw new Exception('Unknown error' . "\n");
            }
        }
    }

// Функция для отправки запроса серверу
    function write_smtp_response($socket, $msg)
    {
        $msg = $msg . "\r\n";
        socket_write($socket, $msg, strlen($msg));
    }

}
