<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Http\ViewComposers;


use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

class Base implements ViewComposer
{
    public function compose(View $view)
    {
        $view->with([
            'base' => $this->getDefaultTemplateVars()
        ]);
    }

    private function getDefaultTemplateVars(): array
    {
        return [
            'requestUrl' => Request::url()
        ];
    }
}
