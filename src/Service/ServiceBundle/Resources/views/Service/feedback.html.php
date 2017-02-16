<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $feedback */
/** @var mixed $forPage */


if(!empty($_POST['select']))
    foreach($feedback as $key => $flight)
        if($flight[$_POST['select']] != $_POST['val'])
            unset($feedback[$key]);

if(count($feedback) > $forPage) {
    $paged = true;
    if (!$_GET['page'])
        $page = 1;
    else
        $page = $_GET['page'];
}


$fields = [
    'name' => 'Имя',
    'email' => 'Email',
    'created' => 'Дата обращения'
];

$pageCount = ((int)(count($feedback) / $forPage)) + 1;
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
<h1>Обращения</h1>
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
<span><b>Поиск &nbsp;</b></span>
<form style="display: inline;" action="" method="post">
    <select name="select">
        <?php foreach($fields as $key => $field){?>
            <option value="<?php echo $key;?>"><?php echo $field;?></option>
        <?php }?>
    </select>
    <input type="text" name="val"/>
    <button type="submit">Искать</button>
</form>
<table>
    <thead>
        <tr>
            <td>Имя</td>
            <td>Email</td>
            <td>Текст</td>
            <td>Дата обращения</td>
        </tr>
    </thead>
    <tbody>
        <?php if(!$paged){?>
            <?php foreach($feedback as $f){?>
                <tr>
                    <td><?php echo $f['name'];?></td>
                    <td><?php echo $f['email'];?></td>
                    <td><?php echo $f['text'];?></td>
                    <td><?php echo $f['created'];?></td>
                </tr>
            <?php }?>
        <?php }else{?>
            <?php foreach($feedback as $key => $f){?>
                <?php if($key > ($page - 1) * $forPage - 1 && $key < $page * $forPage){?>
                    <tr>
                        <td><?php echo $f['name'];?></td>
                        <td><?php echo $f['email'];?></td>
                        <td><?php echo $f['text'];?></td>
                        <td><?php echo $f['created'];?></td>
                    </tr>
                <?php }?>
            <?php }?>
        <?php }?>
    </tbody>
</table>
<?php
if($paged) {
    if (!$_GET['sub']) $sub = 'users';
    else $sub = $_GET['sub'];
    for ($i = 0; $i < $pageCount; $i++) {
        if ($page == $i + 1) {
            ?>
            <?php echo $i + 1; ?>
        <?php } else {
            ?>
            <a href="/?action=admin&sub=<?php echo $_GET['sub']; ?>&page=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a>
        <?php } ?>
    <?php }
}