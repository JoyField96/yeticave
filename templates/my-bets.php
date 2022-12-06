<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($cat_mass as $key => $category):?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?= $category['name_category'];?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <?php if (!empty($bets)): ?>
        <table class="rates__list">
            <?php foreach ($bets as $key => $bet):?>
            <?php if ($bet["winner_id"] === $user_id): ?>
                   <tr class="rates__item rates__item--win">
                <?php else: ?>
            <tr class="rates__item">
                <?php endif; ?>
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $bet['img']?>" width="54" height="40" alt="<?= $bet['title']?>">
                    </div>
                    <div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= $bet["id"]; ?>"><?= $bet['title']?></a></h3>
                    <?php if ($bet["winner_id"] === $user_id): ?>
                    <p><?= $bet['contacts']?></p>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bet['name_category']?>
                </td>
                <td class="rates__timer">
                    <?php $time = get_time_left($bet["date_finish"]) ?>
                    <div class="timer <?php if ($time[0] < 1 && $time[0] != 0): ?>timer--finishing <?php elseif($time[0] == 0): ?>timer--end<?php endif; ?>">
                        <?php if ($time[0] != 0): ?>
                            <?= "$time[0] : $time[1]"; ?>
                        <?php else: ?>
                            Торги окончены
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= format_sum($bet['price_bet'])?>
                </td>
                <td class="rates__time">
                    <?= $bet["date_bet"];?>
                </td>
            </tr>
            <?php endforeach ?>
            <?php endif ?>

 <!--           <tr class="rates__item rates__item--end">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../img/rate7.jpg" width="54" height="40" alt="Сноуборд">
                    </div>
                    <h3 class="rates__title"><a href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>
                </td>
                <td class="rates__category">
                    Доски и лыжи
                </td>
                <td class="rates__timer">
                    <div class="timer timer--end">Торги окончены</div>
                </td>
                <td class="rates__price">
                    10 999 р
                </td>
                <td class="rates__time">
                    19.03.17 в 08:21
                </td>
            </tr>--!>
        </table>
    </section>
</main>
