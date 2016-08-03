<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Carbon\Carbon;
use  DB;

class Setting extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
                        'dateUpdated'];

    public function getValueAttribute($v)
    {
        switch ($this->type) {
            case 'BOOLEAN':
                return (bool) $v;
            case 'INT':
                return (int) $v;
            case 'DOUBLE':
                return (double) $v;
            case 'JSONTEXT':
                $v = DB::table('settings_text')->where('settingId', $this->id)
                    ->value('value');
                // No break.
            case 'JSON':
                return json_decode($v, true);
            case 'TEXT':
                return DB::table('settings_text')->where('settingId', $this->id)
                    ->value('value');
            case 'EMAIL':
            case 'URL':
            case 'DATE':
            case 'DATETIME':
            case 'STRING':
                return $v;
        }        
    }

    public function scopeLookup($query, $name)
    {
        if (is_array($name)) {
            $len = count($name);
            $query->where('name', $name[0]);
            for ($i = 1; $i < $len; $i++) {
                $query->orWhere('name', $name[$i]);
            }
            if ($query->count() == $len) {
                return $query->get()->pluck('value', 'name');
            }
        } else {
            if ($query = $query->where('name', $name)->first()) {
                return $query->value;
            }
        }
        abort(500);
    }
}
