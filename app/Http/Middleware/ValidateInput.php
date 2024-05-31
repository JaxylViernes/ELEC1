<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ValidateInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4']
        ];

        $messages = [
            'email.required' => 'We need to know your email!',
            'email.email' => 'Please follow the email format.',
            'password.required' => 'Please provide a password.',
            'password.min' => 'Minimum number of characters is 4.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return $next($request);
    }
}
