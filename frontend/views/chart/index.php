<?php

/* @var $this yii\web\View */

/* @var $atom \common\models\Atom */
/* @var $ion string */
/* @var $transitions_list \common\models\Transition */
/* @var $atom_name \common\models\Atom->periodicTable->ABBR */

$this->title = Yii::t('app', 'Grotrian Chart - {Z}', ['Z' => $atom_name]);
?>
<br>
<div id="panel">
    <div class="container_12">
        <div class="grid_9">
            <h4><?= Yii::t('chart', 'Data filter')?></h4>

            <form name="inputform" action="">
                <table class="search_form">
                    <tbody>

                    <tr>
                        <td></td>
                        <td align="center"><div class="froam"><?= Yii::t('chart', 'from')?></div><div class="froam" ><?= Yii::t('chart', 'to')?></div></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Wavelength')?>:</td>
                        <td>
                            <input  size="12" type="text" name="waveMinVal" value="<?= $wlmin ?>"/>
                            <input size="12" type="text" name="waveMaxVal" value="<?= $wlmax ?>"/>
                        </td>

                        <td class="dimension">
                            [&#197;]
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>

                        </td>
                    </tr>

                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Energy')?>:</td>
                        <td>
                            <input  size="12" type="text" name="energyMinVal" value="<?= $enmin ?>"/>
                            <input size="12" type="text" name="energyMaxVal" value="<?= $enmax ?>"/>
                        </td>

                        <td class="dimension">
                            <?= Yii::t('chart', 'cm')?><sup>-1</sup>
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Show autoionization states')?>:</td>
                        <td>
                            <input  type="checkbox" name="autoStates" <?php if (!$autoStatesOff):?> checked <?php endif;?>/>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Maximum n')?>:</td>
                        <td>
                            <input size="1" type="text" name="nMaxVal" value="<?= $nmax ?>"/>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Maximum l')?>:</td>
                        <td>
                            <input size="1" type="text" name="lMaxVal" value="<?= $lmax ?>"/>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Group by multiplicity')?>:</td>
                        <td>
                            <input  type="checkbox" name="groupbyMu" <?php if ($groupbyMu):?> checked <?php endif;?>/>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Show prohibited lines (by multiplicity)')?>:</td>
                        <td>
                            <input  type="checkbox" name="prohibitedbyMuOff" <?php if (!$prohibitedbyMuOff):?> checked <?php endif;?>/>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Show prohibited lines (by parity)')?>:</td>
                        <td>
                            <input  type="checkbox" name="prohibitedbyParOff" <?php if (!$prohibitedbyParOff):?> checked <?php endif;?>/>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Level grouping')?>:</td>
                        <td>
                            <input  type="radio" name="grouping" value="term" <?php if ($grouping == "term"):?> checked <?php endif;?>/><?= Yii::t('chart', 'By Term')?><br>
                            <input  type="radio" name="grouping" value="J" <?php if ($grouping == "J"):?> checked <?php endif;?>/><?= Yii::t('chart', 'By J')?><br>
                            <input  type="radio" name="grouping" value="full" <?php if ($grouping == "full"):?> checked <?php endif;?>/><?= Yii::t('chart', 'No grouping')?><br>
                            <input  type="radio" name="grouping" value="auto" <?php if (!$grouping):?> checked <?php endif;?>/><?= Yii::t('chart', 'Auto')?><br>
                        </td>
                        <td class="dimension">
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td class="name"><?= Yii::t('chart', 'Diagram width')?>:</td>
                        <td>
                            <input  size="5" type="text" name="widthVal"  value="<?= $width ?>"/>
                        </td>

                        <td class="dimension">
                            px
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>

                        </td>
                    </tr>

                    <tr>
                        <td  class="name"></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td  class="name"></td>
                        <td>
                            <input class="button white" id="filterBtn" value='<?= Yii::t('chart', 'Apply')?>' type="button" style="width: 60px"/>
                            <input class="button white" id="showAllBtn" value='<?= Yii::t('chart', 'Show All')?>' type="button" style="width: 80px"/>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="clear">  </div>
</div>
<!--END of Panel-->

<div class="slide">
    <a href="#" class="btn-slide"></a>
</div>

<!--End of Slide-->

<!--<div id="svg" style="width: 100%; height: 600px; overflow-x:auto"><?/*= $svg */?></div>-->


<?php

$js = <<< JS
$(document).ready(function() {
    $("#filterBtn").click(function(){
        var waveMinVal = document.inputform.waveMinVal.value;
        var waveMaxVal = document.inputform.waveMaxVal.value;
	    var energyMinVal = document.inputform.energyMinVal.value;
	    var energyMaxVal = document.inputform.energyMaxVal.value;
        var nMaxVal = document.inputform.nMaxVal.value;
        var lMaxVal = document.inputform.lMaxVal.value;
        var widthVal = document.inputform.widthVal.value;
        var groupbyMu = document.inputform.groupbyMu.checked;
        var prohibitedbyMuOff = document.inputform.prohibitedbyMuOff.checked;
        var prohibitedbyParOff = document.inputform.prohibitedbyParOff.checked;
        var autoStates = document.inputform.autoStates.checked;
        var groupingVal = document.inputform.grouping.value;
        
        var query = new Array();
        if (waveMinVal!="" && waveMinVal!=0 )  query.push("wlmin=" + waveMinVal);
        if (waveMaxVal!="" && waveMaxVal!=0 ) query.push("wlmax=" + waveMaxVal);
        if (energyMinVal!="" && energyMinVal!=0 ) query.push("enmin=" + energyMinVal);
        if (energyMaxVal!="" && energyMaxVal!=0 ) query.push("enmax=" + energyMaxVal);
        if (nMaxVal!="" && nMaxVal!=0 ) query.push("nmax=" + nMaxVal);
        if (lMaxVal!="" && lMaxVal!=0 ) query.push("lmax=" + lMaxVal);
        if (widthVal!="" && widthVal!=0) query.push("width=" + widthVal);
        if (groupbyMu) query.push("groupbyMu");
        if (!prohibitedbyMuOff) query.push("prohibitedbyMuOff");
        if (!prohibitedbyParOff) query.push("prohibitedbyParOff");
        if (!autoStates) query.push("autoStatesOff");
        if (groupingVal && groupingVal != 'auto') query.push("grouping=" + groupingVal);

        if (query.length > 0)
            location.replace("?" + query.join("&"));
        else location.replace("?");
    });
    $("#showAllBtn").click(function(){
        location.replace("?");
    });

    // Make slide search panel
    $(".btn-slide").unbind('click');
    $(".btn-slide").click(function(){
        $("#panel").slideToggle("slow");
        $(this).toggleClass("active");
        $("#panel div").addClass('tpanel');
    });

});
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>
