<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Mobile extends Component
{
    /**
     * Create a new component instance.
     */
    public $divClass;
    public $label;
    public $name;
    public $placeholder;
    public $class;
    public $value;
    public $id;
    public $required;

    public function __construct($divClass='col-md-12',$name="mobile_no",$class='form-control',$label=null, $placeholder=null ,$value=null,$id=null,$required= false)
    {
        $this->divClass = $divClass;
        $this->label = $label ?? __('Mobile No');
        $this->name = $name;
        $this->placeholder = $placeholder ?? __('Enter Mobile No');
        $this->class = $class;
        $this->value = $value;
        $this->id = $id;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mobile');
    }
}
