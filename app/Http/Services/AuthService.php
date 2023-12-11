<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class AuthService
{

    /**
     * Undocumented function
     *
     * @param [array] $requestedData
     * @return void
     */
    public static function validateRequest($requestedData)
    {
        
        $validator = Validator::make($requestedData, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        return [
            'message' => $validator->fails() ? $validator->messages()->first() : null,
            'status' => $validator->fails() ? false : true,
        ];
    }
}
