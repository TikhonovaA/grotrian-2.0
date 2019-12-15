<?php

namespace frontend\controllers;


use common\models\Atom;
use common\models\PeriodicTable;
use Yii;
use yii\web\Controller;

/**
 * Class LevelsController
 * @package frontend\controllers
 */
class MainController extends Controller{

    //передаем данные в layout
    static function initTable($atom)
    {
        $periodic_table = PeriodicTable::find()->orderBy('ID')->all();
        $atoms = Atom::find()->select(['ID', 'ID_ELEMENT'])->where(['IONIZATION' => '0','MASS_NUMBER' => null])->distinct()->orderBy('ID_ELEMENT')->all();
        $isotopes = Atom::find()->select(['MASS_NUMBER', 'ID'])->where(['ID_ELEMENT' => $atom->ID_ELEMENT])->andWhere(['not', ['MASS_NUMBER' => null]])->orderBy('MASS_NUMBER')->all();
        $ions = Atom::find()->select(['IONIZATION', 'ID'])->where(['ID_ELEMENT' => $atom->ID_ELEMENT, 'MASS_NUMBER' => null])->orderBy('IONIZATION')->all();
        Yii::$app->view->params['atoms'] = $atoms;
        Yii::$app->view->params['periodic_table'] = $periodic_table;
        Yii::$app->view->params['ions'] = $ions;
        Yii::$app->view->params['isotopes'] = $isotopes;
        Yii::$app->view->params['atom'] = $atom;
    }
}

