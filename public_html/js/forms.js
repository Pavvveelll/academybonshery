// JavaScript Document
	function toggleElement(id) {
		//document.write(id);
		el = document.getElementById(id);
		if (el.style.display == 'none' || el.style.display == '') {
			el.style.display = 'block';
		} else {
			el.style.display = 'none';
		}
		//id=id;
		//return false;
	}
	function toggleLogin() {
		toggleElement('user_welcome');
		toggleElement('user_login')
	}

	
	// JavaScript Document
function getObject(obj) {
	var theObj;
	if(document.all) {
		if(typeof obj=="string") {
			 return document.all(obj);
		} else {
			 return obj.style;
		}
	}
	if(document.getElementById) {
		if(typeof obj=="string") {
			return document.getElementById(obj);
		} else {
			return obj.style;
		}
	}
		return null;
}
function Contar(entrada,salida,caracteres) {
	//checkForm(document.getElementById(entrada).form);
	var entradaObj=getObject(entrada);
	var salidaObj=getObject(salida);
	var longitud=caracteres - entradaObj.value.length;
	if(longitud <= 0) {
		longitud=0;
		entradaObj.value=entradaObj.value.substr(0,caracteres);
	}
	salidaObj.innerHTML = longitud;
}


function checkForm(form) {
	res = true;
	if (form.itema.value=="") {
		form.itema.style.backgroundColor = "#FF9999";
		res = false;
	} else {
		form.itema.style.backgroundColor = "#FFFFFF";
	}

	if (checkURL(form.url.value)) {
		form.url.style.backgroundColor = "#FFFFFF";
	} else {
		form.url.style.backgroundColor = "#FF9999";
		res = false;
	}
	if (form.name.value=="") {
		form.name.style.backgroundColor = "#FF9999";
		res = false;
	} else {
		form.name.style.backgroundColor = "#FFFFFF";
	}
	if (form.city.value=="") {
		form.city.style.backgroundColor = "#FF9999";
		res = false;
	} else {
		if(form.city.value.charAt(0)!=form.city.value.charAt(0).toUpperCase()){
				form.city.style.backgroundColor = "#FF9999";
				res = false;
		}else{
		form.city.style.backgroundColor = "#FFFFFF";
		}
	}	
	
	if (form.capcha.value=="") {
		form.capcha.style.backgroundColor = "#FF9999";
		res = false;
	} else {
		form.capcha.style.backgroundColor = "#FFFFFF";
	}	
	if (form.des.value=="") {
		form.des.style.backgroundColor = "#FF9999";
		res = false;
	} else {
		//проверка URL и email
		if (form.des.value.match(/[A-Za-z0-9._%-]+\.[A-Za-z]{2,4}/)) {
			form.des.style.backgroundColor = "#FF9999";
			res = false;	
		}else{
			form.des.style.backgroundColor = "#FFFFFF";
		}
	}
	
//	if (checkName(form.surname)) {
//		form.surname.style.backgroundColor = "#fff";
//	} else {
//		form.surname.style.backgroundColor = "#f75e00";
//		res = false;
//	}
	if (checkEmail(form.mail.value)) {
		form.mail.style.backgroundColor = "#FFFFFF";
	} else {
		form.mail.style.backgroundColor = "#FF9999";
		res = false;
	}
//	if (checkEmpty(form.password)) {
//		form.password.style.backgroundColor = "#fff";
//	} else {
//		form.password.style.backgroundColor = "#f75e00";
//		res = false;
//	}
//	if (checkEmpty(form.passwordr) && form.passwordr.value==form.password.value) {
//		form.passwordr.style.backgroundColor = "#fff";
//	} else {
//		form.passwordr.style.backgroundColor = "#f75e00";
//		res = false;
//	}
//	
	el = document.getElementById('submit_item');
//	el_d = document.getElementById(button_d);
	if (res) {
		el.disabled = 0;
//		el.style.display="";
//		el_d.style.display="none";
		return true;
	} else {
		el.disabled = 1;
//		el.style.display="none";
//		el_d.style.display="";
		return false;
	}
}
function checkEmail(value) {
	//value = el.value;
	reg = /^(?=[^\.])[a-zA-Z0-9._]*[a-zA-Z0-9_]@(?=[^\.])[_a-zA-Z0-9]*[_a-zA-Z0-9-.]*[_a-zA-Z0-9]\.[a-zA-Z0-9]{2,4}$/;
	isValid = reg.test(value);
	if (isValid) {
		return true;
	} else {
		return false;
	}
}
function checkURL(value) {
	//value = el.value;
	reg = /^http:\/\/[-a-zA-Z0-9.]+\.[a-z]{2,4}\/$/;
	isValid = reg.test(value);
	if (isValid) {
		return true;
	} else {
		return false;
	}
}

	function confirmDeleteAction(that, actionfield, mess){
		if(window.confirm(mess)){
			el = document.getElementById(actionfield);
			form = el.form;
			//form = el.parentNode.parentNode; //TODO foreach
			//form = document.forms["form_item"];
			el.value = 'delete_'+that;
			form.submit();
			return false;
		}
		//return false;
	}
	
	function doubleItem(actionfield) {
		el = document.getElementById(actionfield);
		el.value = 'add';
		cod = document.getElementById('code');
		cod.value = 'введите уникальный артикул';
		id = document.getElementById('id');
		id.value = '0';
		dt=document.getElementById('f_date_d');
		var ndt=new Date();//2009-11-17 11:36:34
		var adt=ndt.getFullYear() + '-' + (ndt.getMonth()+1) + '-' + ndt.getDate()+ ' ' + ndt.getHours()+ ':' + ndt.getMinutes()+ ':' + ndt.getSeconds();
		dt.value=adt;//устанавливаем дату дублируемого товара в сегодняшнюю
		//form = el.parentNode.parentNode; //TODO foreach
		form = document.forms["form_item"];
		 form.submit();
		return false;
	}
	////////////////////////
	///ЛИСТАТЬ/////////////

	function listalka(value) {
		if(value=='richt'){
			if(alllist-obrez>6){
				// 
				el = document.getElementById('fm'+obrez);
				el.style.display = 'none';
				el = document.getElementById('fm'+(obrez+6));
				el.style.display = 'inline';
				obrez++;
			}else{
				//alert(obrez);
			}

		}else{
			if(obrez>0){
				obrez--;
				el = document.getElementById('fm'+(obrez+6));
				el.style.display = 'none';
				el = document.getElementById('fm'+obrez);
				el.style.display = 'inline';
				
				
			}else{
				//alert('left');
			}

		}
		if(obrez==0){
			el = document.getElementById('listleft');
			el.style.display = 'none';
		}else{
			el = document.getElementById('listleft');
			el.style.display = 'block';
		}
		if(obrez+6>=alllist){
			el = document.getElementById('listricht');
			el.style.display = 'none';
		}else{
			el = document.getElementById('listricht');
			el.style.display = 'block';
		}
	}
	
		//Меняем статус в чекбоксах
	//устанавливаем все чекбоксы в ДА
	function checkall( p_formname, p_state ) {
		var t_elements = (eval("document." + p_formname + ".elements"));
		
		 for (var i = 0; i < t_elements.length; i++) {
			 if(t_elements[i].type == "checkbox") {
			 if( t_elements[i].checked == p_state )
			 t_elements[i].checked = !p_state;
			 else
			 t_elements[i].checked = p_state;
			 }
		 }
	}