<?php

/* @var $this yii\web\View */

/* @var $atom \common\models\Atom */
/* @var $ion string */
/* @var $transitions_list \common\models\Transition */
/* @var $atom_name \common\models\Atom->periodicTable->ABBR */

$this->title = Yii::t('app', 'Grotrian Chart - {Z}', ['Z' => $atom_name]);
$this->registerCssFile('@web/css/svg.css');

use common\models\Svg; ?>
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

<div id="svg" style="width: 100%; height: 600px; overflow-x:auto">

    <svg preserveAspectRatio="xMinYMin"
         id="svg_with_diagram"
         viewBox="0 0 <?=$diagram_w?> <?=$diagram_h + 2?>">

        <desc><?=$abbr?></desc>

        <!-- Шкала энергий -->
        <g class="Ecm" id="Ecm">
            <text class="Ecm" x="<?=0.7*$Ecm_row_w?>" y="<?=0.3*$conf_row_h?>">U</text><text
                    class="Ecm" x="<?=$Ecm_row_w - 5?>" y="<?=0.6*$conf_row_h?>">[cm<tspan
                        class="index" dy="<?=-$index_dy?>">-1</tspan><tspan dy="<?=$index_dy?>">]</tspan></text><text
                    class="Ecm" x="<?=$Ecm_row_w - 5?>" y="<?=$diagram_h?>">0</text><text
                    class="Ecm" x="<?=$Ecm_row_w - 1?>" y="<?=$diagram_h - $graph_y?>"><?=$min_limit?></text>

            <!-- for view level energy -->
            <?foreach ($levels as $column){
                foreach($column['atomiccore'] as $atomiccore) {
                    foreach ($atomiccore['term'] as $term) {
                        foreach ($term['group'] as $group) {
                            foreach ($group['level'] as $level) { ?>
                                <line class="energy" id="lbl_<?= $level['ID'] ?>" x1="<?= $Ecm_row_w - 1 ?>"
                                      x2="<?= $Ecm_row_w + 3 ?>" display="none"
                                      y1="<?= Svg::convert_energy($level['ENERGY']) ?>"
                                      y2="<?= Svg::convert_energy($level['ENERGY']) ?>">
                                </line>
                                <text class="Ecml" id="txt_lbl_<?= $level['ID'] ?>" x="<?= $Ecm_row_w - 1 ?>" display="none"
                                      y="<?= Svg::convert_energy($level['ENERGY']) ?>"><?= round($level['ENERGY'], 1) ?></text>
                                <?
                            }
                        }
                    }
                }
            }?>
            <!-- END for view level energy -->
            <!-- Кажется рассчитано, максимум на два лимита. Исправить -->
            <?if ($n_limits > 1){?>
                <text class="Ecm" x="<?=$Ecm_row_w - 1?>"
                      y="<?=$diagram_h - $graph_y - 0.5*$term_row_h?>"><?=$max_limit?></text>
            <?}?>
            <!-- Устанавливаем рызрывы на шкалу -->
            <?foreach($breaks as $break){?>
                <text class="break" x="<?=$Ecm_row_w?>" y="<?=Svg::convert_energy($break['l1']['value'])?>">~<tspan
                            dy="-4" dx="-15">~</tspan></text>
            <?}?>
            <!-- Устанавливаем метки по шкале энергий -->
            <?Svg::set_labels($Ecm_row_w, -1, 'Ecm', 1, 1, $dE);?>
        </g>
        <g class="Data" transform="translate(<?=$Ecm_row_w?>, 0)">
            <?$n_col = 0;
            $translate = 0;
            foreach ($levels as $column){
                $n_col++;
                $n_terms_in_config = 0;
                foreach($column['atomiccore'] as $atomiccore)
                    foreach($atomiccore['term'] as $term)
                        foreach($term['group'] as $group)
                            $n_terms_in_config++;
                $col_w = $n_terms_in_config*$term_row_w;
                ?>
                <g class="column" id="col_<?=$n_col?>" transform="translate(<?=$translate?>, 0)">
                    <rect y="0" x="0" height="<?=$conf_row_h?>" width="<?=$col_w?>" id="recConf_<?=$n_col?>"> </rect>
                    <text class="config" y="<?=0.5*$conf_row_h?>" x="<?=0.5*$col_w?>"
                          rec_id="recConf_<?=$n_col?>"><?=Svg::create_indexes($column['CELLCONFIG'])?></text>
                    <?$n_core = 0;
                    $core_x = 0;
                    foreach ($column['atomiccore'] as $atomiccore) {
                        $n_core++;
                        $n_terms_in_core = 0;
                        foreach($atomiccore['term'] as $term)
                            foreach($term['group'] as $group)
                                $n_terms_in_core++;

                        $core_w = $n_terms_in_core*$term_row_w;
                        ?>
                        <g class="core">
                            <rect x="<?=$core_x?>" height="<?=$core_row_h?>" width="<?=$core_w?>" y="<?=$conf_row_h?>"
                                  id="recCore_<?=$n_core?>"></rect>
                            <?if ($atomiccore['ATOMICCORE'] != ''){?>
                                <text class="config" y="<?=0.5*$core_row_h + $conf_row_h?>" x="<?=$core_x + 0.5*$core_w?>"
                                      rec_id="recCore_<?=$n_core?>"><?=Svg::create_indexes($atomiccore['ATOMICCORE'])?></text>
                            <?}?>
                            <?
                            $n_term = 0;
                            foreach($atomiccore['term'] as $term) {
                                $dx = 0;
                                ?>
                                <g class="term" prefix="<?=$term['TERMPREFIX']?>" parity="<?=$term['TERMMULTIPLY']?>"><?
                                    foreach ($term['group'] as $group){
                                        $n_term++;
                                        $child_x = ($n_term-0.5) * $term_row_w + $core_x;
                                        $n_levels_in_term = 0;
                                        foreach($group['level'] as $level)
                                            $n_levels_in_term++;
                                        ?><rect x="<?=($n_term-1)*$term_row_w + $core_x + $dx?>" y="<?=$conf_row_h+$core_row_h?>" width="<?=$term_row_w?>" id="recTerm_<?=$n_term?>"
                                                L="<?=$group['TERMFIRSTPART']?>"
                                                prefix="<?=$term['TERMPREFIX']?>"
                                                parity="<?=$term['TERMMULTIPLY']?>"
                                                j="<?=$group['J']?>"
                                                n_levels="<?=$n_levels_in_term?>"
                                        <?
                                        if ($n_limits==1){?>
                                            height="<?=$term_row_h?>"
                                            <?if ($group['level'][0]['ENERGY'] > $min_limit){?> info="no" <?}
                                        }
                                        if ($n_limits > 1){
                                            if ($group['level'][count($group['level'])-1]['ENERGY'] > $min_limit) {?>
                                                height="<?=$term_row_h * 0.5?>" type="auto"
                                            <?} else{?> height="<?=$term_row_h?>" <?}
                                        }
                                        ?>
                                        ></rect>
                                        <text class="config" x="<?=$child_x + $dx?>" type="full"
                                            <?if ($n_limits == 1){?>
                                                y="<?=0.5*$term_row_h + $core_row_h + $conf_row_h?>"
                                            <?}elseif ($group['level'][count($group['level'])-1]['ENERGY'] > $min_limit){?>
                                                y="<?=0.25*$term_row_h + $core_row_h + $conf_row_h?>"
                                            <?}else{?>
                                                y="<?=0.5*$term_row_h + $core_row_h + $conf_row_h?>"
                                            <?}?>
                                              rec_id="recTerm_<?=$n_term?>"><?=$group['TERMSECONDPART']?><tspan class="index" dx="<?=-$index_dx?>"
                                                                                                                dy="<?=-$index_dy?>"><?=$term['TERMPREFIX']?></tspan><tspan dx="<?=-$index_dx?>"
                                                                                                                                                                            dy="<?=$index_dy?>"><?=$group['TERMFIRSTPART']?></tspan><?if ($term['TERMMULTIPLY'] != 0){?><tspan class="index"
                                                                                                                                                                                                                                                                               dx="<?=-$index_dx?>" dy="<?=-$index_dy?>">o</tspan><tspan class="index"
                                                                                                                                                                                                                                                                                                                                         dx="<?=-$index_dy?>" dy="<?=2*$index_dy?>"><?=$group['J']?></tspan><?}else{?><tspan class="index"
                                                                                                                                                                                                                                                                                                                                                                                                                             dx="<?=-$index_dx?>" dy="<?=$index_dy?>"><?=$group['J']?></tspan><?}?></text>
                                        <g class="levels"><line class="<?=$level['TERMMULTIPLY']!=0?'odd_level':'level'?>" x1="<?=$child_x + $dx?>" x2="<?=$child_x + $dx?>"
                                                                y2="<?=Svg::convert_energy($group['level'][0]['ENERGY'])?>"
                                                <?if ($n_limits == 1){?>
                                                    y1="<?=Svg::convert_energy($min_limit)?>"
                                                <?}elseif ($group['level'][count($group['level'])-1]['ENERGY'] > $min_limit){?>
                                                    y1="<?=Svg::convert_energy($max_limit)?>"
                                                <?}else{?>
                                                    y1="<?=Svg::convert_energy($min_limit)?>"
                                                <?}?>
                                            ></line>
                                            <?foreach($group['level'] as $level){?>
                                                <line class="<?=$level['TERMMULTIPLY']!=0?'odd_level':'level'?>"
                                                      energy="<?=$level['ENERGY']?>" onmouseover="mouse_on_level(evt, this)" onmouseout="mouse_out_level(evt, this)"
                                                      config="<?=$level['CONFIG']?>" j="<?=$level['J']?>"
                                                      id="<?=$level['ID']?>"
                                                      y1="<?=Svg::convert_energy($level['ENERGY'])?>"
                                                      y2="<?=Svg::convert_energy($level['ENERGY'])?>"
                                                      x1="<?=$child_x - $level_dx + $dx?>"
                                                      x2="<?=$child_x + $level_dx + $dx?>"
                                                      <?if($level['long'] == 1){?>long="1"<?}?>
                                                ></line>
                                                <circle class="<?=$level['TERMMULTIPLY']!=0?'odd_level':'level'?>" cx="<?=$child_x + $dx?>" cy="<?=Svg::convert_energy($level['ENERGY'])?>" r="3"></circle>
                                                <text class="namelevel" id="conf_name_<?=$level['ID']?>" x="<?=$child_x + $level_dx + $dx?>" display="none"
                                                      y="<?=Svg::convert_energy($level['ENERGY'])?>"
                                                ><?$text = []; $text[] = $level['FULL_CONFIG'];
                                                    if ($level['J'] != '') $text[] = 'j=' .$level['J'];
                                                    echo Svg::create_indexes(implode(', ', $text));?>
                                                </text>
                                            <?}?>
                                        </g>
                                    <?}?>
                                </g>
                            <?}?>
                        </g>
                        <?$core_x += $core_w;
                    }?>
                </g>
                <?$translate += $col_w;
            }?>
            <rect class="AllData" id="AllData" width="<?=$term_row_w*$n_terms?>" height="<?=$diagram_h+2?>" y="0" x="0"> </rect>

            <!-- transitions-->
            <g id="transitions">
                <?foreach($lines as $line){
                    if (isset($_REQUEST['prohibitedbyMuOff']) && isset($line['prohibited']) && $line['prohibited'] == 'multiplicity') continue;
                    if (isset($_REQUEST['prohibitedbyParOff']) && isset($line['prohibited']) && $line['prohibited'] == 'parity') continue;
                    if (isset($_REQUEST['wlmax']) && $line['WAVELENGTH'] > $_REQUEST['wlmax']) continue;
                    if (isset($_REQUEST['wlmin']) && $line['WAVELENGTH'] < $_REQUEST['wlmin']) continue;
                    ?>
                    <line class="transition" onclick="click_on_tr(evt, '<?=$line['ID']?>')" onmouseover="mouse_on_tr(evt, '<?=$line['ID']?>')" onmouseout="mouse_out_tr(evt, '<?=$line['ID']?>')"
                          id="<?=$line['ID']?>"
                          low_level="<?=$line['lowerLevel']['ID']?>"
                          high_level="<?=$line['upperLevel']['ID']?>"
                          rating="<?=$line['rating']?>"
                          dx="0"
                          <?if(isset($line['prohibited']) && $line['prohibited'] == 'multiplicity'){?>stroke-dasharray="5, 2"<?}?>
                          <?if(isset($line['prohibited']) && $line['prohibited'] == 'parity'){?>stroke-dasharray="2, 5"<?}?>
                          wavelength="<?=$line['WAVELENGTH']?>"></line>
                    <rect class="fortext" width="1" height="6" transform="" display="none"
                          id="rect_<?=$line['ID']?>"></rect>
                    <text class="transition" transform="" display="none"
                          id="txt_<?=$line['ID']?>"
                          onclick="click_on_tr_text(evt, '<?=$line['ID']?>')" onmouseover="mouse_on_tr(evt, '<?=$line['ID']?>')" onmouseout="mouse_out_tr(evt, '<?=$line['ID']?>')"><?=round($line['WAVELENGTH'], 4)?></text>
                <?}?>
            </g>
            <text class="name" id="Abbr" x="<?=$term_row_w*$n_terms - 5?>" y="<?=$diagram_h - 5?>"><?=$abbr?></text>

            <g class="Eev" id="EeV" transform="translate(<?=$n_terms * $term_row_w?>, 0)">
                <text class="Eev" x="<?=0.3*$term_row_w?>" y="<?=0.3*$conf_row_h?>">U</text><text
                        class="Eev" x="5" y="<?=0.6*$conf_row_h?>">[eV]</text><text
                        class="Eev" x="5" y="<?=$diagram_h?>">0</text><text
                        class="Eev" x="1" y="<?=$diagram_h - $graph_y?>"><?=round($min_limit*$toeV, 1)?></text>

                <!-- Кажется рассчитано, максимум на два лимита. Исправить -->
                <?if ($n_limits > 1){ ?>
                    <text class="Eev" x="1" y="<?=$diagram_h - $graph_y - 0.5*$term_row_h?>"
                    ><?=round($max_limit * $toeV, 1)?></text>
                <?}?>

                <!-- Устанавливаем рызрывы на шкалу -->
                <?foreach($breaks as $break){?>
                    <text class="break" x="0" y="<?=Svg::convert_energy($break['l1']['value'])?>"
                    >~<tspan dy="-4" dx="-15">~</tspan></text>
                <?}?>
                <!-- Устанавливаем разметку по шкале энергии -->
                <?Svg::set_labels(0, 1, 'Eev', 1, $toeV, $dE);?>
            </g>
        </g>
    </svg>
