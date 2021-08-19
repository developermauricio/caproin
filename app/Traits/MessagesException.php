<?php

namespace App\Traits;
use Illuminate\Database\QueryException;

trait MessagesException
{
    /**
     * parser exceptions.
     * @param Illuminate\Database\QueryException $exception
     * @return string
     */
    public function parseException(QueryException $exception): string
    {
        $attributes = collect($exception->getBindings());
        $code = $exception->getCode();
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
