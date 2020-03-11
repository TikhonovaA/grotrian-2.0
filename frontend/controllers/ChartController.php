<?php
namespace frontend\controllers;

use common\models\Atom;
use common\models\Spectrum;
use common\models\Transition;
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
        $atom = Atom::findOne($id);

        if (empty($atom)) {
            throw new HttpException(404);
        }
        $atom_name = $atom->periodicTable->ABBR;
        $ion = Atom::numberToRoman(intval($atom->IONIZATION) + 1);
        $transitions = Transition::find()->with('lowerLevel', 'upperLevel')->where(['ID_ATOM' => $id])->all();
        $transitions_list = ArrayHelper::toArray($transitions, []);

        MainController::initTable($atom);

        return $this->render('index', [
            'atom' => $atom,
            'atom_name' => $atom_name,
            'transitions_list' => $transitions_list,
            'ion' => $ion,
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
        ]);
    }
}
