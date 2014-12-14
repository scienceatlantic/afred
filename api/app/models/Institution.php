<?php
class Institution extends Eloquent
{
    use Eloquence\Database\Traits\CamelCaseModel;
    
    public function facilities()
    {
        return $this->hasMany('Facility');
    }
    
    public function iloContact()
    {
        return $this->hasOne('IloContact');    
    }
}
