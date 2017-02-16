<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var $pushes $feedback */
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
<h1>Push уведомления</h1>
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
<table style="width: 100%;">
    <thead>
    <tr>
        <td>Назначение</td>
        <td>Текст</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($pushes as $p){?>
        <?php if($p['id'] < 3){?>
            <tr>
                <td><?php echo $p['label'];?></td>
                <form action="" method="post">
                    <td>

                        <input type="hidden" name="push_id" value="<?php echo $p['id'];?>">
                        <textarea style="width: 100%;" name="push_val"><?php echo $p['value'];?></textarea>

                    </td>
                    <td style="width: 20px; border: none;"><button type="submit">Сохранить</button> </td>
                </form>
            </tr>
        <?php }?>
    <?php }?>
    </tbody>
</table>
<span style="color: red">* Вместо #VAR# будет подставлено имя.</span>


<table style="width: 100%;">
    <thead>
    <tr>
        <td>Назначение</td>
        <td>Текст</td>
        <td>Текст ссылки</td>
        <td>URL</td>
    </tr>
    </thead>
    <tbody>
        <?php foreach($pushes as $p){?>
            <?php if($p['id'] > 2){?>
                <tr>
                    <td><?php echo $p['label'];?></td>
                    <form action="" method="post">
                        <td>
                            <input type="hidden" name="text_id" value="<?php echo $p['id'];?>">
                            <textarea style="width: 100%;" name="text_val"><?php echo $p['value'];?></textarea>

                        </td>
                        <td>
                            <input type="text" name="text_link_label" value="<?php echo $p['link_text'];?>">
                        </td>
                        <td>
                            <input type="text" name="text_link_val" value="<?php echo $p['link_val'];?>">
                        </td>
                        <td style="width: 20px; border: none;"><button type="submit">Сохранить</button> </td>
                    </form>
                </tr>
            <?php }?>
        <?php }?>
    </tbody>
</table>
<span style="color: red">* В поле текст для "Поделиться в рейсе" доступны следующие подстановки:<br>
    #ROUTE# - "город отправления - город прибытия"<br>
    #DATE# - дата отбытия<br>
    Например, "Я путешествую из #FROM# в #TO# #DATE# числа!"<br><br>
    Если оставить поле "Текст ссылки" или "URL" пустыми, ссылки не будет
</span>