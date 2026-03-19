// JavaScript Document
/*
// Функция для добавления обработчиков событий
function addHandler(object, event, handler, useCapture) {
      if (object.addEventListener) {
            object.addEventListener(event, handler, useCapture ? useCapture : false);
      } else if (object.attachEvent) {
            object.attachEvent('on' + event, handler);
      } else alert("Add handler is not supported");
}

// Определяем браузеры
var ua = navigator.userAgent.toLowerCase();
var isIE = (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1);
var isGecko = (ua.indexOf("gecko") != -1);

// Добавляем обработчики
if (isIE) addHandler (document, "keydown", hotSave);
else addHandler (document, "keypress", hotSave);

function hotSave(evt) {
      // Получаем объект event
      evt = evt || window.event;
      var key = evt.keyCode || evt.which;
      // Определяем нажатие Ctrl+S
      key = !isGecko ? (key == 83 ? 1 : 0) : (key == 115 ? 1 : 0);
      if (evt.ctrlKey && key) {
            // Блокируем появление диалога о сохранении
            if(evt.preventDefault) evt.preventDefault();
evt.returnValue = false;
            // Запускаем любую функцию, по желанию
            clientFunction();
            // Возвращаем фокус в окно
            window.focus();
            return false;
      }
}
function clientFunction() {
      // И тут что-то происходит...
	  alert('yy');
}*/



var ajax = null;

function sobr(id, oper, j){
	if(ajax==null){
		ajax = new sack();
	}
	var addj='';
	if (typeof(j) != 'undefined') {
		addj='&j='+j;
	}
	//alert(addj);
	ajax.requestFile = "/admin/index.php?sobr="+oper+"&id="+id+addj;
	ajax.onCompletion = sobr_Completed;
	ajax.runAJAX();
}

function sobr_Completed(){ 
	var rez_arr=parse_str(ajax.response);
 	var sobrOb = getObject('sobrblock'+rez_arr['id']);
	if (typeof(rez_arr['j']) == 'undefined') {
		if(rez_arr['res']=="yes"){//собран 	
			sobrOb.className='sobryes';
			sobrOb.onclick=function(){
					sobr( rez_arr['id'],'no');
				};
			sobrOb.innerHTML='Собран';	
		}if(rez_arr['res']=="no"){//не собран 	
			sobrOb.className='sobr';
			sobrOb.onclick=function(){
					sobr( rez_arr['id'],'yes');
				};
			sobrOb.innerHTML='Собран';
		}		 
	}else{
		if(rez_arr['res']=="yes"){//собран 	
			sobrOb.className='sobryes';
			sobrOb.onclick=function(){
					sobr( rez_arr['id'],'no',rez_arr['j']);
				};
			sobrOb.innerHTML='Собран';	
		}if(rez_arr['res']=="no"){//не собран 	
			sobrOb.className='sobr';
			sobrOb.onclick=function(){
					sobr( rez_arr['id'],'yes',rez_arr['j']);
				};
			sobrOb.innerHTML='Собран';
		}		
	}
	// alert(ajax.response);
}

function problem(id, j){
	 if(window.confirm("Пометить проблемным?")){
		if(ajax==null){
			ajax = new sack();
		}
		var addj='';
		if (typeof(j) != 'undefined') {
			addj='&j='+j;
		}
		
		ajax.requestFile = "/admin/index.php?problem=yes&id="+id+addj;
		ajax.onCompletion = problem_Completed;
		ajax.runAJAX();
		return false;
	 }
}

function problem_Completed(){ 
	var rez_arr=parse_str(ajax.response);
 	var problem_Ob = getObject('problem'+rez_arr['id']);
	    problem_Ob.className='problem yes';
		problem_Ob.onclick=function(){}
	// alert(ajax.response);
}

