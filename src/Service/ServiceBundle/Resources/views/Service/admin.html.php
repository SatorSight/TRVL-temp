<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $users */
/** @var mixed $forPage */


if(!empty($_POST['select'])){

    if($_POST['select'] == 'app_type'){
        if($_POST['val'] == 'Вконтакте') $_POST['val'] = 'vk';
        if($_POST['val'] == 'Facebook') $_POST['val'] = 'fb';
        if($_POST['val'] == 'Instagram') $_POST['val'] = 'in';
        if($_POST['val'] == 'Одноклассники') $_POST['val'] = 'ok';
    }

    if($_POST['select'] == 'orientation'){
        if($_POST['val'] == 'Гетеро') $_POST['val'] = 0;
        if($_POST['val'] == 'Би') $_POST['val'] = 1;
        if($_POST['val'] == 'Без предубеждений') $_POST['val'] = 2;
    }
    if($_POST['select'] == 'free'){
        if($_POST['val'] == 'Свободен') $_POST['val'] = 0;
        if($_POST['val'] == 'Св. отношения') $_POST['val'] = 1;
        if($_POST['val'] == 'В паре') $_POST['val'] = 2;
    }

    foreach ($users as $key => $flight)
        if ($flight[$_POST['select']] != $_POST['val'])
            unset($users[$key]);
}

$fields = [
    'id' => 'ID',
    'created' => 'Добавлен',
    'banned' => 'Забанен',
    'app_type' => 'Соц. сеть',
    'chat_id' => 'ID чата',
    'about' => 'О себе',
    'code' => 'Имя',
    'orientation' => 'Ориентация',
    'appearance' => 'Внешность',
    'age' => 'Возраст',
    'city' => 'Город',
    'sex' => 'Пол',
    'wannaCommunicate' => 'Хочет общаться',
    'findCompanion' => 'Найти попутчика',
    'findCouple' => 'Найти пару',
    'findFriends' => 'Найти друзей',
    'free' => 'Отношения'
];

if(count($users) > $forPage) {
    $paged = true;
    if (!$_GET['page'])
        $page = 1;
    else
        $page = $_GET['page'];
}

$pageCount = ((int)(count($users) / $forPage)) + 1;
?>
<style>
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Пользователи</h1>
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
            <td>Добавлен</td>
            <td>Забанен</td>
            <td>Соц. сеть</td>
            <td>ID чата</td>
            <td>О себе</td>
            <td>Имя</td>
            <td>Ориентация</td>
            <td>Внешность</td>
            <td>Возраст</td>
            <td>Город</td>
            <td>Пол</td>
            <td>Хочет общаться</td>
            <td>Найти попутчика</td>
            <td>Найти пару</td>
            <td>Найти друзей</td>
            <td>Отношения</td>
            <td>Бан</td>
        </tr>
    </thead>
    <tbody>
        <?php if(!$paged){?>
            <?php foreach($users as $user){?>
                <tr>
                    <td><?php echo $user['id'];?></td>
                    <td><?php echo $user['created'];?></td>
                    <td><?php echo $user['banned'] ? 'Да' : 'Нет';?></td>
                    <td><?php
                        if($user['app_type'] == 'vk') echo 'Вконтакте';
                        if($user['app_type'] == 'fb') echo 'Facebook';
                        if($user['app_type'] == 'in') echo 'Instagram';
                        if($user['app_type'] == 'ok') echo 'Одноклассники';
                        ?>
                    </td>
                    <td><?php echo $user['chat_id'];?></td>
                    <td><?php echo $user['about'];?></td>
                    <td><?php echo $user['name'];?></td>
                    <td>
                        <?php
                        if($user['orientation'] == 0) echo 'Гетеро';
                        if($user['orientation'] == 1) echo 'Би';
                        if($user['orientation'] == 2) echo 'Без предубеждений';
                        ?>
                    </td>
                    <td><?php echo $user['appearance'];?></td>
                    <td><?php echo $user['age'];?></td>
                    <td><?php echo $user['city'];?></td>
                    <td><?php echo $user['sex'] ? 'Ж' : 'М';?></td>
                    <td><?php echo $user['wannaCommunicate'] ? 'Да' : 'Нет';?></td>
                    <td><?php echo $user['findCompanion'] ? 'Да' : 'Нет';?></td>
                    <td><?php echo $user['findCouple'] ? 'Да' : 'Нет';?></td>
                    <td><?php echo $user['findFriends'] ? 'Да' : 'Нет';?></td>
                    <td>
                        <?php
                        if($user['free'] == 0) echo 'Свободен';
                        if($user['free'] == 1) echo 'Св. отношения';
                        if($user['free'] == 2) echo 'В паре';
                        ?>
                    </td>
                    <td>
                        <?php if(!$user['banned']){?>
                            <form action="" method="post">
                                <input type="hidden" name="ban_him" value="<?php echo $user['id'];?>"/>
                                <button type="submit">Забанить</button>
                            </form>
                        <?php }else{ ?>
                            Забанен
                        <?php }?>
                    </td>
                </tr>
            <?php }?>
        <?php }else{?>
            <?php foreach($users as $key => $user){?>
                <?php if($key > ($page - 1) * $forPage - 1 && $key < $page * $forPage){?>
                    <tr>
                        <td><?php echo $user['id'];?></td>
                        <td><?php echo $user['created'];?></td>
                        <td><?php echo $user['banned'] ? 'Да' : 'Нет';?></td>
                        <td><?php
                            if($user['app_type'] == 'vk') echo 'Вконтакте';
                            if($user['app_type'] == 'fb') echo 'Facebook';
                            if($user['app_type'] == 'in') echo 'Instagram';
                            if($user['app_type'] == 'ok') echo 'Одноклассники';
                            ?>
                        </td>
                        <td><?php echo $user['chat_id'];?></td>
                        <td><?php echo $user['about'];?></td>
                        <td><?php echo $user['name'];?></td>
                        <td>
                            <?php
                            if($user['orientation'] == 0) echo 'Гетеро';
                            if($user['orientation'] == 1) echo 'Гей';
                            if($user['orientation'] == 2) echo 'Би';
                            ?>
                        </td>
                        <td><?php echo $user['appearance'];?></td>
                        <td><?php echo $user['age'];?></td>
                        <td><?php echo $user['city'];?></td>
                        <td><?php echo $user['sex'] ? 'Ж' : 'М';?></td>
                        <td><?php echo $user['wannaCommunicate'] ? 'Да' : 'Нет';?></td>
                        <td><?php echo $user['findCompanion'] ? 'Да' : 'Нет';?></td>
                        <td><?php echo $user['findCouple'] ? 'Да' : 'Нет';?></td>
                        <td><?php echo $user['findFriends'] ? 'Да' : 'Нет';?></td>
                        <td>
                            <?php
                            if($user['free'] == 0) echo 'Свободен';
                            if($user['free'] == 1) echo 'Св. отношения';
                            if($user['free'] == 2) echo 'В паре';
                            ?>
                        </td>
                        <td>
                            <?php if(!$user['banned']){?>
                                <form action="" method="post">
                                    <input type="hidden" name="ban_him" value="<?php echo $user['id'];?>"/>
                                    <button type="submit">Забанить</button>
                                </form>
                            <?php }else{ ?>
                                Забанен
                            <?php }?>
                        </td>
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