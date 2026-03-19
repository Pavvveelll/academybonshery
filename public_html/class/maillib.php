<?php
//класс по работе с почтой
if(!defined('COOKIES')){
	error_log('hakher he-he');
	header("Location: http://".$_SERVER['HTTP_HOST'] ."/");
	exit;
}

class maillib{
	//внутренние переменные
	var $charset="utf-8";
	var $encoding="base64";//quoted-printable
	//var $encoding="7bit";
	var $contentType="text/plain";//text/html
	var $mailto=false;
	var $mailfrom=false;
	var $mailreply=false;
	var $mailfromname=false;
	var $mailsubj="";
	var $mailbody="";
	var $mailheaders=false;
	var $return_path=ADMIN_MAIL;
	
	
	//var $mailsent=false;//отправлено или нет
	var $errorlog="";
	var $checkaddr=true;
	
	function maillib(){
		//конструктор
		$this->errorlog="";
	}
	
	function  from($from,$name=false){ //от кого
		if (is_string($from)){
			trim($from);
			if ($this->check_mail($from)){
				$this->mailfrom=$from;
				$this->mailreply=$from;
			}else{
				$this->errorlog.="Неправильный формат адреса электронной почты.\n";
			}
		}else{
			$this->errorlog.="Адрес отправителя должен быть строкой.\n";
		}
		if ($name && is_string($name)){
			trim($name);
			//if ($name!=" ") $this->mailfromname="\"".addslashes($name)."\"";
			if ($name!=" ") $this->mailfromname= '=?'.$this->charset.'?B?'.base64_encode($name).'?=';
			
		}
	}
	function to($to){
		if (is_string($to)){
			trim($to);
			if ($this->check_mail($to)){
				$this->mailto=$to;
			}else{
				$this->errorlog.="Неправильный формат адреса электронной почты.\n";
			}
		}
	}
	function return_path($var){
		//если не определено всё возвращается администратору.
		//$this->return_path
		if (is_string($var)){
			trim($var);
			if ($this->check_mail($var)){
				$this->return_path=$var;
			}else{
				$this->errorlog.="Неправильный формат адреса электронной почты.\n";
			}
		}
	}
	
	function subject($var=""){///Тема
		if (is_string($var)) {
			// $this->mailsubj='=?'.$this->charset.'?B?'.base64_encode(convert_cyr_string(strtr($var,"\r\n","  "), "w","k")).'?=';
			 $this->mailsubj='=?'.$this->charset.'?B?'.base64_encode(strtr($var,"\r\n"," ")).'?=';
			//$this->mailsubj=strtr($var,"\r\n","  ");
		}
	}
	function message($var=""){
		if (is_string($var)){
			 $this->mailbody=base64_encode($var);
		}
	}
	function is_sent(){
		if (@$this->mailsent) {
			return $this->mailsent;
			//return false;
		}else{
			 return false;
		}
	}
	function get_error(){
		return $this->errorlog;
	}
	function check_mail($var){
		//print($var);
		//$temp="^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}\.){0,}[a-zA-Z0-9-]{2,}(\.[a-zA-Z]{2,4}){1,2}$";
		$temp="/^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}\.){0,}[a-zA-Z0-9-]{2,}(\.[a-zA-Z]{2,4}){1,2}$/";
		if ($this->checkaddr){
			//if (ereg($temp,$var)) return true;
			if (preg_match($temp,$var)) return true;
			else{
				$this->mailsent=false;
				return false;
				}
		 }
		 else return true;
	}
	function makeheaders(){
		//формируем заголовки
		$this->headers["Mime-Version"]="1.0";
		///$this->headers["Content-Type"]="text/plain; charset=\"".$this->charset."\"";
		$this->headers["Content-Type"]=$this->contentType."; charset=\"".$this->charset."\"";
		$this->headers["Content-Transfer-Encoding"]="$this->encoding";
		///new
		//$this->headers["Sender"]=ADMIN_MAIL;
//		$this->headers["Errors"]=ADMIN_MAIL;
//		$this->headers["Return-path"]=ADMIN_MAIL;	
		$this->headers["Content-Base"]=SERVER_HOST;
		
		///
		if ($this->mailfrom){
			if ($this->mailfromname){
				$this->headers["From"] = $this->mailfromname." <".$this->mailfrom.">";
				$this->headers["Reply-To"]=$this->mailfromname." <".$this->mailfrom.">";
			}else{
				$this->headers["From"] = $this->mailfrom;
				$this->headers["Reply-To"]=$this->mailfrom;
			}
			
			$this->headers["Errors"]=$this->return_path;
			$this->headers["Return-path"]=$this->return_path;
		}
//		if ($this->mailreply){
//			$this->headers["Reply-To"]=$this->mailreply;
//		}
		
		$this->headers["X-Mailer"] = "maillibPHPsender";
		$this->mailheaders="";
		reset($this->headers);
		foreach ($this->headers as $key => $value){
			if ($value) $this->mailheaders.="$key: $value\n";
		}
	}
	function send(){
	//print "send";
		$this->makeheaders();//формируем заголовки
		if ($this->mailto && $this->mailfrom){
			//отправляем
			 $this->mailsent=mail($this->mailto, $this->mailsubj, $this->mailbody, $this->mailheaders);
/*			 if ($this->mailsent==false){
		
				print "false";
			}else{
			 print "true";
			 }*/
		}else{
			$this->errorlog.="Class:maillib Отсутствует адрес отправителя или получателя";
		}
		
	}
}
?>