</div>


<?php

if ($grouping == '') $gr = 'auto';
else $gr = $grouping;

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
    
    function fix_viewBox() {
        var nTW = table_width + 100;
        if (window.svgDocument)
            window.svgDocument.rootElement.setAttribute('viewBox', '0 0 '+ nTW + ' ' + table_height);
        else
            window.document.getElementById("svg_with_diagram").setAttribute('viewBox', '0 0 '+ nTW + ' ' + table_height);
    }
    
    function drawTransitions(){
        if (window.parent!=null){ //Declaration for ASV3 and webkit
            minWave = window.parent.waveMinVal ? parseFloat(window.parent.waveMinVal) : null
            maxWave = window.parent.waveMaxVal ? parseFloat(window.parent.waveMaxVal) : null
            minEnergy = window.parent.energyMinVal ? parseFloat(window.parent.energyMinVal) : null
            maxEnergy = window.parent.energyMaxVal ? parseFloat(window.parent.energyMaxVal) : null
        } else { //Declaration for ASV6
            minWave = parseFloat(waveMinVal);
            maxWave = parseFloat(waveMaxVal);
            minEnergy = parseFloat(energyMinVal);
            maxEnergy = parseFloat(energyMaxVal);
        }
        drawTransitionsWaveEnergyRange(minWave, maxWave, minEnergy, maxEnergy);
    }
    
    
    
    var table_width = $t_width;
