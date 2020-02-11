<?php

/* @var $this yii\web\View */

/* @var $atom \common\models\Atom */
/* @var $ion string */
/* @var $transitions_list \common\models\Transition */
/* @var $atom_name \common\models\Atom->periodicTable->ABBR */

$this->title = Yii::t('app', 'Spectrogram - {Z}', ['Z' => $atom_name]);
?>

<div class="container_12">
    <div class="grid_12" id="main">
        <div class="brake"></div>
        <div>
            <div id='toolbar'>
                <div id='range'>
                    <div id='min_container'>
                        <b><?= Yii::t('spectr', 'Minimal')?> (&#8491;)</b><br>
                        <input type='text' id='min' value='0'>
                    </div>
                    <div id='max_container'>
                        <b><?= Yii::t('spectr', 'Maximum')?> (&#8491;)</b><br>
                        <input type='text' id='max' value='30000'>
                    </div>
                    <div class='top_div'>
                        <input type='button' id='filter' value='OK' class="bluebtn">
                    </div>
                    <div id='visible_container' style="clear:both; margin-top: 10px;">
                        <input type='button' id='visible' value='<?= Yii::t('spectr', 'Visible spectrum')?>' class='bluebtn'><span style="width: 20px"></span>
                        <input type='button' id='all_spectrum' value='<?= Yii::t('spectr', 'All spectrum')?>' class='bluebtn'>
                    </div>

                </div>
                <div id='zoom_container'>
                    <b><?= Yii::t('spectr', 'Scale')?></b><br>
                    <input type='button' id='x1' value='1' class='bluebtn base active'>
                    <input type='button' value='10' class='bluebtn base'>
                    <input type='button' value='100' class='bluebtn base'>
                    <br><br>
                    <input type='button' id='x2' value='x2' class="bluebtn">
                    <input type='button' value='x5' class="bluebtn">
                </div>
                <div>
                    <input type='button' id='barchart' value='<?= Yii::t('spectr', 'Bar Chart')?>' class="bluebtn"><br><br>
                    <div id="series"></div>
                </div>
            </div>
        </div>
        <div style="margin: auto; margin-top: 10px; width: 520px;">
            <div id="info_intensity"><b><?= Yii::t('spectr', 'Sensibility')?></b></div>
            <div id="range_intensity"><input type="range" min=10 max=400 value=160 style="width: 380px;"></div>
        </div>
        <div id='line_info'>
        </div>
        <div id="svg_wrapper" class="svg_wrapper">
        </div>
        <div id='map'>
            <div id='preview'></div>
            <div id='map_now'></div>
        </div>
    </div>
</div>

<?php
$transitions_list = json_encode($transitions_list);
$w_l = Yii::t('spectr', 'Wavelength');
$lvl = Yii::t('spectr', 'Levels');
$intens = Yii::t('spectr', 'Intensity');
$allLines = Yii::t('spectr', 'All lines');
$lymanSeries = Yii::t('spectr', 'Lyman series');
$balmerSeries = Yii::t('spectr', 'Balmer series');
$paschenSeries = Yii::t('spectr', 'Paschen series');
$brackettSeries = Yii::t('spectr', 'Brackett series');
$pfundSeries = Yii::t('spectr', 'Pfund series');
$humphreysSeries = Yii::t('spectr', 'Humphreys series');
$principalSeries = Yii::t('spectr', 'Principal series');
$sharpSeries = Yii::t('spectr', 'Sharp series');
$diffuseSeries = Yii::t('spectr', 'Diffuse series');
$fundamentalSeries = Yii::t('spectr', 'Fundamental series');

$js = <<< JS
var lvl = "$lvl",
    w_l = "$w_l",
    intens = "$intens",
    atom_name = "$atom_name",
    allLines = "$allLines",
    lymanSeries = "$lymanSeries",
    balmerSeries = "$balmerSeries",
    paschenSeries = "$paschenSeries",
    brackettSeries = "$brackettSeries",
    pfundSeries = "$pfundSeries",
    humphreysSeries = "$humphreysSeries",
    principalSeries = "$principalSeries",
    sharpSeries = "$sharpSeries",
    diffuseSeries = "$diffuseSeries",
    fundamentalSeries = "$fundamentalSeries",
    ion = "$ion";
var transitions_list = $transitions_list;
var max_logbase = 20,
    min_logbase = 1,
    intensity_slider_scale = 20,
    default_logbase = 8,
    max_intensity = 0,
    lines_data;

function init() {
    let n='';
    var zoom = get_zoom(),
        barchart = $('#barchart').hasClass('active'),
        max = Number($('#max').val()),
        min = Number($('#min').val()),
        isDrag = 0,
        start = 0,
        l,
        str = "<svg class='svg' id='svg" + n + "' draggable='true' style='background-color:black;' width='"+ (max-min)*zoom/10 + "' height='120'>",
        map_str = "<svg id='map_svg" + n + "'>",
        map_now = (max - min) / 10000;

    $("#range_intensity input").attr("min", min_logbase * intensity_slider_scale);
    $("#range_intensity input").attr("max", max_logbase * intensity_slider_scale);
    $("#range_intensity input").attr("value", default_logbase * intensity_slider_scale);

    let id = 1;

    let intensity_slider_value = $("#range_intensity input").val();

    if (n == '') {
        max_intensity = 0;
        transitions_list.forEach(function (transition) {
            l = Number(transition.WAVELENGTH);
            let i = Number(transition.INTENSITY);
            if (l > min && l < max) {
                if (i > max_intensity)
                    max_intensity = i;
            }
        });
        
        lines_data = new Array();
        
        transitions_list.forEach(function (transition) {
            l = Number(transition.WAVELENGTH);
            let i = Number(transition.INTENSITY);
           
            if (l > min && l < max) {
                let y1 = 0;
                let newcolor = "rgb(" + transition.COLOR.R + "," + transition.COLOR.G + "," + transition.COLOR.B + ")";
                if (barchart) {
                    if (max_intensity > 0) y1 = definelength(i/ max_intensity, intensity_slider_value / intensity_slider_scale);
                }
                else
                {
                    if (max_intensity > 0) newcolor = fadecolor(newcolor, i/ max_intensity, intensity_slider_value / intensity_slider_scale);
                }

                lines_data[id] = new Object();
                lines_data[id]['l'] = l;
                lines_data[id]['i'] = i;
                lines_data[id]['lower-level-term'] = transition.LOWER_TERM;
                lines_data[id]['upper-level-term'] = transition.UPPER_TERM;
                lines_data[id]['upper-level-term-original'] = transition.upperLevel.CONFIG;
                lines_data[id]['lower-level-term-original'] = transition.lowerLevel.CONFIG;
                lines_data[id]['color'] = "rgb(" + transition.COLOR.R + "," + transition.COLOR.G + "," + transition.COLOR.B + ")";
                
                var x = Math.round(((l - min)/ 10 * zoom)*100)/100;
                var map_x = Math.round(((l - min) / 10 / map_now)*100)/100;
                
                str += "<line id='" + id + "' x1='" + x + "' y1='" + y1 + "' x2='" + x +
                    "' y2='120' stroke-width='1' stroke='" + newcolor + "'></line>";
                map_str +="<line id='full-" + id + "' x1='" + map_x + "' y1='" + y1 + "' x2='" + map_x +
                    "' y2='120' stroke-width='1' stroke='" + newcolor + "'></line>";

                id++;
            }
        });
    }

    if (!n) {
        $('#svg_wrapper').empty();
        $('#preview').empty();
    }else {
        $('#svg_wrapper').css('height', '325px');
        $('#preview').css('height', '300px');
        $('#map_now').css('height', '240px');
    }

    $('#svg_wrapper').prepend(str + "</svg>");

    init_ruler(zoom, min, max, n);

    $('#preview').prepend(map_str + "</svg>");

    $('#map_now').css('width', map_width() + 'px');
    
    $('#svg_wrapper .svg line').hover(
        function() {
            let id = $(this).attr('id');
            $('#line_info').empty();
            $('#line_info').append(w_l + ': <b>' + lines_data[id]['l'] + ' &#8491;</b> ' + lvl +': '
                + lines_data[id]['lower-level-term']
                +' - ' +   lines_data[id]['upper-level-term']
                +'. ' + intens + ': ' + lines_data[id]['i']
            );
            $(this).attr('stroke-width', 2);
        },
        function() {
            $(this).attr('stroke-width', 1);
            $('#line_info').empty();
        }
    );
    
    $('#svg_wrapper').scroll(function() {
        $('#map_now').css('left', Math.round(this.scrollLeft / map_now / zoom * 100)/100 + 'px');
    });

    $('#svg').css('width', (max-min) * zoom / 10 + 'px');
    if (document.getElementById('canvas')) document.getElementById('canvas').width = (max-min) * zoom / 10; //2 = border-left (1px) + border-right (1px)

    $('#svg').mousemove(function(event) {
        if (isDrag) {
            if (n) $('#svg').css('marginLeft', -(start - event.pageX) + 'px');
            else $('#svg_wrapper')[0].scrollLeft = start - event.pageX;
        }
    });

    $('#svg').mousedown(function(event) {
        event.preventDefault();
        $(this).css('cursor', 'move');
        isDrag = 1;
        start = n ? event.pageX - parseInt($('#svg').css('marginLeft')) : event.pageX + $('#svg_wrapper')[0].scrollLeft;
    });

    $('#svg').mouseup(function() {
        $(this).css('cursor', 'default');
        isDrag = 0;
    });
    
    init_serie_selector();
}

function init_serie_selector()
{
    if (atom_name == "H" || atom_name == "D" || atom_name == "T") {
        let series = [];
        for (let i = 1; i < lines_data.length; i++) {
            let llc = lines_data[i]['lower-level-term'];
            if (series.indexOf(llc[0]) == -1) series.push(llc[0]);
        }
        series.sort();
        $('#series').empty();
        $('<select>', {'id': 'serieSelector', 'change': function(event){selectserie(atom_name, event.target.value)}}).appendTo($('#series'));
        $('<option>', {'value': 'all', 'text': allLines}).appendTo($('#serieSelector'));
        for (let j = 0; j < series.length; j++) {
            let text = series[j];
            switch (series[j]){
                case "1": text = lymanSeries; break;
                case "2": text = balmerSeries; break;
                case "3": text = paschenSeries; break;
                case "4": text = brackettSeries; break;
                case "5": text = pfundSeries; break;
                case "6": text = humphreysSeries; break;
                default: text = "n' = " + series[j];
            }
            $('<option>', {'value': series[j], 'text': text + " (n' = " + series[j] +")"}).appendTo($('#serieSelector'));
        }
    }
    let element = atom_name + " " + ion;
    if (element == "Na I" || element == "Li I" || element == "K I" || element == "Rb I" || element == "Cs I" || element == "Fr I"/* || e_count == "1"*/)
    {
        $('#series').empty();
        $('<select>', {'id': 'serieSelector', 'change': function(event){selectserie(element, event.target.value)}}).appendTo($('#series'));
        $('<option>', {'value': 'all', 'text': allLines}).appendTo($('#serieSelector'));
        $('<option>', {'value': 'sp', 'text': principalSeries  + " (ns - n'p)"}).appendTo($('#serieSelector'));
        $('<option>', {'value': 'ps', 'text': sharpSeries + " (np - n's)"}).appendTo($('#serieSelector'));
        $('<option>', {'value': 'pd', 'text': diffuseSeries + " (np - n'd)"}).appendTo($('#serieSelector'));
        $('<option>', {'value': 'df', 'text': fundamentalSeries + " (nd - n'f)"}).appendTo($('#serieSelector'));
    }
}
function selectserie(element, serie)
{
    for (let k = 1; k < lines_data.length; k++) {//сброс длины палочек или затемнения при смене типа отображения
        document.getElementById(k).style.display = "";
        document.getElementById("full-" + k).style.display="";
    }
    //rule_intensity();

    if ((element == "H" || element == "D" || element == "T")&& serie!='all')
    {
        for (let k = 1; k < lines_data.length; k++) {
            let llc = lines_data[k]['lower-level-term'];
            if (llc[0] != serie) {
                document.getElementById(k).style.display = "none";
                document.getElementById("full-" + k).style.display = "none";
            }
        }
    }
    if ((element == "Na I" || element == "Li I" || element == "K I" || element == "Rb I" || element == "Cs I" || element == "Fr I"
            /* || e_count == "1"*/) && serie!='all') {

        for (let k = 1; k < lines_data.length; k++) {
            let llc = lines_data[k]['lower-level-term-original'];
            let ulc = lines_data[k]['upper-level-term-original'];
            //var ll = llc[llc.search( /[a-z][^a-z@]*$/ )];
            let ul = ulc[ulc.search( /[a-z][^a-z@]*$/ )];
            let g_base_level = lines_data[1]['lower-level-term-original'];
            if (llc != g_base_level.substr(0, g_base_level.length -1) + serie[0]
                || ulc.substr(0, ulc.search( /\d+[a-z][^a-z@]*$/ )) + "n" + ulc.substr(ulc.search( /[a-z][^a-z@]*$/ ), 1) != g_base_level.substr(0, g_base_level.length -2) + "n" + serie[1]) {
                document.getElementById(k).style.display = "none";
                document.getElementById("full-" + k).style.display = "none";
            }
        }
    }
}

function map_width(){
    let max = Number($('#max').val());
    let min = Number($('#min').val());
    let map_now = (max - min) / 10000;
    return Math.min(1000/ map_now / get_zoom(), 1000);
}

function init_ruler(zoom, min, max, n) {
    var max = max * zoom / 10,
        min = min * zoom / 10,
        ruler = "<svg width='" + (max-min) + "' height='30' id='ruler' style='background-color:white;'>";

    let rulerMin = Math.ceil(min/100)*100; // round minimum to hundreds in less side
    for (var j = 0; j < max - min; j+= 100) {
        var i = j + rulerMin-min; // i - pixels on ruler
        var rulerValue =  (i+min) * 10 / zoom;
        var line_x = !i ? i + 2 : (i == max ? i - 2 : i);
        ruler += "<line x1='" + line_x + "' y1='0' x2='" + line_x + "' y2='30' stroke-width='2' stroke='rgb(0, 0, 0)'></line>";
        if (j <= max - min - 100) {
            var text_x = i == max ? i - 45 : i + 5 - 2;
            ruler += "<text x='" + text_x + "' y='26' fill='black'>" + rulerValue + "</text>";
        }
    }

    $('#svg_wrapper' + n).append(ruler);
}

function fadecolor(color, normal_intensity, logbase)
{
    if (logbase == max_logbase) return color;
    let alfa = Math.round(Math.log(1+ (Math.pow(2, logbase)-1) * normal_intensity)/Math.log(2)/logbase*1000)/1000;
    return "rgba" + color.substr(3, color.length-3-1) + "," + alfa + ")";
}

function init_all(){
    init();
    //init_serie_selector();
}

function definelength(normal_intensity, logbase)
{
    let maxlength = 120;
    if (logbase == max_logbase) return 0;
    return maxlength - Math.log(1+ (Math.pow(2, logbase) - 1) * normal_intensity)/Math.log(2)/logbase * maxlength;
}

function get_zoom() {
    let zoom = 0;
    $('#zoom_container input').each(function() {
        let _this = $(this);
        if (_this.hasClass('active')) {
            if (_this.hasClass('base'))
                zoom += Number(_this.val());
            else
                zoom *= Number(_this.val().replace('x', ''));
        }

    });
    return zoom ? zoom : 1;
}

$(document).on('click', '#barchart', function() {
    $(this).toggleClass('active');
    var barchart = $('#barchart').hasClass('active');
    for (var k = 1; k < lines_data.length; k++) {//сброс длины палочек или затемнения при смене типа отображения
        if (barchart) {
            if (max_intensity == 0){
                document.getElementById(k).attributes["stroke"].value = lines_data[k].color;
                document.getElementById("full-" + k).attributes["stroke"].value = lines_data[k].color;
            }
            else {
                var color = fadecolor(lines_data[k]['color'], lines_data[k]['i'] / max_intensity, max_logbase);
                document.getElementById(k).attributes["stroke"].value = color;
                document.getElementById("full-" + k).attributes["stroke"].value = color;
            }
        }
        else {
            if (max_intensity == 0){
                document.getElementById(k).attributes["y1"].value = 0;
                document.getElementById("full-" + k).attributes["y1"].value = 0;
            }
            else {
                var y1 = definelength(lines_data[k]['i'] / max_intensity, max_logbase);
                document.getElementById(k).attributes["y1"].value = y1;
                document.getElementById("full-" + k).attributes["y1"].value = y1;
            }
        }
    }
    rule_intensity();
});

function rule_intensity(){
    if (max_intensity == 0) return;
    let barchart = $('#barchart').hasClass('active');
    let intensity_slider_value = $("#range_intensity input").val();
    for (var k = 1; k < lines_data.length; k++) {
        if (barchart) {
            if (max_intensity == 0){
                document.getElementById(k).attributes["y1"].value = 0;
                document.getElementById("full-" + k).attributes["y1"].value = 0;
            }
            else {
                var y1 = definelength(lines_data[k]['i'] / max_intensity, intensity_slider_value / intensity_slider_scale);
                document.getElementById(k).attributes["y1"].value = y1;
                document.getElementById("full-" + k).attributes["y1"].value = y1;
            }
        }
        else {
            if (max_intensity == 0){
                document.getElementById(k).attributes["stroke"].value = lines_data[k].color;
                document.getElementById("full-" + k).attributes["stroke"].value = lines_data[k].color;
            }
            else {
                var color = fadecolor(lines_data[k]['color'], lines_data[k]['i'] / max_intensity, intensity_slider_value / intensity_slider_scale);
                document.getElementById(k).attributes["stroke"].value = color;
                document.getElementById("full-" + k).attributes["stroke"].value = color;
            }
        }
    }
}

$(document).on('click', '#zoom_container input', function() {
    var middle = ($('#svg_wrapper').prop('scrollLeft') + $('#svg_wrapper').prop('clientWidth')/2)/$('#svg_wrapper').prop('scrollWidth');
    if ($(this).hasClass('base'))
        $('#zoom_container input.base').removeClass('active');
    $(this).toggleClass('active');
    init_all();
    $('#svg_wrapper').prop('scrollLeft', middle * $('#svg_wrapper').prop('scrollWidth') - $('#svg_wrapper').prop('clientWidth')/2);

});

$(document).on('click', '#visible', function() {
    $('#min').prop('value', 3800);
    $('#max').prop('value', 7800);
    $('#zoom_container input').removeClass('active');
    $('#x1').addClass('active');
    $('#x2').addClass('active');
    $('#x5').removeClass('active');
    init_all();
});

$(document).on('click', '#all_spectrum', function() {
    $('#min').prop('value', 0);
    $('#max').prop('value', 30000);
    $('#zoom_container input').removeClass('active');
    $('#x1').addClass('active');
    $('#x2').removeClass('active');
    $('#x2').removeClass('active');
    init_all();
});

$(document).on("change mousemove", '#range_intensity',function(){
    rule_intensity();
});

$(document).on('click', '#filter', function() {
    init_all();
});

init();

JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>
