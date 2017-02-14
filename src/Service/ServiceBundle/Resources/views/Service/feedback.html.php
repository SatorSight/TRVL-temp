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
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Обращения</h1>
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