<?php

namespace App\Traits;

trait MessagesException
{
    /**
     * parser exceptions.
     * @param  $exception
     * @return string
     */
    public function parseException($exception, $attributes): string
    {
        $code = $exception->getCode();

        if ($code == "-1") {
            return $exception->getMessage();
        }

        if ($exception->getPrevious()) {
            $error_mensaje = $exception->getPrevious()->getMessage();
        } else {
            $error_mensaje = $exception->getMessage();
        }

        $failData = null;
        $failKey = null;
        collect($attributes)->map(function ($attribute, $key) use (&$failData, &$failKey, $error_mensaje) {
            try {
                echo $error_mensaje.":::".$attribute;
                echo "<br/>";
                if (strpos($error_mensaje, "'" . ($attribute)) || strpos($error_mensaje, '"' . ($attribute))) {
                    $failData = $attribute;
                    $failKey = $key;
                }
            } catch (\Throwable $th) {
            }
        });
        switch ($code) {
            case "23000":
                return $failKey." ya existe (" . $failData . ")";
        }
        return $error_mensaje;
    }
}