function parse_str(str, array){	// Parses the string into variables
	var glue1 = '=';
	var glue2 = '&';

	var array2 = str.split(glue2);
	var array3 = [];
	for(var x=0; x<array2.length; x++){
		var tmp = array2[x].split(glue1);
		array3[unescape(tmp[0])] = unescape(tmp[1]).replace(/[+]/g, ' ');
	}

	if(array){
		array = array3;
	} else{
		return array3;
	}
}
///////////////////////////////////////////////////////////////////



function union(a,b) {
		if(window.confirm("Действительно объединить?")){
			window.location.href = "?id="+a+"&union="+b;
			return false;
		}
}
	
function rezerv(field, kol){
	//резервируем товар, при отрицательных значениях - снимаем с резерва.
//	var form_item=getObject("form_item");
	var field_item=getObject(field);
	var cur_rezerv=field_item.value - 0;
	field_item.value=kol;
	var viev_item=getObject('v'+field);
	viev_item.innerHTML=kol;
	
	var viev_item=getObject('f'+field);
	if(viev_item != null){
		viev_item.innerHTML=cur_rezerv;
	}
	
	var s_item=getObject('s'+field);
	var sn_item=getObject('sn'+field);
	if(kol>0){
		sn_item.style.display='inline';
		s_item.style.display='none';
	}else{
		sn_item.style.display='none';
		s_item.style.display='inline';
	}
	
	var sk_item=getObject('sk'+field);
	if(sk_item != null){
		var sk_item_val=sk_item.innerHTML - 0;
		if(kol>0){
			sk_item.innerHTML=0;		
		}else{
			sk_item.innerHTML=sk_item_val+cur_rezerv;	
		}
	}
	
	
//	form_item.submit();
	return false;
	 
}

function checkall( p_formname, pref ) {
	//устанавливаем или снимаем галки всем элементам в списке (какой именно статус зависит от первого элемента, есле он отмечен, то все снимаем
	var t_elements = (eval("document." + p_formname + ".elements"));
	var n_st=null;
	for (var i = 0; i < t_elements.length; i++) {
		if(t_elements[i].type == "checkbox") {
			if((typeof(pref) == 'undefined')||(t_elements[i].name.indexOf(pref) != -1)) {
				if(n_st==null){
					n_st=!t_elements[i].checked;
				}		
				t_elements[i].checked = n_st;
				//alert( t_elements[i].name);
			}
		//
		
		
		}

//		 if(t_elements[i].type == "checkbox") {
//			 if( t_elements[i].checked == p_state )
//			 	t_elements[i].checked = !p_state;
//		 	else
//			t_elements[i].checked = p_state;
//		 }
	 }
}



function moveit(kyda,chto){
	var shiftOb=getObject("s"+chto);
	shiftval=shiftOb.value;
	if(kyda!='up'){
		shiftval=-shiftval;
	}
	var moveshiftOb=getObject("moveshift");
	moveshiftOb.value=shiftval;
	var moveidOb=getObject("moveid");
	moveidOb.value=chto;//какой итем двигаем
	form = document.forms['moveform'];
	form.submit();
	return false;
	// alert();
}