var table_height = $t_height;
var term_row_w = $term_row_w;
var term_row_h = $term_row_h;
var core_row_h = $core_row_h;
var conf_row_h = $conf_row_h;
var diagram_w = $diagram_w;
var grouping = "$gr";


    //////svg.js
    
    //ie array.includes() workaround polyfill
// https://tc39.github.io/ecma262/#sec-array.prototype.includes
if (!Array.prototype.includes) {
    Object.defineProperty(Array.prototype, 'includes', {
        value: function(searchElement, fromIndex) {

            if (this == null) {
                throw new TypeError('"this" is null or not defined');
            }

            // 1. Let O be ? ToObject(this value).
            var o = Object(this);

            // 2. Let len be ? ToLength(? Get(O, "length")).
            var len = o.length >>> 0;

            // 3. If len is 0, return false.
            if (len === 0) {
                return false;
            }

            // 4. Let n be ? ToInteger(fromIndex).
            //    (If fromIndex is undefined, this step produces the value 0.)
            var n = fromIndex | 0;

            // 5. If n ? 0, then
            //  a. Let k be n.
            // 6. Else n < 0,
            //  a. Let k be len + n.
            //  b. If k < 0, let k be 0.
            var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

            function sameValueZero(x, y) {
                return x === y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
            }

            // 7. Repeat, while k < len
            while (k < len) {
                // a. Let elementK be the result of ? Get(O, ! ToString(k)).
                // b. If SameValueZero(searchElement, elementK) is true, return true.
                if (sameValueZero(o[k], searchElement)) {
                    return true;
                }
                // c. Increase k by 1.
                k++;
            }

            // 8. Return false
            return false;
        }
    });
}


