<?php

/* @var $this yii\web\View */

/* @var $atom \common\models\Atom */
/* @var $model \common\models\Transition */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Atomic transitions - {Z}', ['Z' => $atom->periodicTable->ABBR]);
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => Yii::t('transitions', 'Serie'),
            'attribute' => 'serieFormatHtml',
            'format' => 'html',
        ],
        [
            'label' => Yii::t('transitions', 'Lower level'),
            'format' => 'html',
            'value' => function($model) {
                $result = '';
                if ($model->lowerLevel) {
                    $result = $model->lowerLevel->configurationFormatHtml . ':' . $model->lowerLevel->term;
                    if ($model->lowerLevel->J) {
                        $result .= "<sub>{$model->lowerLevel->J}</sub>";
                    }
                }
                return $result;
            }
        ],
        [
            'label' => Yii::t('transitions', 'Upper level'),
            'format' => 'html',
            'value' => function($model) {
                $result = '';
                if ($model->upperLevel) {
                    $result = $model->upperLevel->configurationFormatHtml . ':' . $model->upperLevel->term;
                    if ($model->upperLevel->J) {
                        $result .= "<sub>{$model->upperLevel->J}</sub>";
                    }
                }
                return $result;
            }
        ],
        [
            'label' => Yii::t('transitions', 'Wavelength [<i>Å</i>]'),
            'encodeLabel' => false,
            'attribute' => 'WAVELENGTH',
            'format' => 'ntext',
        ],
        [
            'label' => Yii::t('transitions', 'Intensity'),
            'attribute' => 'INTENSITY',
            'format' => 'ntext',
        ],
        [
            'label' => Yii::t('transitions', '<i>f<sub>ik</sub></i>'),
            'encodeLabel' => false,
            'attribute' => 'OSCILLATOR_F',
            'format' => 'ntext',
        ],
        [
            'label' => Yii::t('transitions', 'A<sub><i>ki</i></sub><br>[<i>10<sup>8</sup>sec<sup>-1</sup></i>]'),
            'encodeLabel' => false,
            'attribute' => 'PROBABILITY',
            'format' => 'ntext',
            'value' => function($model) {
                $result = '';
                if ($model->PROBABILITY) {
                    $result = $model->PROBABILITY / 100000000;
                }
                return $result;
            }
        ],
        [
            'label' => Yii::t('transitions', 'Excitation cross section <br> Q<sub>max</sub>* 10<sup>18</sup>, <i>cm<sup>2</sup></i>'),
            'encodeLabel' => false,
            'attribute' => 'CROSSECTION',
            'format' => 'html',
        ],
        [
            'label' => Yii::t('transitions', 'Source'),
            'format' => 'html',
            'value' => function($model) {
                $result = '';
                if (!empty($model->source)) {
                    foreach ($model->source as $item) {
                        $result .= Html::a($item->ID, ['#']) . ' ';
                    }
                }
                return $result;
            },
        ],
    ],
]) ?>