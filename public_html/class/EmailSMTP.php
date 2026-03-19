<?php
final class EmailSMTP{

	/* ИСПОЛЬЗОВАНИЕ

		О правильности и корректности данных, нужно позаботится заранее
		класс отправляет то что есть


		$email = EmailSMTP::instance();
		$email->to(MAIL,NAME);
		$email->from(MAIL,NAME);
				$email->reply(MAIL,NAME);
		$email->subject("Сообщение");
		$email->body('<p>MESSAGE</p>');
		$err = $email->send();
		if ($err == 0){
			$item->error = "По технической причине сообщение отправить не удалось. Свяжитесь с администратором по телефону " ;
			error_log("The letter was not sent due to technical errors. Login ".$_POST["login"]);
		} else {
			$_SESSION['message_sent']='send';
			header("Location:".SERVER_HOST."/contact/");
			exit;
		}


	*/

/*	TODO
	нужно ли это?
			$this->headers["Sender"]=$this->mailfrom;
		$this->headers["Errors"]=ADMIN_MAIL;
		$this->headers["Return-path"]=ADMIN_MAIL;
		$this->headers["Reply-To"]=$this->mailfrom;
		$this->headers["Content-Base"]=SERVER_HOST;*/

    private static $_instance;

    private $_smtpServer;
    private $_smtpPort;
    private $_smtpTimeOut = 10;
    private $_smtpUsername;
    private $_smtpPassword;
    private $_postServer;

    private $_to;// только емайл
	private $_to_header;//для заголовков

    private $_from;
	private $_from_header;

    private $_reply;
	private $_reply_header;

    private $_subject;
    private $_body;
    private $_header;

    private function __construct(){
        $this->setSettings(SMTP_LOGIN, SMTP_PASS, SMTP_PROTOCOL."://".SMTP_SERVER, SMTP_PORT);
    }

    private function __clone(){}

