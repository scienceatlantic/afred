<?php
class Contact extends Eloquent
{
    use Eloquence\Database\Traits\CamelCaseModel;
    
    public function facilities()
    {
        return $this->belongsTo('Facility');
    }
}