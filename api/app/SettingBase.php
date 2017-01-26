<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Log;

class SettingBase extends Model
{
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
                if ($v = $this->text()->first()) {
                    return json_decode($v->value, true);
                }
                return null;
            case 'JSON':
                return json_decode($v, true);
            case 'TEXT':
                if ($v = $this->text()->first()) {
                    return $v->value;
                }
                return null;
            case 'EMAIL':
            case 'URL':
            case 'DATE':
            case 'DATETIME':
            case 'STRING':
                return $v;
        }        
    }

    /**
     * Retrieves a particular setting's value.
     * 
     * Note: In an attempt to minimise resource usage, this function only runs
     * a single database query but as a consequence, multiple loops. No testing
     * was done to see if this approach is better than multiple queries and just
     * one loop.
     *
     * @uses SettingBase::logWarning() To log (warning) any values that were not 
     *     found but non-null default was given.
     *
     * @uses SettingBase::logError() To log (error) a value that was not found and 
     *     abort with HTTP 500.
     * 
     * @param string|array $name What is returned depends on whether a string
     *     or an array is given:
     *     (1) String:
     *     Name of setting.
     *
     *     (2) Array:
     *     An array of setting names.
     *
     *     Example where a single setting is retrieved and no default is given:
     *     $appAddress = Setting::lookup('appAddress');
     *
     *     Example where a single setting is retrieved and a default is given:
     *     $appAddress = Setting::lookup('appAddress', 'http://localhost/');
     *     
     *     Example where a few settings are retrieved at once and 'appName' and
     *     'appShortName' are given aliases:
     *     $someVar = Setting::lookup([
     *         'appAddress',
     *         'appName'      => 'name',
     *         'appShortName' => 'shortName'  
     *     ]);
     *     
     *     They can be accessed like this:
     *     $someVar['appAddress']
     *     $someVar['name']
     *     $someVar['shortName']
     *
     *     Example where a few settings are retrieved at once and a default is 
     *     given. That means that any value that was not found will be replaced
     *     with the default:
     *     $someVar = Setting::lookup([
     *         'appName'      => 'name',
     *         'appShortName' => 'shortName'  
     *     ], 'AFRED');
     *
     *     Example where a few settings are retrieved at once and an array of 
     *     defaults are provided where each element of the array corresponds 
     *     with each element of the '$name' array. A null value can be given
     *     to signify that that particular setting must be found.  
     *     $someVar = Setting::lookup([
     *         'appAddress'
     *         'appName',
     *         'appShortName',
     *     ], [
     *         null,
     *         'Atlantic Facilities and Research Equipment Database',
     *         'AFRED'
     *     ]);
     * @param mixed $default If the setting value was not found, it will be
     *     replaced with this. The 'null' value (which is the default) signifies
     *     that we don't have a default (the value must exist in the database).
     * 
     * @return mixed See the examples above of what is returned. When a value
     *     is not found and a $default is provided, it will be logged with a 
     *     warning. If a value is not found and a $default is not provided or 
     *     is null, the method will abort with an HTTP 500.
     */
    public function scopeLookup($query, $name, $default = null)
    {
        // Get an array of settings values.
        if (is_array($name)) {
            // Grab the data from the database.
            $count = 0;
            foreach($name as $k => $v) {
                if (++$count === 1) {
                    $query->where('name', is_string($k) ? $k : $v);
                    continue;
                }
                $query->orWhere('name', is_string($k) ? $k : $v);
            }
            $query = $query->get()->pluck('value', 'name');

            // Process the retrieved values.
            $values = [];
            $index = 0;
            foreach($name as $k => $v) {
                // Get the 'name' of the setting
                $n = is_string($k) ? $k : $v;

                // Get the default value.
                $d = is_array($default) ? $default[$index++] : $default;
                
                // Value found.
                if ($query->has($n)) {
                    $values[$v] = $query[$n];
                } 
                // Not found, but default provided.
                else if ($d !== null) {
                    SettingBase::logWarning($n, $d);
                    $values[$v] = $d;
                } 
                // Not found and default not provided.
                else {
                    SettingBase::logError($n);
                }
            }
            return $values;
        } 
        // Get just a single setting value.
        else {
            // Value found.
            if ($query = $query->where('name', $name)->first()) {
                return $query->value;
            } 
            // Not found, but default provided.
            else if ($default !== null) {
                SettingBase::logWarning($name, $default);
                return $default;
            } 
            // Not found and default not provided.
            else {
                SettingBase::logError($name);
            }
        }
    }
    
    private static function logWarning($name, $default) 
    {
        Log::warning('Setting not found, using default instead.', [
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
