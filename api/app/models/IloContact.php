<?php
class IloContact extends Eloquent
{
    use Eloquence\Database\Traits\CamelCaseModel;
    
    public function institution()
    {
        return $this->belongsTo('Institution');
    }
}