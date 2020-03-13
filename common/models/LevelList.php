<?php
/**
 * Created by PhpStorm.
 * User: atikh
 * Date: 13.03.2020
 * Time: 10:41
 */

namespace common\models;

use common\models\Level;
use yii\helpers\ArrayHelper;

class LevelList
{

    var $items;

    public function GetItemsArray()
    {
        return $this->items;
    }

    public function GroupArrayByKeys($array, $keys, $groupName)
    {
        $newarray = [];
        foreach($array as $value) {
            $found = false;
            foreach ($newarray as &$newarrayvalue){
                //проверяем совпадение $value и $newarrayvalue
                $equal = true;
                foreach ($keys as $key) {
                    if ($value[$key] != $newarrayvalue[$key]) {
                        $equal = false;
                        break;
                    }
                }
                //если всё совпало добавляем туда элемент
                if ($equal) {
                    $newarrayvalue[$groupName][] = $value;
                    $found = true;
                    break;
                }
            }
            unset($newarrayvalue);
            //если не нашли совпадение создаем
            if (!$found){
                $newarrayvalue = [];
                foreach ($keys as $key)
                    $newarrayvalue[$key] = $value[$key];
                $newarrayvalue[$groupName][] = $value;
                $newarray[] = $newarrayvalue;
            }
        }
        return $newarray;
    }

