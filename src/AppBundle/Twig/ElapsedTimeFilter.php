<?php
/**
 * Created by PhpStorm.
 * User: allth
 * Date: 08/09/2017
 * Time: 16:12
 */

namespace AppBundle\Twig;


class ElapsedTimeFilter extends \Twig_Extension
{

    private $intervalFormat = [
        "y" => "an",
        "m" => "mois",
        "d" => "jour",
        "h" => "heure",
        "i" => "minute",
        "s" => "seconde"
    ];

    public function getName()
    {
        return "elapsedTimeFilter";
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter("elapsed", [$this, "elapsed"])
        ];
    }

    public function elapsed($date)
    {
        $now = new \DateTime();
        $interval = $now->diff($date);
        $format = "";
        $break = false;
        foreach ($this->intervalFormat as $key => $val) {
            $value = $interval->$key;
            if ($value > 0 && $key == "y") {
                if ($value > 1) {
                    $format .= "%{$key} {$val}s ";
                    $break = true;
                } else {
                    $format = "%{$key} {$val} ";
                    $break = true;
                }
            } else {
                if ($value > 0 && $key == "m" && !$break) {
                    $format = "%{$key} {$val} ";
                    $break = true;
                } else {
                    if ($value > 0 && $key == "d" && !$break) {
                        if ($value > 1) {
                            $format = "%{$key} {$val}s ";
                            $break = true;
                        } else {
                            $format = "%{$key} {$val} ";
                            $break = true;
                        }
                    } else {
                        if ($value > 0 && !$break) {
                            if ($value > 1) {
                                $format .= "%{$key} {$val}s ";
                            } else {
                                $format = "%{$key} {$val} ";
                            }
                        }
                    }
                }
            }
        }
        return $interval->format($format);
    }
}