<?php
/**
 * Created by PhpStorm.
 * User: atikh
 * Date: 11.03.2020
 * Time: 15:08
 */

namespace common\models;
use DOMDocument;

class Svg
{
    var $n_breaks, $breaks, $diagram_h, $graph_y, $min_limit, $sum_breaks, $n_limits, $max_limit, $term_row_h,
        $n_labels, $index_dx, $index_dy;

    static function setVars($n_br, $br, $d_h, $g_y, $min_lim, $sum_br, $n_lim, $max_lim, $t_row_h, $n_lab, $i_dx, $i_dy){
        global $n_breaks, $breaks, $diagram_h, $graph_y, $min_limit, $sum_breaks, $n_limits, $max_limit, $term_row_h, $n_labels, $index_dx, $index_dy;
        $n_breaks = $n_br;
        $breaks = $br;
        $diagram_h = $d_h;
        $graph_y = $g_y;
        $min_limit = $min_lim;
        $sum_breaks = $sum_br;
        $n_limits = $n_lim;
        $max_limit = $max_lim;
        $term_row_h = $t_row_h;
        $n_labels = $n_lab;
        $index_dx = $i_dx;
        $index_dy = $i_dy;
    }

    /*FORMATING FUNCTIONS */
    /**
     * @param $val
     * @param $n
     * @return mixed
     */
    static function extend_energy($val, $n){
        global $n_breaks, $breaks;
        if ($n < $n_breaks) {
            if ($val < $breaks[$n]['l1']['value']) return Svg::extend_energy($val, $n + 1);
            else return Svg::extend_energy($val + ($breaks[$n]['l2']['value'] - $breaks[$n]['l1']['value']), $n + 1);
        }
        else return $val;
    }

    /**
     * @param $energy
     * @return float
     */
    static function scale_with_breaks($energy){
        global $breaks, $diagram_h, $graph_y, $min_limit, $sum_breaks;
        $val = $energy;
        foreach($breaks as $break){
            if ($energy > $break['l2']['value']) $val -= $break['l2']['value'] - $break['l1']['value'];
            elseif ($energy > $break['l1']['value']) $val -= $energy - $break['l1']['value'];
        }
        return round($diagram_h - (($val*$graph_y) / ($min_limit - $sum_breaks)));
    }

    /*convert energy to coordinates*/
    /**
     * @param $val
     * @return float
     */
    static function convert_energy($val){ //сложная логика условий. Переделать
        global $min_limit, $n_breaks, $diagram_h, $graph_y, $n_limits, $max_limit, $term_row_h;
        if ($val < $min_limit){
            if ($n_breaks >= 1) return Svg::scale_with_breaks($val);
            if ($n_breaks == 0) return round($diagram_h - (($val * $graph_y) / $min_limit), 2);
        }
        elseif ($n_limits == 1) return round($diagram_h - $graph_y, 2);
        elseif ($val > $max_limit) return round($diagram_h - $graph_y - $term_row_h*0.5, 2);
        elseif ($n_limits > 1) return round($diagram_h - $graph_y - ($term_row_h*0.5*($val - $min_limit) / ($max_limit - $min_limit)), 2);
        else return round($diagram_h - $graph_y, 2);
    }

    /**
     * @param $x
     * @param $dx
     * @param $class
     * @param $n
     * @param $kE
     * @param $energy
     */
    static function set_labels($x, $dx, $class, $n, $kE, $energy){
        global $n_labels;
        if ($n < $n_labels) {
            $curE = Svg::extend_energy($energy /*val*/, 0 /*n*/);
            $curY = Svg::convert_energy($curE);
            echo '<text class="' . $class . '" x="' . ($x+$dx) .'" y="' . $curY . '">' . round($curE*$kE, 1) . '</text>' . PHP_EOL;
            echo '<line class="energy" y1="' . $curY . '" y2="' . $curY . '" x1="' . ($x+$dx) . '" x2="' . ($x - 3*$dx) .'"/>' . PHP_EOL;
            Svg::set_labels($x, $dx, $class, $n + 1, $kE, (($energy / $n) * ($n + 1)));
        }
    }

    /*create a string with indexes instead of @{...} (supindex) and ~{...} (subindex)*/
    static function create_indexes($val){
        global $index_dx, $index_dy;

        $val = preg_replace("/@\{([^\}]*)\}~\{([^\}]*)\}/", '<tspan class="index" dy="' . (-$index_dy) . '" dx="' . (-$index_dx) . '">$1</tspan>'
                                                    .'<tspan class="index" dy="' . (2*$index_dy) . '" dx="' . (-$index_dx) . '">$2</tspan>', $val);
        $val = preg_replace("/<\/tspan>([^~@<]*)/", '</tspan><tspan dy="' . (-$index_dy) . '" dx="' . (-$index_dx) . '">$1</tspan>', $val);

        $val = preg_replace("/@\{([^\}]*)\}/", '<tspan class="index" dy="' . (-$index_dy) . '" dx="' . (-$index_dx) . '">$1</tspan>', $val);

        $val = preg_replace("/<\/tspan>([^~@<]+)/", '</tspan><tspan dy="' . ($index_dy) . '" dx="' . (-$index_dx) . '">$1</tspan>', $val);

        $val = preg_replace("/~\{([^\}]*)\}/", '<tspan class="index" dy="' . ($index_dy) . '" dx="' . (-$index_dx) . '">$1</tspan>', $val);
        $val = preg_replace("/<\/tspan>([^~@<]+)/", '</tspan><tspan dy="' . (-$index_dy) . '" dx="' . (-$index_dx) . '">$1</tspan>', $val);

        return $val;
    }

    static function count_length($val){
        $sym = array("@", "~", "{", "}");
        $val = str_replace($sym, "", $val);
        $len = strlen($val) * 7;
        if ($len != 0) $len+=10;
        return $len;
    }

    /*Xml2Array recursive parser*/
    static function parseNode($node, $assocs = null, $valueName = 'value'){
        $arrayElement = [];
        if ($node->nodeType == XML_TEXT_NODE)
            if (trim($node->nodeValue) != "") return trim($node->nodeValue);
            else return null;
        if ($node->hasAttributes())
            foreach ($node->attributes as $attribute)
                $arrayElement[$attribute->nodeName] = $attribute->nodeValue;
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                $childArrayElement = Svg::parseNode($childNode, $assocs, $valueName);
                if ($childArrayElement === null) continue;
                elseif ($childNode->nodeType == XML_TEXT_NODE) $arrayElement[$valueName] = $childArrayElement;
                elseif (isset($childNode->tagName) && $assocs && in_array($childNode->tagName, $assocs))
                    $arrayElement[$childNode->tagName] = $childArrayElement;
                elseif (isset($childNode->tagName)) $arrayElement[$childNode->tagName][] = $childArrayElement;
                else $arrayElement[] = $childArrayElement;
            }
        }
        return $arrayElement;
    }

    static function parseXml($xmlBody, $assocs = null, $valueName = 'value'){
        if (!$xmlBody || $xmlBody == "") return [];
        $DOM = new DOMDocument;
        if (!$DOM->loadXML($xmlBody)) return [];
        return Svg::parseNode($DOM, $assocs, $valueName);
    }
}