var tga = 0.3;
var txt_angle=17;
var count_visible=0;
var max_dx = 15; // max dx for not long level
var min_l = 40; // min length for visible line
var min_d = 15;
var max_eq_dy = 7;
var min_levels_count = 5;
var part_after_begin = 0.25;
//var compression_rate = table_width / window.innerWidth;
var compression_rate = table_width / diagram_w;
if (compression_rate > 1 || grouping == 'term' || grouping == 'J' ) compress_table();

var visible_transitions = new Array();
var right_transitions = new Array();
var left_transitions = new Array();
fix_viewBox();
checkTexts();
drawTransitions();


//Sort function for j like "9/2"
function slava_sort_function_j(j1, j2) {
    return eval(j1 != ""? j1 : 0) - eval(j2 != ""? j2 : 0);
}

function compress_table() {
    var g_cores = document.getElementsByTagName('g');
    var dx;
    for (var i = 0; i < g_cores.length; i++) {
        if (g_cores.item(i).getAttribute('class') == 'core') {
            var g_terms = g_cores.item(i).getElementsByTagName('g');
            for (var j = 0; j < g_terms.length; j++) {
                if (g_terms.item(j).getAttribute('class') == 'term') {
                    var compJ = false;

                    var rect_terms = g_terms.item(j).getElementsByTagName('rect');
                    var g_levels = g_terms.item(j).getElementsByTagName('g');
                    var text_terms = g_terms.item(j).getElementsByTagName('text');

                    if (rect_terms.length > 1) {
                        if (compression_rate >= 1.5 || grouping != 'auto'/* &&(rect_terms.length > 2)*/) {
                            var gL = rect_terms.item(0).getAttribute('l');
                            var gSeq = rect_terms.item(0).getAttribute('seq');
                            if (!is_equal_attribute(rect_terms, 'l', gL)) gL = '(...)';
                            else {
                                if (gSeq == '') compJ = true;
                                else {
                                    if (!is_equal_attribute(rect_terms, 'seq', gSeq)) gSeq = '';
                                    else compJ = true;
                                }
                            }
                        }

                        if (grouping != 'full' && (grouping == 'J' || compJ || ((compression_rate < 1.7) /*|| (rect_terms.length < 3)*/))) {
                            // try to compress
                            for (var k = 0; k < rect_terms.length; k++) {
                                if (rect_terms[k].getAttribute('display') != 'none'
                                    /*compJ || parseInt(rect_terms.item(k).getAttribute('n_levels')) < min_levels_count
                                    || rect_terms.item(k).getAttribute('info') == 'no'*/)
                                { // too little count of levels for term => compress
                                    var curL = rect_terms.item(k).getAttribute('l');
                                    var curSeq = rect_terms.item(k).getAttribute('seq');
                                    var curPrefix = rect_terms.item(k).getAttribute('prefix');
                                    var toCompress = new Array();
                                    find_similar(rect_terms, curL, curSeq, curPrefix, toCompress);
                                    if (toCompress.length > 1) {
                                        toCompress.sort(sort_numbers);
                                        //k = toCompress[toCompress.length - 1] + 1;
                                        // compression
                                        var auto = false; //has autoionization state and we should half a term rect
                                        for (var ii = 1; ii < toCompress.length; ii++) {
                                            if (rect_terms.item(toCompress[ii]).getAttribute('type') == 'auto')
                                                auto = true;
                                        }
                                        if (auto) rect_terms.item(toCompress[0]).setAttribute('height', term_row_h / 2);
                                        if (auto) text_terms.item(toCompress[0]).setAttribute('y', 0.25 * term_row_h + core_row_h + conf_row_h);
                                        // hide levels
                                        for (var ii = 1; ii < toCompress.length; ii++) {
                                            dx = rect_terms.item(toCompress[0]).getAttribute('x') - rect_terms.item(toCompress[ii]).getAttribute('x')
                                            shift_glevels(g_levels.item(toCompress[ii]), dx, true);
                                            if (auto) g_levels.item(toCompress[ii]).getElementsByTagName('line')
                                                .item(0).setAttribute('y1', term_row_h /2  + core_row_h + conf_row_h);
                                            rect_terms.item(toCompress[ii]).setAttribute('display', 'none');
                                            rect_terms.item(toCompress[ii]).nextElementSibling.setAttribute('display', 'none');
                                        }
                                        dx = -(toCompress.length - 1)*term_row_w;
                                        // append J
                                        var tJ = rect_terms.item(toCompress[0]).nextElementSibling;

                                        var jArr = new Array();
                                        for (var ii = 0; ii < toCompress.length; ii++) {
                                            jArr.push(rect_terms.item(toCompress[ii]).getAttribute('j'));
                                            jArr.sort(slava_sort_function_j);
                                        }
                                        var newJ;
                                        var jArrFirst = "";
                                        for (var ii = 0; ii < jArr.length; ii++){
                                            if (jArr[ii]!=""){
                                                jArrFirst = jArr[ii];
                                                break;
                                            }
                                        }

                                        if (jArrFirst != jArr[jArr.length - 1])
                                            newJ = (jArrFirst == ""?0:jArrFirst)  + '-' + (jArr[jArr.length - 1] == ""?0:jArr[jArr.length - 1]);
                                        else newJ = jArrFirst == ""?0:jArrFirst;
                                        var tSpans = tJ.getElementsByTagName('tspan');
                                        tSpans.item(tSpans.length-1).textContent = newJ;

                                        // shift levels of current term
                                        //for (var ii = toCompress[toCompress.length - 1] + 1; ii < rect_terms.length; ii++) {
                                        for (var ii = 0; ii < rect_terms.length; ii++){
                                            //count blanks before this term
                                            dx2 = 0;
                                            for (var iii = 1; iii < toCompress.length; iii++)
                                                if (toCompress[iii]< ii) dx2 -= term_row_w;
                                            if (dx2 != 0 && !toCompress.includes(ii)) {//array.includes() doesn't supported in fucking IE
                                                shift_glevels(g_levels.item(ii), dx2);
                                                rect_terms.item(ii).setAttribute('x', parseFloat(rect_terms.item(ii).getAttribute('x')) + dx2);
                                                rect_terms.item(ii).nextElementSibling.setAttribute('x', parseFloat(rect_terms.item(ii).nextElementSibling.getAttribute('x')) + dx2);
                                            }
                                        }
                                        //shift intermediate terms

                                        // shift next terms
                                        var curTerm = g_terms.item(j);
                                        compress_shift_data(curTerm, dx);
                                    }
                                }
                            }
                        }
                        else {
                            if (/*(rect_terms.length > 2) && */grouping != 'full' && grouping != 'J' && (grouping == 'term' || compression_rate >= 1.7)) {// compress term
                                // create new text
                                var newText = text_terms.item(0).cloneNode(true);
                                if (newText.hasAttribute('transform')) newText.removeAttribute('transform');
                                var nTextSpans = newText.getElementsByTagName('tspan');
                                newText.removeChild(nTextSpans.item(nTextSpans.length - 1));

                                if (gSeq == '' &&  newText.firstChild.nodeName == '#text')
                                    newText.removeChild(newText.firstChild);
                                var nL = document.createTextNode(gL);
                                if (nTextSpans.item(1).hasChildNodes())
                                    nTextSpans.item(1).removeChild(nTextSpans.item(1).firstChild);
                                nTextSpans.item(1).appendChild(nL);
                                // create new rectangle
                                var newRect = rect_terms.item(0).cloneNode(false);
                                newRect.setAttribute('type', 'compression');

                                var auto = false; //has autoionization state and we should half a term rect
                                // hide all texts and rectangles and shift levels
                                for (var k = 0; k < rect_terms.length; k++) {
                                    if (rect_terms.item(k).getAttribute('type') == 'auto') auto = true;
                                }
                                for (var k = 0; k < rect_terms.length; k++) {
                                    rect_terms.item(k).setAttribute('display', 'none');
                                    rect_terms.item(k).nextElementSibling.setAttribute('display', 'none');
                                    dx = - term_row_w * k;
                                    shift_glevels(g_levels.item(k), dx, true);
                                    if (auto) g_levels.item(k).getElementsByTagName('line')
                                        .item(0).setAttribute('y1', term_row_h /2  + core_row_h + conf_row_h);

                                }
                                if (auto) newRect.setAttribute('height', term_row_h / 2);
                                if (auto) newText.setAttribute('y', 0.25*term_row_h + core_row_h + conf_row_h);
                                // add new rect and text
                                g_terms.item(j).insertBefore(newText, g_terms.item(j).firstChild);
                                g_terms.item(j).insertBefore(newRect, g_terms.item(j).firstChild);
                                compress_shift_data(g_terms.item(j), dx, auto);
                            }
                        }
                    }
                }
            }
        }
    }
}
function is_equal_attribute(nList, attrName, attrVal) {
    for (var i = 1; i < nList.length; i++)
        if (attrVal != nList.item(i).getAttribute(attrName)) return false;
    return true;
}

