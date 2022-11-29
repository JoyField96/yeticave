<nav class="nav">
    <ul class="nav__list container">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($cat_mass as $key => $category):?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $category['name_category'];?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<?php $classname = isset($chek_mail) || isset($chek_pass) ? "form--invalid" : ""; ?>
<form class="form container <?= $classname; ?>" action="login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $classname = isset($chek_mail)  ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail">
        <span class="form__error"><?=$chek_mail?></span>
    </div>
    <?php $classname = isset($chek_pass) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=$chek_pass?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
