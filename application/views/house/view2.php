<link rel="stylesheet" href="<?php echo URL::site('media/css/lift.css') ?>" />
<script type="text/javascript" src="<?php echo URL::site('media/js/lift.lang.js') ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo URL::site('media/js/lift.js') ?>" charset="utf-8"></script>
    
<? $h = 153; // высота этажа в верстке  //$lifts = array();  ?>
<section class="house">
    <h1><? echo t('house.name').' '.t('house.number').' &laquo;'.$house->number.'&raquo;'; ?></h1>
    <div class="block">
        
        <? foreach($house->levels() as $level): ?>
            <div class="level" data-id="<? echo $level ?>" id="level_<? echo $level ?>">
                <div class="cell number"><? echo t('house.level.name') ?><b><? echo $level ?></b></div> 
                <? foreach($lifts as $lift): ?>
                <div class="cell">
                    <div class="actions">
                        <ul>
                            <li><a href="/request/add#up" class="up" data-event="up" title="<? echo t('request.up') ?>" data-level="<? echo $level ?>" data-lift="<? echo $lift->id ?>"></a></li>
                            <li><a href="/request/add#down" class="down" data-event="down" title="<? echo t('request.down') ?>" data-level="<? echo $level ?>" data-lift="<? echo $lift->id ?>"></a></li>
                        </ul>
                    </div>
                    <? //if($lift->level == $level) : ?>
                    <? if($level == 1) : ?>
                        <div id="lift_<? echo $lift->id ?>" class="lift <? echo $lift->status ?>" data-status="<? echo $lift->status ?>" data-level="<? echo $lift->level ?>" style="bottom: <? echo ($h * $lift->level) ?>px;" ></div>
                    <? endif ?> 
                </div>
                <? endforeach ?>    
            </div>
            <? if($level > 1): ?>
                <div class="empty_level"></div>
            <? endif ?>
        <? endforeach ?>    
    </div>
</section>