function sort_numbers(arg1, arg2) {
    return parseInt(arg1) - parseInt(arg2);
}

function find_similar(data, L, Seq, Prefix, result) {
    for (var i = 0; i < data.length; i++) {
        var curEl = data.item(i);
        if (curEl.getAttribute('l') == L && curEl.getAttribute('seq') == Seq
            && curEl.getAttribute('prefix') == Prefix) {
            result.push(i);
        }
    }
 }
 
function compress_shift_data(curTerm, dx) {
    var Term = curTerm.nextElementSibling;
    while (Term != null) {
        if ('term' == Term.getAttribute('class')) shift_group(Term, dx);
        Term = Term.nextElementSibling;
    }
    //compress current core
    var curCore = curTerm.parentNode;
    compress_group(curCore, dx);
    //shift cores
    var Core = curCore.nextElementSibling;
    while (Core != null) {
        if ('core' == Core.getAttribute('class')) shift_group(Core, dx);
        Core = Core.nextElementSibling;
    }

    // compress config
    var curConf = curCore.parentNode;
    compress_group(curConf, dx);

    // shift next columns
    var Conf = curConf.nextElementSibling;
    while (Conf != null) {
        if (Conf.getAttribute('class') == 'column') shift_group(Conf, dx);
        Conf = Conf.nextElementSibling;
    }

    // compress AllData
    table_width += dx;
    document.getElementById('AllData').setAttribute('width', table_width);

    // shift eV energy scale
    document.getElementById('EeV').setAttribute('transform', 'translate(' + table_width +',0)');
    // shift Name
    document.getElementById('Abbr').setAttribute('x', table_width - 5);
}

function compress_group(gr, dx){
    var chG = gr.childNodes;
    for (var i = 0; i < chG.length; i++) {
        if (chG.item(i).nodeName == 'text')
            chG.item(i).setAttribute('x', parseFloat(chG.item(i).getAttribute('x')) + dx / 2.);
        if (chG.item(i).nodeName == 'rect')
            chG.item(i).setAttribute('width', parseFloat(chG.item(i).getAttribute('width')) + dx);
    }
}

