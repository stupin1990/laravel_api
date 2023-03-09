<?php
 
namespace App\Exceptions;
 
use Exception;
 
class OAuth2TokenException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json(['error' => $this->message], 401);
    }
}