function zoom(ts,ns,zi){
	if (typeof(zi) == 'undefined') {
		zi=ns;
	}
	ts.width=ns;
	ts.height=ns;
	ts.style.zIndex=zi;
}

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
function screenSize(){
	var w, h;
	 w = (window.innerWidth ? window.innerWidth : (document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.offsetWidth));
	h = (window.innerHeight ? window.innerHeight : (document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.offsetHeight));
	return {w:w, h:h};
} 
var curPopImage=null;
var PopStatus;
var SelOld=null;
function ShowPopImage(arg, dop){
	// alert(arg);
	var popdiv=getObject("imgpop");
	var popnull=getObject("popnull");
	var grayover=getObject("grayoverlay");

	if(arg=='close'){
		popdiv.style.display = 'none';
		grayover.style.display = 'none';
		PopStatus= 'close';
		nImage=null;
	}else{
		var sel=getObject(arg);//выделяем строчку
		if(sel!=null){	
			hlTR(sel, 'click');
		}
		var nImage=new Image();
		nImage.onload = function(){
			 //alert(nImage.width);
			//alert(popdiv.style.width);
				//newWidth=(nImage.width+10);
				newWidth=610;
				popdiv.style.width=newWidth+'px';
				/*toppop.style.width=nImage.width+'px';*/
				var left = Math.ceil(screenSize().w/2)-Math.ceil(newWidth/2);
				popdiv.style.left = left+'px';
				var top = document.body.scrollTop||document.documentElement.scrollTop;
				//top = top+Math.ceil(screenSize().h/2-(nImage.height/2+40));
				top = top+Math.ceil(screenSize().h/2-(600/2+20));
				popdiv.style.top = (top>0?top:0)+'px';
				grayover.style.display = '';
				popdiv.style.display = '';
				//alert(popdiv.style.width);
				//if (typeof(document.getElementsByTagName) != 'undefined') {
//					theCells = sel.getElementsByTagName('td');
//				}
//				else if (typeof(theRow.cells) != 'undefined') {
//					theCells = sel.cells;
//				}
//				var rowCellsCnt  = theCells.length;
//				var newColor              = "#FC0";
//				var c = null;
//				var domDetect    = null;
//				if (typeof(window.opera) == 'undefined' && typeof(theCells[0].getAttribute) != 'undefined') {
//					domDetect    = true;
//				}else { // 3.2 ... with other browsers
//					domDetect    = false;
//				} // end 3
//				// 5.1 ... with DOM compatible browsers except Opera
//				if (domDetect) {
//					for (c = 0; c < rowCellsCnt; c++) {
//						theCells[c].setAttribute('bgcolor', newColor, 0);
//						if(SelOld!=null&&theCells!=SelOld){
//							SelOld[c].setAttribute('bgcolor', "#FFF", 0);
//						}
//					} // end for
//				}else {// 5.2 ... with other browsers
//					for (c = 0; c < rowCellsCnt; c++) {
//						theCells[c].style.backgroundColor = newColor;
//						if(SelOld!=null&&theCells!=SelOld){
//							SelOld[c].style.backgroundColor =  "#FFF";
//						}
//					}
//				}
//				SelOld=theCells;
				popdiv.style.display = '';
				
		}
		nImage.src='/picture/'+arg;
		//popnull.style.backgroundImage="url('/img/wait.gif')";
		grayover.style.display = '';
		popdiv.style.display = '';
		if(dop==true){
			popnull.innerHTML="<img  title='закрыть'  style='cursor: pointer;'  src='/picture/"+arg+"'>" ;
		}else{
			if(curPopImage==null){
				popnull.innerHTML="<img  title='закрыть'  style='cursor: pointer;'  src='/picture/"+arg+"'>" ;
			}else{
				popnull.innerHTML="<img  title='закрыть'  style='cursor: pointer;'  src='/picture/"+curPopImage+"'>" ;
			}
		}
	}
}

//function confirmDeleteAction(form_name, that, actionfield, mess){
//	if(window.confirm(mess)){
//		form = document.forms[form_name];
//		el = form[actionfield];
//		el.value = 'delete_'+that;
//		form.submit();
//		return false;
//	}
//	//return false;
//}

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

//function toggleElement(id) {
//	//document.write(id);
//	el = document.getElementById(id);
//	if (el.style.display == 'none' || el.style.display == '') {
//		el.style.display = 'block';
//	} else {
//		el.style.display = 'none';
//	}
//	//id=id;
//	//return false;
//}

function toggleElement(id, styledisplay) {//
	styledisplay= typeof(styledisplay) != 'undefined' ? styledisplay : 'block';

	//document.write(id);
	el = document.getElementById(id);
	if (el.style.display == 'none' || el.style.display == '') {
		el.style.display = styledisplay;
	} else {
		el.style.display = 'none';
	}
	//id=id;
	//return false;
}

