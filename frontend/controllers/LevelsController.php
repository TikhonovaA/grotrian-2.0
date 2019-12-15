<?php

namespace frontend\controllers;

use common\models\Atom;
use common\models\Level;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;

/**
 * Class LevelsController
 * @package frontend\controllers
 */
class LevelsController extends MainController
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

        $dataProvider = new ActiveDataProvider([
            'query' => Level::find()
                ->with('source')
                ->where(['ID_ATOM' => $id]),
                //->orderBy(['ENERGY' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        MainController::initTable($atom);
        return $this->render('index', [
            'atom' => $atom,
            'atom_name' => $atom_name,
            'dataProvider' => $dataProvider,
        ]);
    }

}