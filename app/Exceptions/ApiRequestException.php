<?php
 
namespace App\Exceptions;
 
use Exception;
use Illuminate\Support\MessageBag;
 
class ApiRequestException extends Exception
{

    protected MessageBag $messages;

    public function __construct(MessageBag $messages)
    {
        $this->messages = $messages;
    }

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
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        
        return response()->json(['error' => $this->messages->all()[0]], 422);
    }
}