<?php
namespace frontend\controllers;

use common\models\Atom;
use common\models\LevelList;
use common\models\Spectrum;
use common\models\Svg;
use common\models\Transition;
use common\models\TransitionList;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class ChartController
 * @package frontend\controllers
 */
class ChartController extends MainController
{
    /**
     * @param int $id
     * @return string
     * @throws HttpException
     */
    public function actionIndex($id = 2511)
    {
        $atom = Atom::findOne($id);

        if (empty($atom)) {
            throw new HttpException(404);
        }
        $atom_name = $atom->periodicTable->ABBR;

        $enmin = $enmax= $wlmin=$wlmax=$nmax=$lmax=$width=$grouping=$groupbyMu=$prohibitedbyMuOff=$prohibitedbyParOff=$autoStatesOff = null;
        if (Yii::$app->getRequest()->getQueryParam('enmin'))$enmin = Yii::$app->getRequest()->getQueryParam('enmin');
        if (Yii::$app->getRequest()->getQueryParam('enmax'))$enmax = Yii::$app->getRequest()->getQueryParam('enmax');
        if (Yii::$app->getRequest()->getQueryParam('wlmin'))$wlmin = Yii::$app->getRequest()->getQueryParam('wlmin');
        if (Yii::$app->getRequest()->getQueryParam('wlmax'))$wlmax = Yii::$app->getRequest()->getQueryParam('wlmax');
        if (Yii::$app->getRequest()->getQueryParam('nmax'))$nmax = Yii::$app->getRequest()->getQueryParam('nmax');
        if (Yii::$app->getRequest()->getQueryParam('lmax'))$lmax = Yii::$app->getRequest()->getQueryParam('lmax');
        if (Yii::$app->getRequest()->getQueryParam('width'))$width = Yii::$app->getRequest()->getQueryParam('width');
        if (Yii::$app->getRequest()->getQueryParam('grouping'))$grouping = Yii::$app->getRequest()->getQueryParam('grouping');
        if (isset($_REQUEST['groupbyMu'])) $groupbyMu =  true;
        if (isset($_REQUEST['prohibitedbyMuOff'])) $prohibitedbyMuOff =  true;
        if (isset($_REQUEST['prohibitedbyParOff'])) $prohibitedbyParOff =  true;
        if (isset($_REQUEST['autoStatesOff'])) $autoStatesOff =  true;


        $element_id = $id;
        $atom_data = $atom;
        $abbr = $atom->periodicTable->ABBR;

        $limits = Svg::parseXml($atom_data->LIMITS, ["limits", "l1", "l2"]);
        if (isset($limits["limits"]["limit"])) $limits = $limits["limits"]["limit"];
        $n_limits = 0;
        $max_limit = 0;
        $min_limit = 0;
        if (is_array($limits))
            foreach ($limits as $limit)
                if (isset($limit['value'])) {
                    if ($limit['value'] > $max_limit) $max_limit = $limit['value'];
                    if ($limit['value'] < $min_limit || $min_limit == 0) $min_limit = $limit['value'];
                    $n_limits++;
                }

        if ($enmin) $min_energy = $enmin; else $min_energy = 0;
        if ($enmax) $max_energy = $enmax; else $max_energy = 0;
        if ($autoStatesOff) $max_energy = ($max_energy == 0) ? $min_limit : min($max_energy, $min_limit);

        $levelList = new LevelList();
        $options = [];
        if ($nmax) $options['nmax'] = $nmax;
        if ($lmax) $options['lmax'] = $lmax;
        if ($groupbyMu) $levels = $levelList->LoadGroupedByMultiplet($element_id, $min_energy, $max_energy, $options);
        else $levels = $levelList->LoadGrouped($element_id, $min_energy, $max_energy, $options);
        $levelsOrdered = $levelList->GetItemsArray();

        $transitionList = new TransitionList();
        $lines = $transitionList->LoadForDiagram($element_id);


        $breaks = Svg::parseXml($atom_data['BREAKS'], ["breaks", "l1", "l2"]);
        if (isset($breaks["breaks"]["break"])) $breaks = $breaks["breaks"]["break"];
        if (count($breaks) == 0){
            foreach ($levelsOrdered as $level){
                if (isset($prevLevel) && $level['ENERGY'] < $min_limit && $level['ENERGY'] - $prevLevel['ENERGY'] > $min_limit * 0.15){
                    $breaks[] = ['l1' =>['value' => round($prevLevel['ENERGY'] + $min_limit*0.05, -1)],
                        'l2' =>['value' => round($level['ENERGY'] - $min_limit*0.02, -1)]];
                }
                $prevLevel = $level;
            }
        }

        $n_breaks = 0;
        $sum_breaks = 0;
        if (is_array($breaks))
            foreach ($breaks as $break)
                if (isset($break['l1']['value']) && $break['l2']['value']) {
                    $n_breaks++;
                    $sum_breaks += $break['l2']['value'] - $break['l1']['value'];
                }

        $n_labels = 5;
        $toeV = 0.00012398;
        $Ecm_row_w = 50;
        $index_dy = 5;
        $index_dx = 1;
        $level_dx = 5;

        $diagram_w = 1000;
        if (isset($_REQUEST['width'])) $diagram_w = $_REQUEST['width'];

        $diagram_h = 700;
        $term_row_w = 30;

        $dE = round(($min_limit - $sum_breaks) / ($n_labels * 100)) * 100;

        $conf_row_h = 0;

        foreach ($levels as $column)
            if (Svg::count_length($column['CELLCONFIG']) > $conf_row_h)
                $conf_row_h = Svg::count_length($column['CELLCONFIG']);

        $core_row_h = 0;
        foreach ($levels as $column)
            foreach ($column['atomiccore'] as $atomiccore)
                if (Svg::count_length($atomiccore['ATOMICCORE']) > $core_row_h)
                    $core_row_h = Svg::count_length($atomiccore['ATOMICCORE']);

        $term_row_h = 0;
        $n_terms = 0;
        foreach($levels as $column)
            foreach($column['atomiccore'] as $atomiccore)
                foreach($atomiccore['term'] as $term)
                    foreach($term['group'] as $group) {
                        $n_terms++;
                        $str = $group['TERMSECONDPART'] . $term['TERMPREFIX']
                            . $group['TERMFIRSTPART'] . $term['TERMMULTIPLY'] . $group['J'];
                        if (Svg::count_length($str) > $term_row_h)
                            $term_row_h = Svg::count_length($str);
                    }
        if ($n_limits >1) $term_row_h *= 2;

        if ($term_row_w * $n_terms < $diagram_w) $term_row_w = $diagram_w / $n_terms;

        $graph_y = $diagram_h - $core_row_h - $conf_row_h - $term_row_h;
        $t_width = $term_row_w * $n_terms;
        $t_height = $diagram_h + 2;

        Svg::setVars($n_breaks, $breaks, $diagram_h, $graph_y, $min_limit, $sum_breaks, $n_limits, $max_limit, $term_row_h, $n_labels, $index_dx, $index_dy);

        MainController::initTable($atom);

        return $this->render('index', [
            'atom' => $atom,
            'atom_name' => $atom_name,
            'enmin' => $enmin,
            'enmax' => $enmax,
            'wlmin' => $wlmin,
            'wlmax' => $wlmax,
            'nmax' => $nmax,
            'lmax' => $lmax,
            'width' => $width,
            'grouping' => $grouping,
            'groupbyMu' => $groupbyMu,
            'prohibitedbyMuOff' => $prohibitedbyMuOff,
            'prohibitedbyParOff' => $prohibitedbyParOff,
            'autoStatesOff' => $autoStatesOff,
            'diagram_w' => $diagram_w,
            'diagram_h' => $diagram_h,
            'abbr' => $abbr,
            'Ecm_row_w' => $Ecm_row_w,
            'conf_row_h' => $conf_row_h,
            'graph_y' => $graph_y,
            'min_limit' => $min_limit,
            'levels' => $levels,
            'n_limits' => $n_limits,
            'term_row_h' => $term_row_h,
            'max_limit' => $max_limit,
            'breaks' => $breaks,
            'dE' => $dE,
            'term_row_w' => $term_row_w,
            'core_row_h' => $core_row_h,
            'index_dx' => $index_dx,
            'index_dy' => $index_dy,
            'level_dx' => $level_dx,
            'n_terms' => $n_terms,
            'toeV' => $toeV,
            't_width' => $t_width,
            't_height' => $t_height,
            'lines' => $lines,
        ]);
    }
}
