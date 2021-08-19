<?php

namespace App\Traits;

trait MessagesException
{
    /**
     * parser exceptions.
     * @param  $exception
     * @return string
     */
    public function parseException($exception): string
    {
        $code = $exception->getCode();

        if ($code == "-1") {
            return $exception->getMessage();
        }

        $attributes = collect($exception->getBindings());
        $error_mensaje = $exception->getPrevious()->getMessage();
        $failData = null;
        $attributes->map(function ($attribute) use (&$failData, $error_mensaje) {
            if (strpos($error_mensaje, "".($attribute))) {
                $failData = $attribute;
            }
        });

        switch($code) {
            case "23000":
                return "(".$failData.") ya se encuentra registrado";
        }
        return $error_mensaje;
    }
}