function shift_group(gr, dx){
    var chG = gr.childNodes;
    for (var i = 0; i < chG.length; i++) {
        if (chG.item(i).nodeName == 'rect' || chG.item(i).nodeName == 'text')
            chG.item(i).setAttribute('x', parseFloat(chG.item(i).getAttribute('x')) + dx);
        if (chG.item(i).nodeName == 'g') {
            if (gr.getAttribute('class') == 'column' || gr.getAttribute('class') == 'core')  shift_group(chG.item(i), dx);
            if (gr.getAttribute('class') == 'term') shift_glevels(chG.item(i), dx, false);
        }
    }
}

function shift_glevels(gLevels, dx, hide){
    if (gLevels.nodeName == 'g' && gLevels.getAttribute('class') == 'levels'){
        var lines = gLevels.getElementsByTagName('line');
        for (var j = 0; j < lines.length; j++){
            lines.item(j).setAttribute('x1', parseFloat(lines.item(j).getAttribute('x1')) + dx);
            lines.item(j).setAttribute('x2', parseFloat(lines.item(j).getAttribute('x2')) + dx);
        }
        var txt_l = gLevels.getElementsByTagName('text');
        for (var j = 0; j < txt_l.length; j++){
            txt_l.item(j).setAttribute('x', parseFloat(txt_l.item(j).getAttribute('x')) + dx);
            if (hide) txt_l.item(j).setAttribute('display', 'none');
        }
        var circle_l = gLevels.getElementsByTagName('circle');
        for (var j = 0; j < circle_l.length; j++){
            circle_l.item(j).setAttribute('cx', parseFloat(circle_l.item(j).getAttribute('cx')) + dx);
        }
        return 1;
    }
    return -1;
}


function drawTransitionsWaveEnergyRange(minWave, maxWave, minEnergy, maxEnergy){
    var transitions = document.getElementById('transitions');
    if (transitions == null) return 1;
    transitions = transitions.childNodes;
    var rating, val;

    for (var i=0; i<transitions.length; i++){
        if (transitions.item(i).nodeType == Node.TEXT_NODE) continue;
        if (document.getElementById(transitions.item(i).getAttribute('high_level'))
            && document.getElementById(transitions.item(i).getAttribute('low_level'))
            && transitions.item(i).nodeName == 'line'
            && (maxWave == null || transitions.item(i).getAttribute('wavelength') <= maxWave)
            && (minWave == null || transitions.item(i).getAttribute('wavelength') >= minWave)
            && (maxEnergy == null || document.getElementById(transitions.item(i).getAttribute('high_level')).getAttribute('energy') <= maxEnergy)
            && (minEnergy == null || document.getElementById(transitions.item(i).getAttribute('high_level')).getAttribute('energy') >= minEnergy)
            && (maxEnergy == null || document.getElementById(transitions.item(i).getAttribute('low_level')).getAttribute('energy') <= maxEnergy)
            && (minEnergy == null || document.getElementById(transitions.item(i).getAttribute('low_level')).getAttribute('energy') >= minEnergy)
            ){
            var curLine = transitions.item(i);
            var hlevel = document.getElementById(curLine.getAttribute('high_level'));
            var llevel = document.getElementById(curLine.getAttribute('low_level'));
            var longh = hlevel.hasAttribute('long');
            var longl = llevel.hasAttribute('long');
            var xl = get_real_line_x(llevel);
            var xh = get_real_line_x(hlevel);
            var y1 = parseFloat(hlevel.getAttribute('y1'));
            var y2 = parseFloat(llevel.getAttribute('y1'));
            curLine.setAttribute('y1', y1);
            curLine.setAttribute('y2', y2);

            if (!longh && !longl){
                //var len = Math.sqrt((y2-y1)*(y2-y1) + (xh-xl)*(xh-xl));
                //	if (len > min_l)
                //	{
                //      	curLine.setAttribute('x1', xh);
                //              curLine.setAttribute('x2', xl);
                //		visible_transitions.push(curLine);count_visible++;
                //	}
                set_invisible(curLine);
            }
            else{
                var len = Math.sqrt((1+tga*tga)*(y2-y1)*(y2-y1));
                if (len > min_l){
                    if (longh && !longl){ // low_level is not long
                        val = xl;
                        curLine.setAttribute('x2', xl);
                        if (xl < xh){// to reduce distance between levels!!!!
                            val += (y2-y1)*tga;
                            right_transitions.push(curLine);
                        }
                        else {
                            val -= (y2-y1)*tga;
                            left_transitions.push(curLine);
                        }
                        curLine.setAttribute('x1', val);
                        visible_transitions.push(curLine);
                        count_visible++;
                    }
                    else{// only high_level or both are long
                        val = xh;
                        curLine.setAttribute('x1', val);
                        if (xh > xl){
                            val -= (y2-y1)*tga;
                            right_transitions.push(curLine);
                        }
                        else {
                            val += (y2-y1)*tga;
                            left_transitions.push(curLine);
                        }
                        curLine.setAttribute('x2', val);
                        visible_transitions.push(curLine);
                        count_visible++;
                    }
                }
                else set_invisible(curLine);
            }
            if (count_visible == 30){
                rating = curLine.getAttribute('rating');
                if (rating > 3) rating = 3;
            }
            if (count_visible > 30)
                if (curLine.getAttribute('rating') < rating) break;
        }
    }

    distr_transitions();
    fix_levels();
    show_texts();
}

function get_line_CTM(line){
    var ctM = line.parentNode.parentNode.parentNode.parentNode.getCTM();
    var CTMScale = 1/ctM.a;
    if (CTMScale != 1){ //not IE
        ctM.a = ctM.a * CTMScale;
        ctM.b = ctM.b * CTMScale;
        ctM.c = ctM.c * CTMScale;
        ctM.d = ctM.d * CTMScale;
        ctM.e = (ctM.e - line.parentNode.parentNode.parentNode.parentNode.parentNode.getCTM().e) * CTMScale;
        ctM.f = ctM.f * CTMScale;
    }
    return(ctM);
}

