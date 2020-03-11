<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use \cinghie\multilanguage\widgets\MultiLanguageWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::t('site', Yii::$app->name),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top ',
            ],
        ]);
        $id = 'index';
        if(Yii::$app->request->get('id') ) $id = Yii::$app->request->get('id') ;
        $menuItems = [
            ['label' => Yii::t('site','Elements'), 'url' => ['/element/'.$id ]],
            ['label' => Yii::t('site','Levels'), 'url' => ['/levels/'.$id ]],
            ['label' => Yii::t('site','Transitions'), 'url' => ['/transitions/'.$id  ]],
            ['label' => Yii::t('site','Spectra'), 'url' => ['/spectra/'.$id  ]],
            ['label' => Yii::t('site','Charts'), 'url' => ['/chart/'.$id  ]],
        ];

        $menuItems[] = '<li style="padding: 15px 0">' .MultiLanguageWidget::widget([]) . '</li>';
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?php if(Yii::$app->request->pathInfo): ?>
                <div class="pos-f-t">
                    <div class="collapse" id="navbarToggleExternalContent">
                        <div class="bg-dark p-4">
                            <?= $this->render('../element_picker/_element_picker', ['periodic_table' => $this->params['periodic_table'],'atoms' => $this->params['atoms']]) ?>
                        </div>
                    </div>
                    <nav >
                        <button class="button white" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span><?=$this->params['atom']->periodicTable->ABBR?></span>
                        </button>
                        <?php foreach ($this->params['ions'] as $ion):?>
                            <?php if($ion->IONIZATION != -1): ?>
                                <?php if ($ion->ID == Yii::$app->request->get('id')):?>
                                    <a class="ion" href="<?=$ion->ID?>">
                                        <button class="button white selected" type="button" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                            <span><?=\common\models\Atom::numberToRoman(intval($ion->IONIZATION) + 1)?></span>
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <a class="ion" href="<?=$ion->ID?>">
                                        <button class="button white" type="button" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                            <span><?=\common\models\Atom::numberToRoman(intval($ion->IONIZATION) + 1)?></span>
                                        </button>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach;?>
                        <?php foreach ($this->params['ions'] as $ion):?>
                            <?php if($ion->IONIZATION == -1): ?>
                                <?php if ($ion->ID == Yii::$app->request->get('id')):?>
                                    <a class="ion" href="<?=$ion->ID?>">
                                        <button class="button white selected" type="button" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                            <span><?=$this->params['atom']->periodicTable->ABBR?></span><sup>–</sup>
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <a class="ion" href="<?=$ion->ID?>">
                                        <button class="button white" type="button" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                            <span><?=$this->params['atom']->periodicTable->ABBR?></span><sup>–</sup>
                                        </button>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach;?>
                        <?php foreach ($this->params['isotopes'] as $isotope):?>
                                <?php if ($isotope->ID == Yii::$app->request->get('id')):?>
                                    <a class="ion" href="<?=$isotope->ID?>">
                                        <button class="button white selected" type="button" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                            <span><?=$this->params['atom']->periodicTable->ABBR?></span><sup><?=$isotope->MASS_NUMBER?></sup>
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <a class="ion" href="<?=$isotope->ID?>">
                                        <button class="button white" type="button" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                            <span><?=$this->params['atom']->periodicTable->ABBR?></span><sup><?=$isotope->MASS_NUMBER?></sup>
                                        </button>
                                    </a>
                                <?php endif; ?>
                        <?php endforeach;?>
                    </nav>
                </div>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>