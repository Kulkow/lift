<? $h = ! empty($h) ? $h : 202 ?>
<div id="lift_<? echo $lift->id ?>" class="lift <? echo $lift->status ?>" data-id="<? echo $lift->id ?>" data-status="<? echo $lift->status ?>" 
data-current="<? echo $lift->current ?>" data-up="<? echo implode(',', $lift->request('up')) ?>" data-down="<? echo implode(',',$lift->request('down')) ?>" data-level="<? echo $lift->level ?>" style="bottom: <? echo ($h * ($lift->current - 1)) ?>px;" >
    <div class="action">
        <div class="s">
        <? $l = $lift->house->level; ?>
            <? for($i = $l; $i >= 1; $i--){
                if($i % 5 == 0 AND $i !== $l){
                    echo '</div><div class="s">';
                }
                echo '<a href="#level_'.$i.'" class="l" data-level="'.$i.'" title="'.t('house.level.name').'-'.$i.'">'.$i.'</a>';
            } ?>
            </div>
    </div>
    <div class="piple"></div>
</div>