<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $flights */
/** @var mixed $forPage */

if(!empty($_POST['select']))
    foreach($flights as $key => $flight)
        if($flight[$_POST['select']] != $_POST['val'])
            unset($flights[$key]);

$paged = false;

if(count($flights) > $forPage) {
    $paged = true;
    if (!$_GET['page'])
        $page = 1;
    else
        $page = $_GET['page'];
}

$pageCount = ((int)(count($flights) / $forPage)) + 1;


$fields = [
    'id' => 'ID',
    'type' => 'Тип',
    'no' => 'Номер',
    'from' => 'Откуда',
    'to' => 'Куда',
    'airlineCode' => 'Код',
    'code' => 'Код полный',
    'fromCode' => 'Откуда код',
    'fromAirport' => 'Откуда ст.',
    'fromCity' => 'Откуда город',
    'fromCountry' => 'Откуда страна',
    'toCode' => 'Куда код',
    'toAirport' => 'Куда ст.',
    'toCity' => 'Куда город',
    'toCountry' => 'Куда страна',
    'fromDate' => 'Дата отправления',
    'toDate' => 'Дата прибытия'
];



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
<h1>Направления</h1>
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
        <td>ID</td>
        <td>Тип</td>
        <td>Номер</td>
        <td>Откуда</td>
        <td>Куда</td>
        <td>Код</td>
        <td>Код полный</td>
        <td>Откуда код</td>
        <td>Откуда ст.</td>
        <td>Откуда город</td>
        <td>Откуда страна</td>
        <td>Куда код</td>
        <td>Куда ст.</td>
        <td>Куда город</td>
        <td>Куда страна</td>
        <td>Дата отправления</td>
        <td>Дата прибытия</td>
    </tr>
    </thead>
    <tbody>
    <?php if(!$paged){
        foreach($flights as $key => $flight){?>
            <tr>
                <td><?php echo $flight['id'];?></td>
                <td><?php echo $flight['type'] ? 'Поезд' : 'Самолет';?></td>
                <td><?php echo $flight['no'];?></td>
                <td><?php echo $flight['from'];?></td>
                <td><?php echo $flight['to'];?></td>
                <td><?php echo $flight['airlineCode'];?></td>
                <td><?php echo $flight['code'];?></td>
                <td><?php echo $flight['fromCode'];?></td>
                <td><?php echo $flight['fromAirport'];?></td>
                <td><?php echo $flight['fromCity'];?></td>
                <td><?php echo $flight['fromCountry'];?></td>
                <td><?php echo $flight['toCode'];?></td>
                <td><?php echo $flight['toAirport'];?></td>
                <td><?php echo $flight['toCity'];?></td>
                <td><?php echo $flight['toCountry'];?></td>
                <td><?php echo $flight['fromDate'];?></td>
                <td><?php echo $flight['toDate'];?></td>
            </tr>
    <?php }
    }else{?>
        <?php foreach($flights as $key => $flight){
            if($key > ($page - 1) * $forPage - 1 && $key < $page * $forPage){?>
                <tr>
                    <td><?php echo $flight['id'];?></td>
                    <td><?php echo $flight['type'] ? 'Поезд' : 'Самолет';?></td>
                    <td><?php echo $flight['no'];?></td>
                    <td><?php echo $flight['from'];?></td>
                    <td><?php echo $flight['to'];?></td>
                    <td><?php echo $flight['airlineCode'];?></td>
                    <td><?php echo $flight['code'];?></td>
                    <td><?php echo $flight['fromCode'];?></td>
                    <td><?php echo $flight['fromAirport'];?></td>
                    <td><?php echo $flight['fromCity'];?></td>
                    <td><?php echo $flight['fromCountry'];?></td>
                    <td><?php echo $flight['toCode'];?></td>
                    <td><?php echo $flight['toAirport'];?></td>
                    <td><?php echo $flight['toCity'];?></td>
                    <td><?php echo $flight['toCountry'];?></td>
                    <td><?php echo $flight['fromDate'];?></td>
                    <td><?php echo $flight['toDate'];?></td>
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