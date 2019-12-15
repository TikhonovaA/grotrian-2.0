<?php

/* @var $this yii\web\View */

/* @var $atom \common\models\Atom */
/* @var $model \common\models\Level */
/* @var $dataProvider $model \common\models\Level */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Atomic levels - {Z}', ['Z' => $atom->periodicTable->ABBR]);
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => Yii::t('level', 'Configuration type'),
            'attribute' => 'typeConfigurationFormatHtml',
            'format' => 'html',
        ],
        [
            'label' => Yii::t('level', 'Configuration'),
            'attribute' => 'configurationFormatHtml',
            'format' => 'html',
        ],
        'J',
        [
            'label' => Yii::t('level', 'Term'),
            'attribute' => 'term',
            'format' => 'html',
        ],
        [
            'label' => Yii::t('level', 'Energy (cm<sup>-1</sup>)'),
            'encodeLabel' => false,
            'attribute' => 'ENERGY',
            'format' => 'ntext',
        ],
        [
            'label' => Yii::t('level', 'Lifetime'),
            'attribute' => 'LIFETIME',
            'format' => 'ntext',
        ],
        [
            'label' => Yii::t('level', 'Source'),
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