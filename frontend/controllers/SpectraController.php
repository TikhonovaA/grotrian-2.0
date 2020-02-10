<?php
namespace frontend\controllers;

use common\models\Atom;
use common\models\Spectrum;
use common\models\Transition;
use yii\web\HttpException;

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
        $transitions_list = Transition::find()->where(['ID_ATOM' => $id])->asArray()->all();

        foreach ($transitions_list as &$transition){
            $transition["COLOR"] = Spectrum::wavelength2RGB($transition["WAVELENGTH"]);
        }

        MainController::initTable($atom);

        return $this->render('index', [
            'atom' => $atom,
            'atom_name' => $atom_name,
            'transitions_list' => $transitions_list,
        ]);
    }
}
