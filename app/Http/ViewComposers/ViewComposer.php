<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Http\ViewComposers;


use Illuminate\Contracts\View\View;

interface ViewComposer
{
    public function compose(View $view);
}
