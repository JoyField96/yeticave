
<header class="main-header">

    <div class="main-header__container container">
        <h1 class="visually-hidden">YetiCave</h1>
        <a class="main-header__logo" href="/">
            <img src="../img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
        </a>
        <form class="main-header__search" method="get" action="search.php" autocomplete="off">
            <input type="search" name="search" placeholder="Поиск лота">
            <input class="main-header__search-btn" type="submit" name="find" value="Найти">
        </form>
        <?php $url = !$is_auth ? "403.php":"add.php"; ?>
        <a class="main-header__add-lot button" href="<?=$url?>">Добавить лот</a>

        <nav class="user-menu">
            <?php if ($is_auth): ?>
                <div class="user-menu__logged">
                    <p><?= $user_name; ?></p>
                    <a  class="user-menu__bets" href="pages/my-bets.html">Мои ставки</a>
                    <a  class="user-menu__logout" href="logout.php">Выход</a>
                </div>
            <?php else: ?>
                <ul class="user-menu__list">
                    <li class="user-menu__item">
                        <a href="singup.php">Регистрация</a>
                    </li>
                    <li class="user-menu__item">
                        <a href="login.php">Вход</a>
                    </li>
                </ul>
            <?php endif; ?>
        </nav>
    </div>
</header>
