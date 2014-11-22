<?php
class Facility extends Eloquent
{
    use Eloquence\Database\Traits\CamelCaseModel;
    
    public function contacts()
    {
        return $this->hasMany('Contact');
    }
    
    public function equipment()
    {
        return $this->hasMany('Equipment');
    }
}
