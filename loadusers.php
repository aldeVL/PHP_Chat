<?php
session_start(); //Запускаем сессию
include ("bd.php");//Подключение к бд
$us = loadus($db);
function loadus($db) {
		$us = "";
		$userss = mysqli_query($db,"SELECT * FROM users"); //Получаем данные о всех пользователях
		if($userss) {
			if(mysqli_num_rows($userss) >= 1) {
				while($array3 = mysqli_fetch_array($userss)) {
					//Если текущий пользователь админ - выводим список пользователей с кнопкой заблокировать/разблокировать
					if ("$_SESSION[id]" == 6){
						//Если статус пользователя - 1(заблокирован) - выводим список с кнопкой разблокировать и с статусом
						if ("$array3[status]"==1){		
							$us .= "<div class='listel'>$array3[login]<span class='sta'> [Заблокирован]</span><div class='mind'><input style='display: none;' class='blockbtn' type=\"submit\" value=\"Заблокировать\" onclick=\"location.href='/blocking.php?bl=$array3[id]';\" /></div>
							<div class='mind'><input style='display: block;' class='unblockbtn' type=\"submit\" value=\"Разблокировать\" onclick=\"location.href='/unblocking.php?bl=$array3[id]';\" /></div>
							</div><div><hr size='2px'></div>";
							}
						//Иначе Если статус пользователя - 0(Обычный) - выводим список пользователей с кнопкой заблокировать
						else{
							$us .= "<div class='listel'>$array3[login]<div class='mind'><input class='blockbtn' type=\"submit\" value=\"Заблокировать\" onclick=\"location.href='/blocking.php?bl=$array3[id]';\" /></div>
							<div class='mind'><input class='unblockbtn' type=\"submit\" value=\"Разблокировать\" onclick=\"location.href='/unblocking.php?bl=$array3[id]';\" /></div>
							</div><div><hr size='2px'></div>";
							}
						}
						//Если текущий пользователь обычный и статус юзера в базе 1(заблокирован) - выводим список пользователей с статусом
						else if ("$array3[status]"==1){
							$us .= "<div class='listel'>$array3[login]<span class='sta'> [Заблокирован]</span>
							</div><div><hr size='2px'></div>";
						}
						//Если текущий пользователь обычный и статус юзера в базе 0(Обычный) - выводим список пользователей
						else {
							$us .= "<div class='listel'>$array3[login]
							</div><div><hr size='2px'></div>";
						}
				}
			}
		}
	return $us;
}
echo $us;
?>