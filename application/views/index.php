<h1>Компьютерный центр</h1>
<?php // Slider::render('home') ?>
<?php //Banner::render('home') ?>

<h1><? t('site.name')?></h1>

<?
function itLevel($count, $end = 1, $step = 1) {
    for ($i = $count; $i >= $end; $i += $step) {
        yield $i;
    }
}
 ?>