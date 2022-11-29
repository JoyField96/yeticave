
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
<?php $classname = isset($errors) ? "form--invalid" : ""; ?>
    <form class="form container <?= $classname; ?>" action="singup.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <?php $classname = isset($errors) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="email" name="email" placeholder="Введите e-mail">
            <span class="form__error"><?=$errors['email']?></span>
        </div>
        <?php $classname = isset($errors) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?=$errors['password']?></span>
        </div>
        <?php $classname = isset($errors) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя">
            <span class="form__error"><?=$errors['name']?></span>
        </div>
        <?php $classname = isset($errors) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться"></textarea>
            <span class="form__error"><?=$errors['message']?></span>
        </div>
        <?php $classname = !empty($errors) ? "form__error--bottom" : ""; ?>
        <span class="form__error <?= $classname; ?>">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