    public function LoadGroupedByMultiplet($element_id, $min_energy = 0, $max_energy = 0, $options = [])
    {
        if(($min_energy > 0) && ($max_energy > 0)){
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->andWhere(['>=','ENERGY', $min_energy])
                ->andWhere(['<=', 'ENERGY', $max_energy])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }
        else if($min_energy > 0){
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->andWhere(['>=','ENERGY', $min_energy])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }
        else if ($max_energy > 0){
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->andWhere(['<=', 'ENERGY', $max_energy])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }
        else {
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }

        $level_list = ArrayHelper::toArray($levels, []);
        foreach ($level_list as $i => &$level) {
            $level["CELLCONFIG"] = $levels[$i]->cellConfig;
        }
        $this->items = $level_list;

        foreach ($level_list as $i => &$level){
            //проверяем nmax и lmax
            $configwoac = preg_replace('/\([^\(\)]*\)/', '', $level['CONFIG']);
            $configwoind = preg_replace('/\{[^\{\}]*\}/', '', $configwoac);
            $n = preg_replace('/^.*?(\d+)[^0-9]*$/', '$1', $configwoind);
            $l = preg_replace('/^.*?([a-z])[^a-z]*$/', '$1', $configwoind);

            if (isset($options['nmax']) && $n > $options['nmax']) {
                unset($level_list[$i]);
                continue;
            }
            if (isset($options['lmax']) && $l > $options['lmax'] && $l != 's' && $l != 'p' && $l != 'd' && $l != 'f') {
                unset($level_list[$i]);
                continue;
            }

            if ($level['TERMFIRSTPART'] == "(?)") $level['TERMFIRSTPART'] = "?";
            $level['FULL_CONFIG'] = $level['CONFIG'];

            if ($level['CONFIG'] == "(?)") $level['CONFIG'] = "?";
            //убираем с конца конфигурации, j и терма незначащие символы, такие как '?', ', "
            $level['CONFIG'] = preg_replace('/^(.*?)([^a-zA-Z\}\)]*)$/', '$1', $level['CONFIG']);
            //убираем ~{...} c конца конфигурации
            $level['CONFIG'] = preg_replace('/^(.*)(~\{[^\{\}]*\})$/', '$1', $level['CONFIG']);
            //убираем последнюю букву из конфигурации, если их там две
            $level['CONFIG'] = preg_replace('/^(.*[a-zA-Z])[a-zA-Z]$/', '$1', $level['CONFIG']);

            //устанавливаем поля с NULL в ''
            if ($level['TERMSECONDPART'] == null) $level['TERMSECONDPART'] = '';
            if ($level['TERMPREFIX'] == null) $level['TERMPREFIX'] = '';
            if ($level['TERMFIRSTPART'] == null || $level['TERMFIRSTPART'] == '') $level['TERMFIRSTPART'] = '?';

            //убираем с конца конфигурации, j и терма незначащие символы, такие как '?', ', "
            $level['J'] = preg_replace('/^(.*?)([^0-9]*)$/', '$1', $level['J']);
            $level['TERMFIRSTPART'] = preg_replace('/^(.*?)([^a-zA-Z0-9\)\}\]]*)$/', '$1', $level['TERMFIRSTPART']);
            $level['TERMSECONDPART'] = trim($level['TERMSECONDPART']);


            //если есть атомный остаток, то выносим его в отдельный атрибут (ATOMICCORE), из CONFIG убираем
            $regexp_ac = '/^(.*)\(([^\)]*)\)(\d+[a-z])$/';
            $regexp_ac2 = '/^(.*)\(([^\)]*)\)(n[a-z])$/';
            //echo PHP_EOL . $level['CONFIG'] . " : ";
            if (preg_match($regexp_ac, $level['CONFIG'])){
                $level['ATOMICCORE'] = preg_replace($regexp_ac, '$2', $level['CONFIG']);
                //echo $level['ATOMICCORE'] . " : ";
                $level['CONFIG'] =  preg_replace($regexp_ac, '$1$3', $level['CONFIG']);
                $level['CELLCONFIG'] =  preg_replace($regexp_ac2, '$1$3', $level['CELLCONFIG']);
                //echo $level['CONFIG'];
            }
            else $level['ATOMICCORE'] = '';

            if ($level['CONFIG']  == null || $level['CONFIG']  == '')
                $level['CONFIG'] = '?';

            if ($level['TERMFIRSTPART'] == null || $level['TERMFIRSTPART'] == '')
                $level['TERMFIRSTPART'] = '?';
        }
        unset($level);
        //если у всех уровней с одинаковым CELLCONFIG совпадают и CONFIG, то CELLCONFIG = CONFIG
        $cellconfigs = [];

        foreach ($level_list as $level) {
            if (!isset($cellconfigs[$level['CELLCONFIG']])) $cellconfigs[$level['CELLCONFIG']] = [];
            if (!in_array($level['CONFIG'], $cellconfigs[$level['CELLCONFIG']]))
                $cellconfigs[$level['CELLCONFIG']][] = $level['CONFIG'];
        }

        $new_cellconfigs = [];
        foreach ($cellconfigs as $cellconfig => $configs) {
            if (count($configs) == 1)
                $new_cellconfigs[$configs[0]] = $configs;
            else $new_cellconfigs[$cellconfig] = $configs;
        }
        $cellconfigs = $new_cellconfigs;

        foreach ($level_list as &$level)
            foreach ($cellconfigs as $cellconfig => $configs)
                foreach ($configs as $config)
                    if ($level['CONFIG'] == $config)
                        $level['CELLCONFIG'] = $cellconfig;
        unset($level);

        //генерируем long
        $transitionList = new TransitionList();
        $transitionList->LoadWithLevels($element_id);
        $transitions =  $transitionList->GetItemsArray();

        foreach ($level_list as &$level){
            $level['long'] = 0;
            if ($level['ENERGY'] == 0) $level['long'] = 1;
            foreach($transitions as $transition){
                if ($transition['lowerLevel']['ID'] == $level['ID'] && $transition['lowerLevel']['TERMMULTIPLY'] != $transition['upperLevel']['TERMMULTIPLY']) {
                    $level['long'] = 1;
                    break;
                }
            }

        }
        unset($level);

        if ($level_list[0]['ENERGY'] == 0) $ground_config = $level_list[0]['CELLCONFIG'];//Поскольку у нас $level_list отсортирован, то первый элемент - с энергией 0;
        foreach($level_list as $i => &$level) {
            if ($level['CELLCONFIG'] == $ground_config) {
                $level['GROUNDCONFIG'] = 1;
            }
            else $level['GROUNDCONFIG'] = 0;
        }
        unset($level);


        //1. Group by multiplet
        $ground_items = [];
        $odd_items = [];
        $even_items = [];
        $ground_terms = [];
        $even_terms = [];
        $odd_terms = [];
        $ground_groups = [];
        $ground_atomiccores = [];
        $ground_columns = [];
        $odd_groups = [];
        $odd_atomiccores = [];
        $odd_columns = [];
        $even_groups = [];
        $even_atomiccores = [];
        $even_columns = [];
        $multiplets = $this->GroupArrayByKeys($level_list, ['TERMPREFIX'], 'level_list');

        foreach($multiplets as $m_key => $multiplet) {
            $ground_items[$m_key] = [];
            $odd_items[$m_key] = [];
            $even_items[$m_key] = [];

            foreach ($multiplet['level_list'] as $i => &$level) {
                if ($level['GROUNDCONFIG'] == 1) {
                    $ground_items[$m_key][] = $level;
                    unset($multiplet['level_list'][$i]);
                }
            }
            unset($level);

            foreach ($multiplet['level_list'] as $level) {
                if ($level['TERMMULTIPLY'] == 1)
                    $odd_items[$m_key][] = $level;
                else
                    $even_items[$m_key][] = $level;
            }

            //Группируем элементы массива
            $ground_terms[$m_key] = $this->GroupArrayByKeys($ground_items[$m_key], ['CELLCONFIG', 'GROUNDCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY', 'TERMFIRSTPART', 'TERMSECONDPART', 'J'], 'level');
            $even_terms[$m_key] = $this->GroupArrayByKeys($even_items[$m_key], ['CELLCONFIG', 'GROUNDCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY', 'TERMFIRSTPART', 'TERMSECONDPART', 'J'], 'level');
            $odd_terms[$m_key] = $this->GroupArrayByKeys($odd_items[$m_key], ['CELLCONFIG', 'GROUNDCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY', 'TERMFIRSTPART', 'TERMSECONDPART', 'J'], 'level');
            $ground_groups[$m_key] = $this->GroupArrayByKeys($ground_terms[$m_key], ['CELLCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY'], 'group');
            $ground_atomiccores[$m_key] = $this->GroupArrayByKeys($ground_groups[$m_key], ['CELLCONFIG', 'ATOMICCORE', 'TERMMULTIPLY'], 'term');
            $ground_columns[$m_key] = $this->GroupArrayByKeys($ground_atomiccores[$m_key], ['CELLCONFIG', 'TERMMULTIPLY'], 'atomiccore');
            $odd_groups[$m_key] = $this->GroupArrayByKeys($odd_terms[$m_key], ['CELLCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY'], 'group');
            $odd_atomiccores[$m_key] = $this->GroupArrayByKeys($odd_groups[$m_key], ['CELLCONFIG', 'ATOMICCORE', 'TERMMULTIPLY'], 'term');
            $odd_columns[$m_key] = $this->GroupArrayByKeys($odd_atomiccores[$m_key], ['CELLCONFIG', 'TERMMULTIPLY'], 'atomiccore');
            $even_groups[$m_key] = $this->GroupArrayByKeys($even_terms[$m_key], ['CELLCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY'], 'group');
            $even_atomiccores[$m_key] = $this->GroupArrayByKeys($even_groups[$m_key], ['CELLCONFIG', 'ATOMICCORE', 'TERMMULTIPLY'], 'term');
            $even_columns[$m_key] = $this->GroupArrayByKeys($even_atomiccores[$m_key], ['CELLCONFIG', 'TERMMULTIPLY'], 'atomiccore');

            foreach ($even_columns[$m_key] as &$column) {
                foreach ($column['atomiccore'] as &$atomiccore) {
                    foreach ($atomiccore['term'] as &$term) {
                        $term['group'] = array_reverse($term['group']);
                    }
                    unset($term);
                    $atomiccore['term'] = array_reverse($atomiccore['term']);
                }
                unset($atomiccore);
                $column['atomiccore'] = array_reverse($column['atomiccore']);
            }
            unset($column);
            $even_columns[$m_key] = array_reverse($even_columns[$m_key]);

            if (isset($ground_columns[$m_key][0]) && $ground_columns[$m_key][0]['TERMMULTIPLY'] == 1) {
                foreach ($ground_columns[$m_key] as &$column) {
                    foreach ($column['atomiccore'] as &$atomiccore) {
                        foreach ($atomiccore['term'] as &$term) {
                            $term['group'] = array_reverse($term['group']);
                        }
                        unset($term);
                        $atomiccore['term'] = array_reverse($atomiccore['term']);
                    }
                    unset($atomiccore);
                    $column['atomiccore'] = array_reverse($column['atomiccore']);
                }
                unset($column);
                $ground_columns[$m_key] = array_reverse($ground_columns[$m_key]);
            }

            $columns[$m_key] = array_merge($odd_columns[$m_key], $ground_columns[$m_key], $even_columns[$m_key]);
            //echo "***";
            //print_r($ground_columns[$m_key]);
        }
        $final_columns = [];
        foreach($multiplets as $m_key => $multiplet){
            $final_columns = array_merge($final_columns, $columns[$m_key]);

        }
        return $final_columns;
    }

