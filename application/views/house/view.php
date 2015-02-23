<link rel="stylesheet" href="<?php echo URL::site('media/js/lift/lift.css') ?>" />
<script type="text/javascript" src="<?php echo URL::site('media/js/lift/lift.lang.js') ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo URL::site('media/js/lift/lift.js') ?>" charset="utf-8"></script>
    
<? $h = 202; // ������ ����� � �������  //$lifts = array();  ?>
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
                        <?php echo View::factory('lift/item')->bind('lift', $lift); ?>
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
<div id="logs">
    <div class="wrapper">
    
    
    </div>
</div>


<script>/**
* Application
*/

$(function(){
    It_lift.ini();
    
    var lifts = $('.lift');
    $('.level .actions A').click(function(){
        var a = $(this), _lift = a.data('lift'), _level = a.data('level'), _event = a.data('event'), _url = a.attr('href'), lift = $('#lift_' + _lift);
        if(lift.length > 0){
            if(! a.hasClass('active')){
                a.addClass('active');
                It_lift.post(_url, {lift:_lift, level:_level, direction:_event}, function(json){
                    if(json.request){
                        var  r_lift = json.lift, lift = $('#lift_' + r_lift.id);
                        console.log(json);
                        liftgo(lift, r_lift.level);
                        It_lift.log({lift: 'event:' + _event + ', lift:' + r_lift.id +',level ' + r_lift.level});
                    }else{
                    console.log(json);       
                    }
                })    
            }
        }
        return !1;
    })
    
    $('.lift .l').click(function(){
        var a = $(this), _level = a.data('level'), _url = a.attr('href'), lift = a.closest('.lift');
        if(lift.length > 0){
            if(! a.hasClass('a')){
                a.addClass('a');
                It_lift.post(_url, {level:_level}, function(json){
                    if(json.lift){
                        var  r_lift = json.lift, lift = $('#lift_' + r_lift.id);
                        console.log(json);
                        liftgo(lift, r_lift.level);
                        It_lift.log({lift: 'lift:' + r_lift.id +',level ' + r_lift.level});
                    }else{
                    console.log(json);       
                    }
                })    
            }
        }
        return !1;
    })
    
    /*
    * ���������� ��������� ��������� �����
    */
    //It_lift.post({})
})
</script>