function fix_levels(){
    for (var i=0; i<visible_transitions.length; i++){
        var cTr = visible_transitions[i];

        var x2 = parseFloat(cTr.getAttribute('x2'));
        var ll = document.getElementById(cTr.getAttribute('low_level'));
        extend_level(ll, get_line_CTM(ll), x2);

        var x1 = parseFloat(cTr.getAttribute('x1'));
        var hl = document.getElementById(cTr.getAttribute('high_level'));
        extend_level(hl, get_line_CTM(hl), x1);
    }
}

function show_transition_text(transition, a, rH){
    var x1 = parseFloat(transition.getAttribute('x1'));
    var x2 = parseFloat(transition.getAttribute('x2'));
    var y1 = parseFloat(transition.getAttribute('y1'));
    var y2 = parseFloat(transition.getAttribute('y2'));
    var gx = x2 - (x2 - x1)*part_after_begin;
    var gy = y2 - (y2 - y1)*part_after_begin;

    var txtR = document.getElementById('rect_' + transition.getAttribute('id'));
    txtR.setAttribute('display', '');
    txtR.setAttribute('transform', 'rotate(' + a + ' ' + gx + ' ' + gy +')');
    txtR.setAttribute('x', gx);
    txtR.setAttribute('y', gy - 1);

    var txtE = document.getElementById('txt_' + transition.getAttribute('id'));
    txtE.setAttribute('transform', 'rotate(' + a + ' ' + gx + ' ' + gy +')');
    txtE.setAttribute('display', '');
    txtE.setAttribute('x', gx);
    txtE.setAttribute('y', gy + rH * 0);
    txtR.setAttribute('width', txtE.getComputedTextLength());
}

function show_texts(){
    var rH = 4;//?

    for (var i=0; i<left_transitions.length; i++)
        show_transition_text(left_transitions[i], -(90 + txt_angle), rH);

    for (var i=0; i<right_transitions.length; i++)
        show_transition_text(right_transitions[i], -(90 - txt_angle), rH);
}

function distr_ordered_transitions(transitions, side){
    for (var i = 0; i < transitions.length - 1 && transitions.length > 1; i++) {
        dn = 1;
        while (dn < transitions.length - i){
            curTr = transitions[i+dn];
            if (!equal(transitions[i], transitions[i+dn])) {
                d = Math.abs(distance(transitions[i], transitions[i+dn]));
                if (d < min_d) {	// try to shift i+dn transition
                    hl = document.getElementById(curTr.getAttribute('high_level'));
                    ll = document.getElementById(curTr.getAttribute('low_level'));
                    curDx = parseFloat(curTr.getAttribute('dx'));
                    hl_long = hl.hasAttribute('long');
                    ll_long = ll.hasAttribute('long');
                    if ((!hl_long) || (!ll_long)){
                        // high level is not long
                        dx = min_d - d + curDx;
                        if (dx <= max_dx){
                            new_x1 = parseFloat(curTr.getAttribute('x1'))
                            if (side == "left") new_x1 += dx;
                            else new_x1 -= dx;
                            curTr.setAttribute('x1', new_x1);

                            new_x2 = parseFloat(curTr.getAttribute('x2'));
                            if (side == "left") new_x2 += dx;
                            else new_x2 -= dx;
                            curTr.setAttribute('x2', new_x2);

                            curTr.setAttribute('dx', dx);
                            dn++;
                        }
                        else{ // hide transition
                            set_invisible(curTr);
                            transitions.splice(i+dn, 1);
                        }
                    }
                    else{ // both levels are long
                        dx = parseFloat(curTr.getAttribute('dx'));
                        if (dx + max_dx > (table_width*0.3)){
                            new_x1 = parseFloat(curTr.getAttribute('x1'));
                            if (side == "left") new_x1 += max_dx;
                            else new_x1 -= max_dx;
                            curTr.setAttribute('x1', new_x1);

                            curTr.setAttribute('dx', dx + max_dx);
                            new_x2 = parseFloat(curTr.getAttribute('x2'));
                            if (side == "left") new_x2 += max_dx;
                            else new_x2 -= max_dx;
                            curTr.setAttribute('x2', new_x2);
                            dn++;
                        }
                        else{
                            set_invisible(curTr);
                            transitions.splice(i+dn, 1);
                        }
                    }
                }
                else dn++; // d > min
            }
            else{ // if equal
                set_invisible(curTr);
                transitions.splice(i+dn, 1);
            }
        }
    }

}

function distr_transitions(){
    right_transitions.sort(sort_function_decrease_x1);
    left_transitions.sort(sort_function_increase_x1);
    distr_ordered_transitions(left_transitions, "left");     // left_transitions
    distr_ordered_transitions(right_transitions, "right");   // right_transitions
}

function sort_function_increase_x1(line1, line2){
    return sort_function(line1, line2, 1);
}

function sort_function_decrease_x1(line1, line2){
    return sort_function(line1, line2, -1);
}

function sort_function(line1, line2, dir){
    var x11 = parseFloat(line1.getAttribute('x1'));
    var x12 = parseFloat(line2.getAttribute('x1'));

    if (x11 != x12) return dir * (x11 - x12);
    else {
        var y11 = parseFloat(line1.getAttribute('y1'));
        var y12 = parseFloat(line2.getAttribute('y1'));
        if (y11 != y12) return y12 - y11;

        var x21 = parseFloat(line1.getAttribute('x2'));
        var x22 = parseFloat(line2.getAttribute('x2'));
        if (x21 != x22) return dir * (x22 - x21);
        else {
            //if (dir == -1) return 0;
            var wl1 = parseFloat(line1.getAttribute('wavelength'));
            var wl2 = parseFloat(line2.getAttribute('wavelength'));
            return wl1 - wl2;
        }
    }
}

function distance(line1, line2){
    return diff(line2, line1, 'x1') + tga * diff(line1, line2, 'y1');
}

function diff(line1, line2, param){
    var v1 = parseFloat(line1.getAttribute(param));
    var v2 = parseFloat(line2.getAttribute(param));
    if (isNaN(v1)) v1 = 0;
    if (isNaN(v2)) v2 = 0;
    return v1 - v2;
}