function hlTR(theRow, theAction)
{
    var theCells = null;
	var theDefaultColor='#ffffff';
	var thePointerColor='#D5D5D5';
	var theMarkColor='#FFEDD2';
	var newColor     = null;
	
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }else {
        return false;
    }

    var rowCellsCnt  = theCells.length;

	if(SelOld==null || theRow != SelOld ){//
		if (theAction == 'out') {
			newColor              = theDefaultColor;
		}
		if (theAction == 'over') {
			newColor              = thePointerColor;
			
		}
	}
	if (theAction == 'outclick') {
		newColor              = theDefaultColor;
	}
	if (theAction == 'click') {
		//alert(SelOld);
		newColor              = theMarkColor;
		if(SelOld!=null&&theRow!=SelOld){
			hlTR(SelOld, 'outclick');
		}
		SelOld=theRow;
	}

	if (newColor) {	
		for (c = 0; c < rowCellsCnt; c++) {
			theCells[c].setAttribute('bgcolor', newColor, 0);
		}
	}
    return true;
} // end of the 'hlTR()' function


/**
 * Sets/unsets the pointer and marker in browse mode
 *
 * @param   object    the table row
 * @param   integer  the row number
 * @param   string    the action calling this script (over, out or click)
 * @param   string    the default background color
 * @param   string    the color to use for mouseover
 * @param   string    the color to use for marking a row
 *
 * @return  boolean  whether pointer is set or not
 */
 var marked_row = new Array;
function setPointer(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    } // end 3

    // 3.3 ... Opera changes colors set via HTML to rgb(r,g,b) format so fix it
    if (currentColor.indexOf("rgb") >= 0)
    {
        var rgbStr = currentColor.slice(currentColor.indexOf('(') + 1,
                                     currentColor.indexOf(')'));
        var rgbValues = rgbStr.split(",");
        currentColor = "#";
        var hexChars = "0123456789ABCDEF";
        for (var i = 0; i < 3; i++)
        {
            var v = rgbValues[i].valueOf();
            currentColor += hexChars.charAt(v/16) + hexChars.charAt(v%16);
        }
    }

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
            // Garvin: deactivated onclick marking of the checkbox because it's also executed
            // when an action (like edit/delete) on a single item is performed. Then the checkbox
            // would get deactived, even though we need it activated. Maybe there is a way
            // to detect if the row was clicked, and not an item therein...
            // document.getElementById('id_rows_to_delete' + theRowNum).checked = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
             && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor              = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
            // document.getElementById('id_rows_to_delete' + theRowNum).checked = true;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '')
                                  ? thePointerColor
                                  : theDefaultColor;
            marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                                  ? true
                                  : null;
            // document.getElementById('id_rows_to_delete' + theRowNum).checked = false;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } // end 5

    return true;
} // end of the 'setPointer()' function

function printo(page){
	//var DocumentContainer = document.getElementById(‘divtoprint’);
	var WindowObject = window.open(page, 'PrintWindow','width=200,height=100,top=100,left=100,toolbars=yes,scrollbars=no,status=no,resizable=no');
	//WindowObject.document.writeln(DocumentContainer.innerHTML);
	//WindowObject.document.close();
	WindowObject.focus();
	 //WindowObject.print();
	 //WindowObject.close();
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

function doubleItem(actionfield) {
	el = document.getElementById(actionfield);
	el.value = 'add';
	cod = document.getElementById('code');
	cod.value = '';
	cod = document.getElementById('artpost');
	cod.value = '';	
	nik = document.getElementById('nik');
	nik.value = '';
	id = document.getElementById('id');
	id.value = '0';
//	dt=document.getElementById('f_date_d');
//	var ndt=new Date();//2009-11-17 11:36:34
//	var adt=ndt.getFullYear() + '-' + (ndt.getMonth()+1) + '-' + ndt.getDate()+ ' ' + ndt.getHours()+ ':' + ndt.getMinutes()+ ':' + ndt.getSeconds();
//	dt.value=adt;//устанавливаем дату дублируемого товара в сегодняшнюю
	//form = el.parentNode.parentNode; //TODO foreach
	form = document.forms["form_item"];
	form.action='/admin/tovar.php?id=0';
	 form.submit();
	return false;
}