    public function LoadGrouped($element_id, $min_energy = 0, $max_energy = 0, $options = [])
    {
        if(($min_energy > 0) && ($max_energy > 0)){
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->andWhere(['>=','ENERGY', $min_energy])
                ->andWhere(['<=', 'ENERGY', $max_energy])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }
        else if($min_energy > 0){
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->andWhere(['>=','ENERGY', $min_energy])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }
        else if ($max_energy > 0){
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->andWhere(['<=', 'ENERGY', $max_energy])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }
        else {
            $levels = Level::find()
                ->where(['ID_ATOM' => $element_id])
                ->orderBy(['ENERGY' => SORT_ASC])
                ->all();
        }

        $level_list = ArrayHelper::toArray($levels, []);
        foreach ($level_list as $i => &$level) {
            $level["CELLCONFIG"] = $levels[$i]->cellConfig;
        }
        $this->items = $level_list;

        $items = $this->GetItemsArray();

        foreach ($items as $i => &$item) {
            //проверяем nmax и lmax
            $configwoac = preg_replace('/\([^\(\)]*\)/', '', $item['CONFIG']);
            $configwoind = preg_replace('/\{[^\{\}]*\}/', '', $configwoac);
            $n = preg_replace('/^.*?(\d+)[^0-9]*$/', '$1', $configwoind);
            $l = preg_replace('/^.*?([a-z])[^a-z]*$/', '$1', $configwoind);

            if (isset($options['nmax']) && $n > $options['nmax']) {
                unset($items[$i]);
                continue;
            }
            if (isset($options['lmax']) && $l > $options['lmax'] && $l != 's' && $l != 'p' && $l != 'd' && $l != 'f') {
                unset($items[$i]);
                continue;
            }


            if ($item['TERMFIRSTPART'] == "(?)") $item['TERMFIRSTPART'] = "?";
            $item['FULL_CONFIG'] = $item['CONFIG'];

            if ($item['CONFIG'] == "(?)") $item['CONFIG'] = "?";
            //убираем с конца конфигурации, j и терма незначащие символы, такие как '?', ', "
            $item['CONFIG'] = preg_replace('/^(.*?)([^a-zA-Z\}\)]*)$/', '$1', $item['CONFIG']);
            //убираем ~{...} c конца конфигурации
            $item['CONFIG'] = preg_replace('/^(.*)(~\{[^\{\}]*\})$/', '$1', $item['CONFIG']);
            //убираем последнюю букву из конфигурации, если их там две
            $item['CONFIG'] = preg_replace('/^(.*[a-zA-Z])[a-zA-Z]$/', '$1', $item['CONFIG']);

            //устанавливаем поля с NULL в ''
            if ($item['TERMSECONDPART'] == null) $item['TERMSECONDPART'] = '';
            if ($item['TERMPREFIX'] == null) $item['TERMPREFIX'] = '';
            if ($item['TERMFIRSTPART'] == null || $item['TERMFIRSTPART'] == '') $item['TERMFIRSTPART'] = '?';

            //убираем с конца конфигурации, j и терма незначащие символы, такие как '?', ', "
            $item['J'] = preg_replace('/^(.*?)([^0-9]*)$/', '$1', $item['J']);
            $item['TERMFIRSTPART'] = preg_replace('/^(.*?)([^a-zA-Z0-9\)\}\]]*)$/', '$1', $item['TERMFIRSTPART']);
            $item['TERMSECONDPART'] = trim($item['TERMSECONDPART']);


            //если есть атомный остаток, то выносим его в отдельный атрибут (ATOMICCORE), из CONFIG убираем
            $regexp_ac = '/^(.*)\(([^\)]*)\)(\d+)([a-z])$/';
            $regexp_ac2 = '/^(.*)\(([^\)]*)\)(n[a-z])$/';
            //echo PHP_EOL . $item['CONFIG'] . " : ";
            if (preg_match($regexp_ac, $item['CONFIG'])) {
                $item['ATOMICCORE'] = preg_replace($regexp_ac, '$2', $item['CONFIG']);
                //echo $item['ATOMICCORE'] . " : ";
                $item['CONFIG'] = preg_replace($regexp_ac, '$1$3$4', $item['CONFIG']);
                $item['CELLCONFIG'] = preg_replace($regexp_ac2, '$1$3', $item['CELLCONFIG']);
                //echo $item['CONFIG'];
            } else $item['ATOMICCORE'] = '';

            if ($item['CONFIG'] == null || $item['CONFIG'] == '')
                $item['CONFIG'] = '?';

            if ($item['TERMFIRSTPART'] == null || $item['TERMFIRSTPART'] == '')
                $item['TERMFIRSTPART'] = '?';
        }
        unset($item);
        //если у всех уровней с одинаковым CELLCONFIG совпадают и CONFIG, то CELLCONFIG = CONFIG
        $cellconfigs = [];

        foreach ($items as $item) {
            if (!isset($cellconfigs[$item['CELLCONFIG']])) $cellconfigs[$item['CELLCONFIG']] = [];
            if (!in_array($item['CONFIG'], $cellconfigs[$item['CELLCONFIG']]))
                $cellconfigs[$item['CELLCONFIG']][] = $item['CONFIG'];
        }

        $new_cellconfigs = [];
        foreach ($cellconfigs as $cellconfig => $configs) {
            if (count($configs) == 1)
                $new_cellconfigs[$configs[0]] = $configs;
            else $new_cellconfigs[$cellconfig] = $configs;
        }
        $cellconfigs = $new_cellconfigs;

        foreach ($items as &$item)
            foreach ($cellconfigs as $cellconfig => $configs)
                foreach ($configs as $config)
                    if ($item['CONFIG'] == $config)
                        $item['CELLCONFIG'] = $cellconfig;
        unset($item);

        //генерируем long
        $transitionList = new TransitionList();
        $transitionList->LoadWithLevels($element_id);
        $transitions =  $transitionList->GetItemsArray();

        foreach ($items as &$item){
            $item['long'] = 0;
            if ($item['ENERGY'] == 0) $item['long'] = 1;
            foreach($transitions as $transition){
                if ($transition['lowerLevel']['ID'] == $item['ID'] && $transition['lowerLevel']['TERMMULTIPLY'] != $transition['upperLevel']['TERMMULTIPLY']) {
                    $item['long'] = 1;
                    break;
                }
            }

        }
        unset($item);

        $ground_items = [];
        if ($items[0]['ENERGY'] == 0) $ground_config = $items[0]['CELLCONFIG'];//Поскольку у нас $items отсортирован, то первый элемент - с энергией 0;
        foreach($items as $i => &$item) {
            if ($item['CELLCONFIG'] == $ground_config) {
                $item['GROUNDCONFIG'] = 1;
                $ground_items[] = $item;
                unset($items[$i]);
            }
            else $item['GROUNDCONFIG'] = 0;
        }
        unset($item);

        $odd_items = [];
        $even_items = [];
        foreach($items as $item) {
            if ($item['TERMMULTIPLY'] == 1)
                $odd_items[] = $item;
            else
                $even_items[] = $item;
        }

        //Группируем элементы массива
        $ground_terms = $this->GroupArrayByKeys($ground_items, ['CELLCONFIG', 'GROUNDCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY', 'TERMFIRSTPART', 'TERMSECONDPART', 'J'], 'level');
        $even_terms = $this->GroupArrayByKeys($even_items, ['CELLCONFIG', 'GROUNDCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY', 'TERMFIRSTPART', 'TERMSECONDPART', 'J'], 'level');
        $odd_terms = $this->GroupArrayByKeys($odd_items, ['CELLCONFIG', 'GROUNDCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY', 'TERMFIRSTPART', 'TERMSECONDPART', 'J'], 'level');
        $ground_groups = $this->GroupArrayByKeys($ground_terms, ['CELLCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY'], 'group');
        $ground_atomiccores = $this->GroupArrayByKeys($ground_groups, ['CELLCONFIG', 'ATOMICCORE', 'TERMMULTIPLY'], 'term');
        $ground_columns = $this->GroupArrayByKeys($ground_atomiccores, ['CELLCONFIG', 'TERMMULTIPLY'], 'atomiccore');
        $odd_groups = $this->GroupArrayByKeys($odd_terms, ['CELLCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY'], 'group');
        $odd_atomiccores = $this->GroupArrayByKeys($odd_groups, ['CELLCONFIG', 'ATOMICCORE', 'TERMMULTIPLY'], 'term');
        $odd_columns = $this->GroupArrayByKeys($odd_atomiccores, ['CELLCONFIG', 'TERMMULTIPLY'], 'atomiccore');
        $even_groups = $this->GroupArrayByKeys($even_terms, ['CELLCONFIG', 'ATOMICCORE', 'TERMPREFIX', 'TERMMULTIPLY'], 'group');
        $even_atomiccores = $this->GroupArrayByKeys($even_groups, ['CELLCONFIG', 'ATOMICCORE', 'TERMMULTIPLY'], 'term');
        $even_columns = $this->GroupArrayByKeys($even_atomiccores, ['CELLCONFIG', 'TERMMULTIPLY'], 'atomiccore');

        foreach($even_columns as &$column) {
            foreach ($column['atomiccore'] as &$atomiccore) {
                foreach ($atomiccore['term'] as &$term) {
                    $term['group'] = array_reverse($term['group']);
                }
                unset($term);
                $atomiccore['term'] = array_reverse($atomiccore['term']);
            }
            unset($atomiccore);
            $column['atomiccore'] = array_reverse($column['atomiccore']);
        }
        unset($column);
        $even_columns = array_reverse($even_columns);

        if ($ground_columns[0]['TERMMULTIPLY'] == 1) {
            foreach ($ground_columns as &$column) {
                foreach ($column['atomiccore'] as &$atomiccore) {
                    foreach ($atomiccore['term'] as &$term) {
                        $term['group'] = array_reverse($term['group']);
                    }
                    unset($term);
                    $atomiccore['term'] = array_reverse($atomiccore['term']);
                }
                unset($atomiccore);
                $column['atomiccore'] = array_reverse($column['atomiccore']);
            }
            unset($column);
            $ground_columns = array_reverse($ground_columns);
        }

        $columns = array_merge($odd_columns, $ground_columns, $even_columns);
        return $columns;
    }


}