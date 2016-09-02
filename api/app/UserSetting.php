<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use DB;
use Log;

class UserSetting extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relationship between a user setting and the user it belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'userId');
    }

    /**
     * Value attribute accessor.
     *
     * The value column is retrieved and is cast into the appropriate type based
     * on the 'type' column.
     */
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
                $v = DB::table('user_settings_text')
                    ->where('userSettingId', $this->id)->first()->value;
                // No break.
            case 'JSON':
                return json_decode($v, true);
            case 'TEXT':
                return DB::table('user_settings_text')
                    ->where('userSettingId', $this->id)->first()->value;
            case 'EMAIL':
            case 'URL':
            case 'DATE':
            case 'DATETIME':
            case 'STRING':
                return $v;
        }        
    }

    /**
     * Functionally identical to the similarly named method in the 'Setting'
     * model except that a '$userId' is required.
     *
     * @see Setting::lookup()
     */
    public function scopeLookup($query, $userId, $name, $default = null)
    {
        // The 'where' closure below will attach its return value to this.
        $value = null;

        // Look for the user first.
        $query->where('userId', $userId);
        
        // Then look for the settings.
        $query->where(function($query) use ($name, $default, &$value) {
            // Get an array of settings values.
            if (is_array($name)) {
                // Grab the data from the database.
                foreach($name as $k => $v) {
                    $query->orWhere('name', is_string($k) ? $k : $v);
                }
                $query = $query->get()->pluck('value', 'name');

                // Process the retrieved values.
                $value = [];
                $index = 0;
                foreach($name as $k => $v) {
                    // Get the 'name' of the setting
                    $n = is_string($k) ? $k : $v;

                    // Get the default value.
                    $d = is_array($default) ? $default[$index++] : $default;
                    
                    // Value found.
                    if ($query->has($n)) {
                        $value[$v] = $query[$n];
                    } 
                    // Not found, but default provided.
                    else if ($d !== null) {
                        UserSetting::logNotice($n, $d);
                        $value[$v] = $d;
                    } 
                    // Not found and default not provided.
                    else {
                        UserSetting::logError($n);
                    }
                }
            } 
            // Get just a single setting value.
            else {
                // Value found.
                if ($query = $query->where('name', $name)->first()) {
                    $value = $query->value;
                } 
                // Not found, but default provided.
                else if ($default !== null) {
                    UserSetting::logNotice($name, $default);
                    $value = $default;
                } 
                // Not found and default not provided.
                else {
                    UserSetting::logError($name);
                }
            }
        });
        return $value;
    }

    private static function logNotice($name, $default) 
    {
        Log::notice('Setting not found, using default instead.', [
            'name'    => $name,
            'default' => $default
        ]);
    }

    private static function logError($name)
    {
        Log::error('Setting not found. Aborting!', [
            'name' => $name
        ]);
        abort(500);
    }
}
