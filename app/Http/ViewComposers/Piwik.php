<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class Piwik implements ViewComposer
{
    public function compose(View $view)
    {
        return $view
            ->with('url', config('piwik.url'))
            ->with('siteId', config('piwik.siteId'));
    }
}