    # инстанциализирующий метод
    public static function instance(){
        if(empty(self::$_instance))
            self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * Установка настроек
     * @param $smtpUsername string  Логин пользователя от почты
     * @param $smtpPassword string  Пароль от почтового ящика
     * @param $smtpServer string  Почтовый сервер для отправки писем
     * @param $smtpPort string  Порт SMTP соединений
     * @return $this object  Собственный объект
     */
    private function setSettings($smtpUsername, $smtpPassword, $smtpServer, $smtpPort){
        $this->_smtpUsername = base64_encode($smtpUsername);
        $this->_smtpPassword = base64_encode($smtpPassword);
        $this->_smtpServer = $smtpServer;
        $this->_smtpPort = $smtpPort;
        if($this->_smtpServer=='tcp://localhost'){
            $this->_postServer='localhost';
        }else{
            preg_match("/([a-z]+)\.([a-z]+)$/ui", $smtpServer, $postServer);
            $this->_postServer = $postServer[0];
        }


        return $this;
    }


	/*
		Готовим заголовки
	*/
	private function prepare_email_header($email, $name = ''){
			if($name != '')
		   		return "=?utf-8?B?" . base64_encode($name) . "?= <" . $email . ">\r\n";
			else
				return $email . "\r\n";

	}

    /**
     * Кому
     *
     * @param string $email string E-mail адресата
     * @param string $name Имя адресата, по умолчанию ''
     *
     * TODO проверки? ошибки?
     */
    public function to($email, $name = ''){
	   if($email!=''){
 			$this->_to_header = $this->prepare_email_header($email, $name);
			$this->_to= $email;
	   }
    }

    /**
     * От кого
     *
     * @param string $email E-mail пользователя, отправившего письмо
     * @param string $name Имя пользователя, по умолчанию ''
     */
    public function from($email, $name = ''){
	   if($email!=''){
 			$this->_from_header = $this->prepare_email_header($email, $name);
			$this->_from= $email;
	   }
    }

    /**
     * Кому ответить
     *
     * @param string $email E-mail пользователя для ответа
     * @param string $name Имя пользователя для ответа, по умолчанию ''
     *
     */
    public function reply($email, $name = ''){
	   if($email!=''){
 			$this->_reply_header = $this->prepare_email_header($email, $name);
			$this->_reply= $email;
	   }
    }

    /**
     * Тема письма
     *
     * @param string $subject Тема письма
     * @return object $this Собственный объект
     */
    public function subject($subject){
        $this->_subject = "=?utf-8?B?".base64_encode($subject)."?=\r\n";
        return $this;
    }

    /**
     * Тело письма
     *
     * @param string $data Сообщение письма. Данный параметр может содержать, HTML теги
     * @return object $this Собственный объект
     */
    public function body($data){
        $this->_body = $data;

        return $this;
    }

    /**
     * Сбор заголовка
     *
     * @returt boolean/string Отправляется строка при успешном сборе заголовка, иначе false
     */
    private function getHeaderGenerate(){
        if(empty($this->_to) and empty($this->_from) and empty($this->_subject))
            return false;

        # Дата отправки письма
        $this->_header = "Date: ".date("D, j M Y G:i:s")." +0400\r\n";

        # Название почтовой программы, инициирующая отправку
        $this->_header .= "X-Mailer: ZakPHPMailer(v.1.2)\r\n";

        # От кого
        $this->_header .= "From: ".$this->_from_header;

        # Кому
        $this->_header .= "To: ".$this->_to_header;


        # Для автоответа
        $this->_header .= "Reply-To: ";

        if(empty($this->_reply)){
			$this->_header .= $this->_from_header;
        } else {
			$this->_header .= $this->_reply_header;
        }

        # Приоритет письма
        $this->_header .= "X-Priority: 3 (Normal)\r\n";

        # Тема письма
        $this->_header .= "Subject: " . $this->_subject;

        # Тип, версия, кодировка
        $this->_header .= "MIME-Version: 1.0\r\n";
        $this->_header .= "Content-Type: text/html; charset=utf-8\r\n";
        $this->_header .= "Content-Transfer-Encoding: 8bit\r\n";

        return $this->_header;
    }

    /**
     * Чтение ответа сервера
     *
     * @param resource $smtpConnect Указатель подключения
     * @return string $data Строка с ответом сервера
     */
    private function getReplyServer($smtpConnect){
        $data = "";
        while($str = fgets($smtpConnect, 515)){
            $data .= $str;
            if(substr($str,3,1) == " ")
                break;
        }

        return $data;
    }

    /**
     * Отправка почты
     *
     * @return int Если 0 - ошибка техническая (нет подключения в серверу, не верные логины и пароли и т.д.), если -1 - ошибка ползовательская (введен не верные E-mail), если 1 - отправка письма прошла успешно
     */
    public function send(){
        # Собираем заголовок
        $header = $this->getHeaderGenerate();

        # Если заголовок не собран, то пишем в лог ошибку
        if($header == false and empty($this->_body)){
            error_log("Ne dostatochno dannyh dlya otpravki pis'ma. Proverte vypolnenie metodov to(), from(), subject(), body()");
            return 0;
        }

        # Подключаемся к серверу
        $connectionMailServer = fsockopen(
            $this->_smtpServer,
            $this->_smtpPort,
            $errno,
            $errstr,
            $this->_smtpTimeOut);

        # Ошибка пишется в лог если нет подлючения
        if(!$connectionMailServer) {
            error_log("Oshibka soedineniya s serverom: " . $errno . " - " . $errstr);
            //fclose($connectionMailServer); зачем закрывать неоткрытое
            return 0;
        }

        $this->getReplyServer($connectionMailServer);

        fputs($connectionMailServer, "EHLO " . $this->_postServer . "\r\n");
        $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

        if($replyServer != 250) {
            error_log("Oshibka privetstviya s pochtovym serverom ELHO");
            fclose($connectionMailServer);
            return 0;
        }

        /**
         * для локального тестирования авторизацию не проводим
         */
        if($this->_smtpServer!='tcp://localhost'){

            fputs($connectionMailServer, "AUTH LOGIN\r\n");
            $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

            if($replyServer != 334) {
                error_log("Ot pochtovogo servera poluchen zapret na avtorizaciyu AUTH LOGIN");
                fclose($connectionMailServer);
                return 0;
            }

            fputs($connectionMailServer, $this->_smtpUsername."\r\n");
            $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

            if($replyServer != 334) {
                error_log("Pri avtorizacii poluchen otkaz iz-za nesuschestvuyuschego ili zablokirovannogo pol'zovatelya " . SMTP_LOGIN);
                fclose($connectionMailServer);
                return 0;
            }

            fputs($connectionMailServer, $this->_smtpPassword."\r\n");
            $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

            if($replyServer != 235) {
                error_log("Pri avtorizacii poluchen otkaz iz-za nevernogo parolya k pochtovomu yaschiku " . SMTP_LOGIN);
                fclose($connectionMailServer);
                return 0;
            }
        }

        fputs($connectionMailServer, "MAIL FROM:" . $this->_from . "\r\n");
        $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

        if($replyServer != 250) {
            error_log("Otkaz v kommande MAIL FROM");
            fclose($connectionMailServer);
            return 0;
        }

        fputs($connectionMailServer, "RCPT TO:" . $this->_to . "\r\n");
        $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

        if($replyServer != 250 AND $replyServer != 251) {
            error_log("Otkaz v kommande RCPT TO");
            fclose($connectionMailServer);
            return 0;
        }

        fputs($connectionMailServer, "DATA\r\n");
        $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

        if($replyServer != 354) {
            error_log("Otkaz v kommande DATA");
            fclose($connectionMailServer);
            return 0;
        }

        fputs($connectionMailServer, $header . "\r\n" . $this->_body . "\r\n.\r\n");
        $replyServer = substr($this->getReplyServer($connectionMailServer), 0, 3);

        if($replyServer != 250) {
            error_log("Oshibka otpravki pis'ma OTVET: ".$replyServer  );
            fclose($connectionMailServer);
            return 0;
        }

        fputs($connectionMailServer,"QUIT\r\n");
        //$replyServer = $this->getReplyServer($connectionMailServer);
        $this->getReplyServer($connectionMailServer);
        return 1;
    }
}
