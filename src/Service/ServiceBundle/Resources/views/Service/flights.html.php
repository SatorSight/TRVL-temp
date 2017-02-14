<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $flights */

$paged = false;
//
//if(count($flights) > 10) {
//    $paged = true;
//    if (!$_GET['page'])
//        $page = 1;
//
//
//}

?>
<style>
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Направления</h1>
<table>
    <tbody>
    <tr class="no-border">
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
        <?php foreach($flights as $flight){?>
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
        </tbody>
    </table>
<?php