<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $man */
/** @var mixed $woman */
/** @var mixed $age18 */
/** @var mixed $age25 */
/** @var mixed $age38 */
/** @var mixed $age50 */
/** @var mixed $age100 */
/** @var mixed $from */
/** @var mixed $to */



?>
<style>
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Статистика</h1>
<table>
    <tbody>
    <tr class="no-border">
        <td><a href="/?action=admin&sub=users">Пользователи</a></td>
        <td><a href="/?action=admin&sub=stat">Статистика</a></td>
        <td><a href="/?action=admin&sub=feedback">Обращения</a></td>
        <td><a href="/?action=admin&sub=flights">Направления</a></td>
        <td><a href="/?action=admin&sub=banned">Забаненные</a></td>
        <td><a href="/?action=admin&sub=texts">Тексты</a></td>
    </tr>
    </tbody>
</table>

<table>
    <tbody>
        <tr><td style="text-align: center" colspan="2">М / Ж</td></tr>
        <tr>
            <td>Мужчин</td>
            <td><?php echo $man;?></td>
        </tr>
        <tr>
            <td>Женщин</td>
            <td><?php echo $woman;?></td>
        </tr>
        <tr><td style="text-align: center" colspan="2">Возрастные группы</td></tr>
        <tr>
            <td>< 18</td>
            <td><?php echo $age18;?></td>
        </tr>
        <tr>
            <td>18-25</td>
            <td><?php echo $age25;?></td>
        </tr>
        <tr>
            <td>25-38</td>
            <td><?php echo $age38;?></td>
        </tr>
        <tr>
            <td>38-50</td>
            <td><?php echo $age50;?></td>
        </tr>
        <tr>
            <td>> 50</td>
            <td><?php echo $age100;?></td>
        </tr>
        <tr><td style="text-align: center" colspan="2">Направления</td></tr>
        <tr>
            <td><b>Города отбытия:</b></td>
            <td><b>Кол-во путешественников:</b></td>
        </tr>
        <?php foreach($from as $city => $n){?>
            <tr>
                <td><?php echo $city;?></td>
                <td><?php echo $n;?></td>
            </tr>
        <?php }?>
        <tr>
            <td><b>Города прибытия:</b></td>
            <td><b>Кол-во путешественников:</b></td>
        </tr>
        <?php foreach($to as $city => $n){?>
            <tr>
                <td><?php echo $city;?></td>
                <td><?php echo $n;?></td>
            </tr>
        <?php }?>
    </tbody>
</table>