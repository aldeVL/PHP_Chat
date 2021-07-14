<?php
session_start(); //Запускаем сессию
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>VLChat</title>
<style>
.chat {
	border:1px solid #333;
	border-right: 0;
	margin:15px;
	width:40%;
	height:74%;
	background:#555;
	color:black;
}

.chat-messages {
	min-height:80%;
	max-height:80%;
	overflow:auto;
}

.chat-messages__content {
	padding:1px;
	overflow: hidden;
}

.chat__message {
	border:2px solid #333;
	margin-top:2px;
	padding:2px;
}

.chat__message_black {
	border-color:#000;
}

.chat-input {
	margin-top: 2px;
	min-height:6%;
}
input {
	font-family:arial;
	font-size:16px;
	vertical-align:middle;
	background:#333;
	color:#fff;
	border:0;
	display:inline-block;
	margin:1px;
	height:30px;
}

.chat-form__input {
	width:79%;
}

.chat-form__submit {
	width:19.5%;
	height: 13%;
}
.data{
	margin-top: -3px;
	margin-left: 3px;
	color: gray;
}
span{
	color:#fff;
}
span.private{
	color:#E1DFE9;
}
div.private{
	color: #39BC39;
}
div.mind{
	margin-top: -7px;
	float: right;
}
.delbtn{
	font-size: 9pt;
	height: 15px;
	margin-top: 9px;
}
.sendbtn, .vvodi{
	display: inline;
}
.sendb{
	margin-top: -5.7%;
}
.uselist{
	position: absolute;
	left: 40.2%;
	top: 14px;
	margin-top: 1px;
	width: 20%;
	border-left: 0;
	min-height:70.7%;
	max-height:70.7%;
	overflow:auto;
	padding: 5px;
}
.listt{
	padding: 5px;
	font-size: 14pt;
}
label{
	margin-left: 40px;
	margin-bottom: 20px;
}
.listt_content{
	margin-top: 20px;
}
.listel{
	margin-bottom: 10px;
	margin-right: 0;
	padding: 0;
	
}
.blockbtn{
	font-size: 9pt;
	height: 15px;
	margin-top: 13px;
}
.unblockbtn{
	display: none;
	font-size: 9pt;
	height: 15px;
	margin-top: 13px;
}
.sta{
	font-size: 9pt;
	color: #ff4040;
}
.smil{
	padding: 2px;
}
</style>
<script type="text/javascript" src="jquery-3.5.0.min.js"></script>
</head>
	<body>
	<div class='chat'>
	<div class='chat-messages' id="chatt">
		<div class='chat-messages__content' id='messages'>
			Загрузка...
		</div>
	</div>
	<div class='chat-input'>
		<form method='post' id='chat-form'>
			<div class="vvodi">
			<input type='text' id='reciever' class='chat-form__input' placeholder='Кому'>
			<input type='text' id='message-text' class='chat-form__input' maxlength="50" placeholder='Введите сообщение'> 
			</div>
			<div class="sendbtn">
			<input type='submit' class='chat-form__submit sendb' value='Отправить'><br />
			</div>
			<div class="smil">
				<span>
					<img class="kartinka" width="20" height = "20" src="images/1.png" alt=":laugh:"/>
					<img class="kartinka" width="20" height = "20" src="images/2.png" alt=":smile:"/>
					<img class="kartinka" width="20" height = "20" src="images/3.png" alt=":podmig:"/>
					<img class="kartinka" width="20" height = "20" src="images/4.png" alt=":poker:"/>				
				</span>
			</div>
		</form>
	</div>
	</div>
	<div class="chat uselist">
	<div class="listt">
	<label>Список пользователей</label>
		<div class="listt_content" id = "useri">
			Загрузка...
		</div>
	</div>
	</div>
<label><?php echo "Вы вошли на сайт, как <b>".$_SESSION['login']."</b>";?></label>
<script>
var messages__container = document.getElementById('messages'); //Контейнер сообщений — скрипт будет добавлять в него сообщения
var useri__container = document.getElementById('useri'); //Контейнер списка пользователей — скрипт будет добавлять в него список юзеров
var sendForm = document.getElementById('chat-form'); //Форма отправки
var messageInput = document.getElementById('message-text'); //Поле для текста сообщения
var recieverInput = document.getElementById('reciever'); //Поле для получателя сообщения
var var1 = null; //Переменная для записи сообщения из инпута
var var2 = null; //Переменная для записи получателя из инпута

function send_request(act) {//Основная функция
	if (act == 'noload'){ //Вызов документа loadusers.php
		$.post('loadusers.php',{
		}).done(function (data) { //Заносим в контейнер списка пользователей ответ от сервера
			useri__container.innerHTML = data;
		});
	}
	else{
		$.post('chat.php',{ //Вызов документа chat.php
		act: act, //передаем в chat.php переменную act
		}).done(function (data) { //Заносим в контейнер сообщений ответ от сервера
			messages__container.innerHTML = data;
		});
	}
}

window.onload = function(){ //Функция загрузки сообщений и списка пользователя при загрузке страницы
   send_request('load');
   send_request('noload');
};

function scrolling(){ //Функция скроллинга окна чата с задержкой
	var block = document.getElementById("chatt");
    block.scrollTop = 99999;
	console.log("выполнено")
}
setTimeout(scrolling, 2500);

sendForm.onsubmit = function (){ //Функция отправки сообщения
	event.preventDefault()
	var1 = messageInput.value; //записываем в переменную сообщение
	var2 = recieverInput.value; //записываем в переменную получателя
	act = 'send'
	if (var1 == ''){ //проверка на пустое сообщение
		return;
	}
	$.post('chat.php',{ //Отправляем переменные
		act: act,
		var1: var1,
		var2: var2
	}).done(function (data) {//Заносим в контейнер сообщений от сервера
		messages__container.innerHTML = data;
		messageInput.value = ''; //Очищаем поле ввода
	});
	return false; //Возвращаем ложь, чтобы остановить классическую отправку формы
};
 
 $(document).ready(function(){
 $(".kartinka").click(function(){
 var smile = $(this).attr('alt');
 var text = $("#message-text").val();
 $("#message-text").val(text + smile);
 });
 });
</script>
</body>
</html>