<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use App\Area;

class CategoriasComposer
{
	public function compose(View $view)
	{
        $areas = Area::ordered()->get();

		$view->with('areas', $areas);
	}
}
