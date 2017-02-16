<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $banned */
?>
<style>
	body {
	font-family: 'Open Sans', Arial, sans-serif;
	}
	h1 {
	text-align: center;
	}
    table {
	border-spacing: 0 10px;
	width: 80%;
	margin: 0 auto;
	}
	table th, table thead {
	font-weight: bold;
	}
	table th {
	padding: 10px 20px;
	background: #56433D;
	color: #F9C941;
	border-right: 2px solid; 
	font-size: 0.9em;
	}
	table th:first-child {
	text-align: left;
	}
	table th:last-child {
	border-right: none;
	}
	table td {
	vertical-align: middle;
	padding: 10px;
	font-size: 14px;
	text-align: center;
	border-top: 2px solid #56433D;
	border-bottom: 2px solid #56433D;
	border-right: 2px solid #56433D;
	background:white;
	}
	table td:first-child {
	border-left: 2px solid #56433D;
	}
	table td:nth-child(2){
	text-align: left;
	}
	table td.caption {
	border: none;
	margin: 20px 0;
	}
	table tr:hover td, .menu td {
	border-color: #C61359;
	}
	.menu td {
	background:#ededed;
	}
</style>
<h1>Забаненные пользователи</h1>
<table>
    <tbody>
    <tr class="no-border menu">
        <td><a href="/?action=admin&sub=users">Пользователи</a></td>
        <td><a href="/?action=admin&sub=stat">Статистика</a></td>
        <td><a href="/?action=admin&sub=feedback">Обращения</a></td>
        <td><a href="/?action=admin&sub=flights">Направления</a></td>
        <td><a href="/?action=admin&sub=banned">Забаненные</a></td>
        <td><a href="/?action=admin&sub=texts">Тексты</a></td>
        <td><a href="/?action=admin&sub=push">Push уведомления</a></td>
        <td><a href="/?action=admin&sub=logout">Выход</a></td>
    </tr>
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <td>ID</td>
        <td>Зарегистрирован</td>
        <td>Соц. сеть</td>
        <td>ID чата</td>
        <td>Имя</td>
        <td>Бан</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($banned as $f){?>
        <tr>
            <td><?php echo $f['id'];?></td>
            <td><?php echo $f['created'];?></td>
            <td><?php echo $f['app_type'];?></td>
            <td><?php echo $f['chat_id'];?></td>
            <td><?php echo $f['name'];?></td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="unban_him" value="<?php echo $f['id'];?>">
                    <button type="submit">Разбанить</button>
                </form>
            </td>
        </tr>
    <?php }?>
    </tbody>
</table>