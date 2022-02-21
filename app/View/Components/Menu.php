<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class Menu extends Component
{

    public $menu;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menu = Cache::store('array')->rememberForever('menu', function () {
            return Category::all()->map(function ($value) {
                $value->is_active = request()->is($value->slug, $value->slug.'/*');
                $value->route = route('category.show', ['category'=>$value->slug], false);
                return $value;
            });
        });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu');
    }
}
