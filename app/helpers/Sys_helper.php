<?php

namespace App\Helpers;

class Sys_helper{

   public function replace_variables($commands, $variables) {
        foreach ($commands as &$command) {
            $command = str_replace(
                array_map(function ($var) { return "%%$var%%"; }, array_keys($variables)),
                array_values($variables),
                $command
            );
        }
        return $commands;
    }
}