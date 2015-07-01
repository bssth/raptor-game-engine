<?php

/**
 * Командый процессор Dino
 */
class Dino {

    /**
     * Знак который дает понять Dino что перед ним команда, и надо обрабатывать
     * строку как команду, а не пропускать
     * 
     * @var type 
     */
    public $firstCommandLetter = '/';
    public $_cursor = array();
    public $logging = true;
    public $acwcl = true;

    /**
     * Метод обработки команды, проверяет, является ли строка командой или
     * сообщением в чате, если командой то обрабатывает его, если нет то 
     * пропускает и возвращает false;
     * 
     * @param type $string
     */
    function handleCommand($string = null)
    {
        /**
         * Проверяем, является ли первый знак строки обозначением команды
         */
        if ($string{0} == $this->firstCommandLetter) {
            /**
             * Убираем слэш в начале команды чтобы не мешал
             */
            $cursor = trim($string, $this->firstCommandLetter);
            /**
             * Разбиваем строку на метод и его аргументы
             */
            if (preg_match('/ /', $cursor)) {
                $this->_cursor = explode(' ', $cursor);

                $command = $this->_cursor[0];
            } else {
                $this->_cursor = $cursor;
                $command = $cursor;
            }

            /**
             * Проверяем, есть ли у команды аргументы
             */
            if (method_exists($this, $command)) {
                if (isset($this->_cursor [1]) && is_array($this->_cursor)) {
                    /**
                     * Вызываем функцию с аргументами
                     */
                    $arg[] = ltrim(implode(' ', $this->_cursor), $command);
                    $cursor = call_user_func_array(array($this, $command), $arg);
                } else {
                    $cursor = call_user_func(array($this, $command));
                }
                return $cursor;
            } else {
                echo '<br>Несуществующая команда';
                self::log('Trying to call undefined command');
            }
        } elseif (method_exists($this, explode(' ', $string)[0])) {
            $this->acwcl = false;
            $this->handleCommand('/' . $string);
        }
    }

    /**
     * Выводит время в виде JS alert-а или просто как текст на странице
     * @param type $args
     */
    private static function gettime($args = null)
    {
        if (preg_match('/js/', $args)) {
            self::push(date('H:i'));
        } else {
            print(date('H:i'));
        }
    }

    /**
     * JS сообщение
     * @param type $msg
     */
    private static function push($msg)
    {
        if (!empty($msg)) {
            echo '<script>alert("' . $msg . '");</script>';
            log_error('dino', "Message $msg pushed \n");
        } else {
            die();
        }
    }

    /**
     * Выводит на экран помощь по командам и работе с Dino
     */
    private static function help()
    {
        echo "<h3>Dino - Help</h3>"
        . "<h5>Commands</h5>"
        . "<b>/push</b><i> <message></i> - show js alert with custom message<br>"
        . "<b>/gettime</b> - shows current time. Args: js)<br>"
        . "<b>/ban p=<player> t=<time> r=<reason></b> - ban player";
    }

    private static function ban($msg)
    {
        //Имеем аргументы в виде: p=player t=time r=reason
        //Нужно разложить их в массиве

        if (preg_match('/p=/', $msg) && preg_match('/t=/', $msg) && preg_match('/r=/', $msg)) {
            if (substr($msg, -1) == ' ')
                $msg = substr($msg, 0, -1);
            $arrays = explode(" ", $msg);
            foreach ($arrays as $array)
                $arr[] = explode("=", $array);
            //Выносим из массива данные в переменные
            //Цикл для p (player)
            for ($i = 1; $i < 3; $i++) {
                if ($arr[$i][0] == 'p') {
                    $player = $arr[$i][1];
                    break;
                } else {
                    continue;
                }
            }
            //Цикл для t (time)
            for ($i = 0; $i < 3; $i++) {
                if ($arr[$i][0] == 't') {
                    $time = $arr[$i][1];
                    break;
                } else {
                    continue;
                }
            }
            //Цикл для r (reason)
            for ($i = 1; $i < 4; $i++) {
                if ($arr[$i][0] == 'r') {
                    $reason = $arr[$i][1];
                    break;
                } else {
                    continue;
                }
            }

            if (is_object(CharByName($player))) {
                CharByName($player)->ban = time() + $time;
                CharByName($player)->ban_reason = $reason;
                echo "Персонаж <b>" . $player . "</b> заблокирован. Время бана: " . $time . " секунд";
            } else {
                echo "Персонаж <b>" . $player . "</b> не существует";
            }
        } else {
            echo "Нарушен синтаксис команды (см. <b>/help</b>)";
        }
    }

    private static function unban($args)
    {
        if (is_object(CharByName($args))) {
            CharByName($args)->ban = 0;
            CharByName($args)->ban_reason = "Разблокирован";

            echo "Персонаж <b>" . $args . "</b> разблокирован";
        } else {
            echo "Персонаж <b>" . $args . "</b> не существует";
        }
    }

}
