<?php
/**
 * @copyright 2017
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Http\Controllers;


use Illuminate\Contracts\Session\Session;

class MiscController extends Controller
{
    public function setLocale(Session $session, $locale)
    {
        $session->put('user.locale', $locale);
        return redirect()->back();
    }
}