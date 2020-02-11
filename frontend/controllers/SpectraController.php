<?php
namespace frontend\controllers;

use common\models\Atom;
use common\models\Spectrum;
use common\models\Transition;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

/**
 * Class SpectraController
 * @package frontend\controllers
 */
class SpectraController extends MainController
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
        $ion = Atom::numberToRoman(intval($atom->IONIZATION) + 1);
        $transitions = Transition::find()->with('lowerLevel', 'upperLevel')->where(['ID_ATOM' => $id])->all();
        $transitions_list = ArrayHelper::toArray($transitions, []);

        $index = 0;
        foreach ($transitions_list as &$transition){
            $result = '';
            if ($transitions[$index]->lowerLevel) {
                $result = $transitions[$index]->lowerLevel->configurationFormatHtml . ':' . $transitions[$index]->lowerLevel->term;
                if ($transitions[$index]->lowerLevel->J) {
                    $result .= "<sub>{$transitions[$index]->lowerLevel->J}</sub>";
                }
            }
            $transition["LOWER_TERM"] = $result;
            $result = '';
            if ($transitions[$index]->upperLevel) {
                $result = $transitions[$index]->upperLevel->configurationFormatHtml . ':' . $transitions[$index]->upperLevel->term;
                if ($transitions[$index]->upperLevel->J) {
                    $result .= "<sub>{$transitions[$index]->upperLevel->J}</sub>";
                }
            }
            $transition["UPPER_TERM"] = $result;
            $transition["COLOR"] = Spectrum::wavelength2RGB($transition["WAVELENGTH"]);
            $transition["upperLevel"]["CONFIG"] = $transitions[$index]->upperLevel->CONFIG;
            $transition["lowerLevel"]["CONFIG"] = $transitions[$index]->lowerLevel->CONFIG;
            $index++;
        }

        MainController::initTable($atom);

        return $this->render('index', [
            'atom' => $atom,
            'atom_name' => $atom_name,
            'transitions_list' => $transitions_list,
            'ion' => $ion,
        ]);
    }
}
