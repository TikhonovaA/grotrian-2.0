<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property integer $ID
 * @property integer $ID_ATOM
 * @property string $CONFIG
 * @property float $LIFETIME
 * @property string $J
 * @property string $TERMPREFIX
 * @property string $TERMMULTIPLY
 * @property string $TERMFIRSTPART
 * @property string $TERMSECONDPART
 * @property string $typeConfiguration
 * @property string $typeConfigurationFormatHtml
 * @property string $source
 *
 * Class Level
 * @package common\models
 */
class Level extends ActiveRecord
{
    public $typeConfig;

    public static function tableName()
    {
        return '{{LEVELS}}';
    }

    /**
     * generate type configuration
     */
    public function setTypeConfig()
    {
        if (empty($this->typeConfig) and !empty($this->CONFIG)) {
            $typeConfig = $this->CONFIG;
            if ($typeConfig == "(?)") $typeConfig = "?";

            //убираем с конца конфигурации, j и терма незначащие символы, такие как '?', ', "
            $typeConfig = preg_replace('/^(.*?)([^a-zA-Z\}\)]*)$/', '$1', $typeConfig);

            //убираем ~{...} c конца конфигурации
            $typeConfig = preg_replace('/^(.*)(~\{[^\{\}]*\})$/', '$1', $typeConfig);
            //убираем последнюю букву из конфигурации, если их там две
            $typeConfig = preg_replace('/^(.*[a-zA-Z])[a-zA-Z]$/', '$1', $typeConfig);

            //если заканчивается на @{число}, то в CELLCONFIG копируем CONFIG %@{%}
            //если не заканчивается на @{число}, то в CELLCONFIG заносим CONFIG с заменой последнего числа на 'n'
            $this->typeConfig = $typeConfig;

            if (!preg_match('/^(.*@\{.*\})$/', $typeConfig)) {
                if (preg_match('/^(.*?)(\d+)([a-z])$/', $typeConfig)) {
                    $this->typeConfig = preg_replace('/^(.*?)(\d+)([a-z])$/', '$1n$3', $typeConfig);
                }
            }
            if (empty($this->typeConfig)) {
                $this->typeConfig = '?';
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTypeConfiguration()
    {
        self::setTypeConfig();

        return $this->typeConfig;
    }

    /**
     * generate term in format html
     * @return string
     */
    public function getTerm()
    {
        $term = '';

        if (!empty($this->TERMSECONDPART)) {
            $term .= "<span>{$this->TERMSECONDPART}</span>";
        }
        if (!empty($this->TERMPREFIX)) {
            $term .= "<sup>{$this->TERMPREFIX}</sup>";
        }
        if (trim($this->TERMFIRSTPART) == '') {
            $term .= '(?)';
        } else {
            $term .= "<span>{$this->TERMFIRSTPART}</span>";
        }
        if ($this->TERMMULTIPLY == true) {
            $term .= '<span>&deg;</span>';
        }

        return $term;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasMany(Source::className(), ['ID' => 'ID_SOURCE'])
            ->viaTable(Bibliolink::tableName(), ['ID_RECORD' => 'ID'], function ($query) {
                /* @var $query \yii\db\ActiveQuery */
                $query->andWhere(['RECORDTYPE' => Bibliolink::TYPE_LEVEL]);
            });

    }

    /**
     * @return string|string[]|null
     */
    public function getTypeConfigurationFormatHtml()
    {
        $result = $this->typeConfiguration;
        $result = preg_replace('/\@{([1-9]+)}/i', '<sup>$1</sup>', $result);
        $result = preg_replace('/\~{(.*?)}/i', '<sub>$1</sub>', $result);
        $result = preg_replace('/\@{([0])}/i', '&deg;', $result);
        return $result;
    }

    /**
     * @return string|string[]|null
     */
    public function getConfigurationFormatHtml()
    {
        $result = $this->CONFIG;
        $result = preg_replace('/\@{([1-9]+)}/i', '<sup>$1</sup>', $result);
        $result = preg_replace('/\~{(.*?)}/i', '<sub>$1</sub>', $result);
        $result = preg_replace('/\@{([0])}/i', '&deg;', $result);
        return $result;
    }

    public function getCellConfig()
    {
            $config = $this->CONFIG;
            if ($config == "(?)") $config = "?";

            //убираем с конца конфигурации, j и терма незначащие символы, такие как '?', ', "
            $config = preg_replace('/^(.*?)([^a-zA-Z\}\)]*)$/', '$1', $config);

            //убираем ~{...} c конца конфигурации
            $config = preg_replace('/^(.*)(~\{[^\{\}]*\})$/', '$1', $config);
            //убираем последнюю букву из конфигурации, если их там две
            $config = preg_replace('/^(.*[a-zA-Z])[a-zA-Z]$/', '$1', $config);

            //если заканчивается на @{число}, то в CELLCONFIG копируем CONFIG %@{%}
            //если не заканчивается на @{число}, то в CELLCONFIG заносим CONFIG с заменой последнего числа на 'n'
            $result = $config;

            if (!preg_match('/^(.*@\{.*\})$/', $config)) {
                if (preg_match('/^(.*?)(\d+)([a-z])$/', $config))
                    $result = preg_replace('/^(.*?)(\d+)([a-z])$/', '$1n$3', $config);
            }
            /*            else{
                            $item[$name] = preg_replace_callback('/^(.*?)(\d+)([a-z])@\{(\d+)\}$/',
                                function ($matches) {
                                    if (($matches[4] - 1)  == 1) $index = '';
                                    else $index = '@{' . ($matches[4] - 1) . '}';
                                    $replacement = $matches[1] . $matches[2] . $matches[3] . $index . 'n' . $matches[3];
                                    return $replacement;
                                },
                                $config
                            );
                        }
            */
            if ($config == null || $config == '')
                $result = $config = '?';
            return $result;
    }

}