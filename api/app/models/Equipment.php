<?php
class Equipment extends Eloquent
{
    use Eloquence\Database\Traits\CamelCaseModel;
    
    public function facility()
    {
        return $this->belongsTo('Facility');
    }
}