function equal(line1, line2){
    var dx1 = diff(line1, line2, 'x1') - diff(line1, line2, 'dx');
    var dx2 = diff(line1, line2, 'x2') - diff(line1, line2, 'dx');
    if (dx1 == 0 || dx2 == 0){
        var dy1 = Math.abs(diff(line1, line2, 'y1'));
        var dy2 = Math.abs(diff(line1, line2, 'y2'));
        if (dy1 <= max_eq_dy && dy2 <= max_eq_dy) return true;

        var wl1 = parseInt(line1.getAttribute('wavelength'));
        var wl2 = parseInt(line2.getAttribute('wavelength'));
        if (wl1 == wl2) return true;
        else return false;
    }
    return false;
}

function get_real_line_x(level){
    var trM = get_line_CTM(level);
    var mx = (parseFloat(level.getAttribute('x1')) + parseFloat(level.getAttribute('x2')))/ 2. ;
    return trM.a * mx + trM.e;
}

function set_invisible(line){
    line.setAttribute('display', 'none');
    // delete from visible_transitions
    for (var i = 0; i < visible_transitions.length; i++){
        if (visible_transitions[i].getAttribute('id') == line.getAttribute('id')){
            visible_transitions.splice(i, 1);
            break;
        }
    }
}

function extend_level(level, trMatr, new_x){
    var lx1 = level.getAttribute('x1');
    var lx2 = level.getAttribute('x2');
    var trM = trMatr.inverse();
    var nx = trM.a*new_x + trM.e;
    if (nx > Math.max(lx1, lx2)){
        level.setAttribute('x2', nx);
        level.setAttribute('x1', Math.min(lx1, lx2));
    }
    if (nx < Math.min(lx1, lx2)){
        level.setAttribute('x1', nx);
        level.setAttribute('x2', Math.max(lx1, lx2));
    }
}

function checkTexts(){
    var texts = document.getElementsByTagName('text');
    for (var i = 0; i < texts.length; i++){
        var svgTextNode =  texts.item(i);
        if (svgTextNode.getAttribute('display') != 'none' &&
            svgTextNode.getAttribute('class') == 'config'){
            var curChilds = (svgTextNode.parentNode).childNodes;
            for (var j = 0; j < curChilds.length; j++){
                if (curChilds.item(j).nodeName == 'rect' && curChilds.item(j).hasAttribute('id')
                    && svgTextNode.getAttribute('rec_id') == curChilds.item(j).getAttribute('id')
                    /*&& svgTextNode.getComputedTextLength() > curChilds.item(j).getAttribute('width')*/){//let it rotate all labels
                    // compare Rectangle width and text ComputedTextLength
                    rotateText(svgTextNode);
                    break;
                }
            }
        }
    }
}

function rotateText(elem){
    var curTransform = elem.hasAttribute('transform')? curTransform = elem.getAttribute('transform'): '';
    var resultTransform = 'rotate(-90 ' + elem.getAttribute('x') + ' ' + elem.getAttribute('y') +') ' ;
    resultTransform += curTransform;
    elem.setAttribute('transform', resultTransform);
}

////// interactive mouse functions
function getTarget(e){
    if ('getTarget' in e) return e.getTarget();
    else if (e.srcElement) return e.srcElement;
    else return e.relatedTarget;
}

////// interactive mouse functions
function getTarget(e){
    if ('getTarget' in e) return e.getTarget();
    else if (e.srcElement) return e.srcElement;
    else return e.relatedTarget;
}

});

function show_level_info(level){
    document.getElementById('txt_lbl_'+level.getAttribute('id')).setAttribute('display', '');
    document.getElementById('lbl_'+level.getAttribute('id')).setAttribute('display', '');
    document.getElementById('conf_name_'+level.getAttribute('id')).setAttribute('display', '');
}

function hide_level_info(level) {
    document.getElementById('txt_lbl_'+level.getAttribute('id')).setAttribute('display', 'none');
    document.getElementById('lbl_'+level.getAttribute('id')).setAttribute('display', 'none');
    document.getElementById('conf_name_'+level.getAttribute('id')).setAttribute('display', 'none');
}

function mouse_on_tr(evt, id){
//    if (target.nodeName != 'line') target = getTarget(evt);
    target = document.getElementById(id);
    show_level_info(document.getElementById(target.getAttribute('low_level')));
    show_level_info(document.getElementById(target.getAttribute('high_level')));
}

function click_on_tr(evt, id){
//    if (target.nodeName != 'line') target = getTarget(evt);
    target = document.getElementById(id);
    if (target.getAttribute('display') == 'none') target.setAttribute('display', '');
    else target.setAttribute('display', 'none');
}
function click_on_tr_text(evt, id){
//    if (target.nodeName != 'line') target = getTarget(evt);
    txt = document.getElementById('txt_' + id);
    rect = document.getElementById('rect_' + id);
    if (txt.getAttribute('display') == 'none') {
        txt.setAttribute('display', '');
        rect.setAttribute('display', '');
    }
    else {
        txt.setAttribute('display', 'none');
        rect.setAttribute('display', 'none');
    }
}

function mouse_move_tr(evt){
}

function mouse_out_tr(evt, id){
    //if (target.nodeName!='line') target = getTarget(evt);
    target = document.getElementById(id);
    hide_level_info(document.getElementById(target.getAttribute('low_level')));
    hide_level_info(document.getElementById(target.getAttribute('high_level')));
}

function mouse_on_level(evt, target){
    if (target.nodeName!='line') target = getTarget(evt);
    show_level_info(target);
}

function mouse_out_level(evt, target){
    if (target.nodeName!='line') target = getTarget(evt);
    hide_level_info(target);
}

JS;

$this->registerJs( $js, $position = yii\web\View::POS_BEGIN, $key = null );
?>
