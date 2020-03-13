<?php
/**
 * Created by PhpStorm.
 * User: atikh
 * Date: 13.03.2020
 * Time: 11:08
 */

namespace common\models;
use common\models\Transition;
use yii\helpers\ArrayHelper;

class TransitionList
{
    var $items;

    public function GetItemsArray()
    {
        return $this->items;
    }

    public function LoadWithLevels($element_id)
    {
        $result = Transition::find()
            ->with('lowerLevel', 'upperLevel')
            ->where(['ID_ATOM' => $element_id])
            ->orderBy(['WAVELENGTH' => SORT_ASC])
            ->asArray()
            ->all();

        $this->items = $result;

    }

    public function LoadForDiagram($element_id){
        $this->LoadWithLevels($element_id);
        $items = $this->GetItemsArray();

        foreach ($items as &$item) {
            if ($item['lowerLevel']['TERMPREFIX'] != $item['upperLevel']['TERMPREFIX'] //and it must be LS-coupling!
                && is_string($item['lowerLevel']['TERMFIRSTPART']) && is_string($item['upperLevel']['TERMFIRSTPART'])
                && strlen($item['lowerLevel']['TERMFIRSTPART']) > 0 && strlen($item['upperLevel']['TERMFIRSTPART']) > 0
                && $item['lowerLevel']['TERMFIRSTPART'][0] >='A' && $item['lowerLevel']['TERMFIRSTPART'][0] <='Z'
                && $item['upperLevel']['TERMFIRSTPART'][0] >='A' && $item['upperLevel']['TERMFIRSTPART'][0] <='Z'
            )
                $item['prohibited'] = 'multiplicity';
            if ($item['lowerLevel']['TERMMULTIPLY'] == $item['upperLevel']['TERMMULTIPLY']) $item['prohibited'] = 'parity';

            if ($item['WAVELENGTH']>=4000 && $item['WAVELENGTH'] <=8000)
                $item['rating'] = 3;
            elseif ($item['WAVELENGTH']>8000)
                $item['rating'] = 2;
            else
                $item['rating'] = 1;
            if ($item['lowerLevel']['ENERGY'] == 0
//            &&  $item['upper_level_energy'] < $atom_data['IONIZATION_POTENCIAL']
            )
                $item['rating'] += 4;

            foreach($items as $item2)
                if ($item['lowerLevel']['ENERGY'] === $item2['upperLevel']['ENERGY']) {
                    $item['rating']++;
                    break;
                }
        }
        unset($item);
        usort($items, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });
        return $items;
    }
}