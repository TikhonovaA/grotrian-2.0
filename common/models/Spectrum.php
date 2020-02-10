<?php

namespace common\models;


class Spectrum
{
    static function wavelength2RGB($length){		//Функция генерирования RGB цвета по длине волны

        $gamma=1;
        $Violet=3800;
        $Blue=4400;
        $Cyan=4900;									//Опорные точки цвета
        $Green=5100;
        $Yellow=5800;
        $Orange=6450;
        $Red=7800;

        switch ($length) {
            case ($length >= $Violet AND $length < $Blue) :
                $RGB['R']=-($length - $Blue) / ($Blue - $Violet);
                $RGB['G']=0;
                $RGB['B']=1;
                break;

            case ($length >= $Blue AND $length < $Cyan) :
                $RGB['R']=0;
                $RGB['G']=($length - $Blue) / ($Cyan - $Blue);
                $RGB['B']=1;
                break;

            case ($length >= $Cyan AND $length < $Green) :
                $RGB['R']=0;
                $RGB['G']=1;
                $RGB['B']=-($length - $Green) / ($Green - $Cyan);
                break;

            case ($length >= $Green AND $length < $Yellow) :
                $RGB['R']=($length - $Green) / ($Yellow - $Green);
                $RGB['G']=1;
                $RGB['B']=0;
                break;

            case ($length >= $Yellow AND $length < $Orange) :
                $RGB['R']=1;
                $RGB['G']=-($length - $Orange) / ($Orange - $Yellow);
                $RGB['B']=0;
                break;

            case ($length >= $Orange AND $length < $Red):
                $RGB['R']=1;
                $RGB['G']=0;
                $RGB['B']=0;
                break;

            default:
                $RGB['R']=1;		//Белый цвет по умолчанию
                $RGB['G']=1;
                $RGB['B']=1;
        }

        switch ($length) {
            case ($length >= $Violet AND $length < $Blue) :
                $correction= 0.3 + 0.7*($length - $Violet) / ($Blue - $Violet);
                break;

            case ($length >= 4200 AND $length < 7000) :
                $correction=1;
                break;

            case ($length >= 7000 AND $length < $Red) :
                $correction=0.3 + 0.7*($Red - $length) / ($Red - 7000);
                break;

            default:
                $correction=1;
        }

        $correction *=255;

        $RGB['R']=intval($correction*$RGB['R'])*$gamma;
        $RGB['G']=intval($correction*$RGB['G']*$gamma);
        $RGB['B']=intval($correction*$RGB['B']*$gamma);

        return $RGB;
    }
}