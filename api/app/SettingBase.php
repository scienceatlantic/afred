<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Carbon\Carbon;
use Log;

abstract class SettingBase extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
                        'dateUpdated'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Value attribute accessor.
     *
     * The value column is retrieved and is cast into the appropriate type based
     * on the 'type' column.
     */
    public function getValueAttribute($v)
    {
        $type = $this->type;

        if ($v === null && $type !== 'TEXT' && $type !== 'JSONTEXT') {
            return null;
        }

        switch ($type) {
            case 'BOOLEAN':
                return (boolean) $v;
            case 'INT':
                return (integer) $v;
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
                return ($v = $this->text()->first()) ? $v->value : null;
            case 'EMAIL':
                // No break.
            case 'URL':
                // No break.
            case 'STRING':
                return $v;
            case 'DATE':
                // No break.
            case 'DATETIME':
                return Carbon::parse($v);
        }        
    }

    /**
     * Updates the `value` column of a setting (and the `dateUpdated` column).
     */
    public function updateValue($v)
    {
        $type = $this->type;

        if ($v !== null) {
            switch ($type) {
                case 'BOOLEAN':
                    self::isType($v, ['boolean', 1, 0, 'NULL']);
                    $this->value = (boolean) $v;
                    break;
                case 'INT':
                    self::isType($v, ['integer', 'NULL']);
                    $this->value = (integer) $v;
                    break;
                case 'DOUBLE':
                    self::isType($v, ['integer', 'double', 'NULL']);
                    $this->value = (double) $v;
                    break;
                case 'DATE':
                    try {
                        $this->value = Carbon::parse($v)->toDateString();
                    } catch (\Exception $e) {
                        Log::error('`$v` is not a valid date string', [
                            'name'               => $this->name,
                            'value'              => $v,
                            'carbonErrorMessage' => $e->getMessage()
                        ]);
                        abort(500);
                    }
                    break;
                case 'DATETIME':
                    try {
                        $this->value = Carbon::parse($v)->toDateTimeString();
                    } catch (\Exception $e) {
                        Log::error('`$v` is not a valid datetime string', [
                            'name'               => $this->name,
                            'value'              => $v,
                            'carbonErrorMessage' => $e->getMessage()
                        ]);
                        abort(500);
                    }
                    break;
                case 'EMAIL':
                    if (!filter_var($v, FILTER_VALIDATE_EMAIL)) {
                        Log::error('`$v` is not a valid email', [
                            'name'  => $this->name,
                            'value' => $v
                        ]);
                        abort(500);
                    }
                    $this->value = $v;
                    break;
                case 'URL':
                    if (!filter_var($v, FILTER_VALIDATE_URL)) {
                        Log::error('`$v` is not valid URL', [
                            'name'  => $this->name,
                            'value' => $v
                        ]);
                        abort(500);                        
                    }
                    $this->value = $v;
                    break;
                case 'STRING':
                    $this->value = $v;
                    break;
                case 'JSON':
                    if (($v = json_encode($v)) === false || !self::isJson($v)) {
                        Log::error('`$v` is not valid JSON', [
                            'name'  => $this->name,
                            'value' => $v
                        ]);
                        abort(500);
                    }
                    $this->value = $v;
                    break;
                case 'TEXT':
                    $text = $this->text()->first() ?: $this->text()->create([]);
                    $text->value = $v;
                    $text->save();
                    $this->value = null;
                    break;
                case 'JSONTEXT':
                    if (($v = json_encode($v)) === false || !self::isJson($v)) {
                        Log::error('`$v` is not valid JSON', [
                            'name'  => $this->name,
                            'value' => $v
                        ]);
                        abort(500);
                    }
                    $text = $this->text()->first() ?: $this->text()->create([]);
                    $text->value = $v;
                    $text->save();
                    $this->value = null;
                    break;
            }
        } else {
            $this->value = null;
            if ($text = $this->text()->first()) {
                $text->value = null;
                $text->update();
            }
        }

        $this->dateUpdated = Carbon::now();
        $this->update();
    }

    /**
     *  Similar to `find()` except search is based on the `name` column.
     */
    public static function findByName($name, $query = null)
    {
        $query = $query ?: self::query();
        return $query->where('name', $name)->first();
    }

    /**
     * Retrieves a particular setting's value.
     * 
     * Note: In an attempt to minimise resource usage, this function only runs
     * a single database query, but as a consequence multiple loops. No testing
     * was done to see if this approach is better than multiple queries and just
     * one loop.
     *
     * @uses SettingBase::isEmpty() Check if a value is empty (empty string or
     *     null).
     *
     * @uses SettingBase::logDefaultFound() To log (warning) any values that 
     *     were not found (or found but is empty) but a non-null default was 
     *     given.
     *
     * @uses SettingBase::logErrorNotFound() To log (error) a value that was not
     *     found (or is empty) and no default was provided. Will abort an 
     *     HTTP 500.
     * 
     * @param string|array $name What is returned depends on whether a string
     *     or an array is given:
     *     (1) String:
     *     Value of setting or default (if provided).
     *
     *     (2) Array:
     *     Numerically or associatively indexed array.
     *
     *
     *     Example where a single setting is retrieved and no default is given:
     *     $appAddress = Setting::lookup('appAddress');
     *
     *
     *     Example where a single setting is retrieved and a default is given:
     *     $appAddress = Setting::lookup('appAddress', 'http://localhost/');
     *     
     *
     *     Example where a few settings are retrieved at once with defaults:
     *     list($address, $name, $shortName) = Setting::lookup([
     *         'appAddress',
     *         'appName'      => 'Atlantic Facilities',
     *         'appShortName' => 'AF'
     *     ]);
     *
     *
     *     Example where a few settings are retrieved at once and a single
     *     default value is provided.
     *     list($name, $shortName) = Setting::lookup([
     *         'appName',
     *         'appShortName',  
     *     ], 'AFRED');
     *
     *
     *     Example where a few settings are retrieved at once and we want it 
     *     returned as an associative array:
     *     $settings = Setting::lookup([
     *         'appName',
     *         'appShortName',  
     *     ], null, true);
     *     
     *     Retrieve values like this:
     *     $settings['appName']
     *
     *     
     *     Example where a few settings are retrieved at once and we want it 
     *     returned as an associative array with array keys specified:
     *     $settings = Setting::lookup([
     *         'appName',
     *         'appShortName',  
     *     ], null, [
     *         'name',
     *         'shortName'   
     *     ]);
     *
     *     Retrieve values like this:
     *     $settings['name']
     *
     *
     * @param mixed $default If the setting value was not found or is empty, it
     *     will be replaced with this. The 'null' value (which is the default)
     *     signifies that we don't have a default (signifying that a non-empty 
     *     must exist in the database).
     *
     * @param array|boolean $keys Default is false = return as numerically
     *     indexed array, true = return as associative array, array = return
     *     as associative array with keys specified.
     * 
     * @return mixed See the examples above of what is returned. When a value
     *     is not found (or is empty) and a $default is provided, it will be
     *     logged with a warning. If a value is not found (or is empty) and a
     *     $default is not provided or is null, the method will abort with an
     *     HTTP 500 (via `SettingBase::logErrorNotFound()`).
     */
    public static function lookup($name, $default = null, $keys = false,
        $query = null)
    {
        $query = $query ?: self::query();

        // Convert to array if not already an array.
        $names = is_array($name) ? $name : [$name];

        // Retrieve values.
        $i = 0;
        foreach($names as $k => $v) {
            $method = $i++ === 0 ? 'where' : 'orWhere';
            $query->$method('name', is_string($k) ? $k : $v);
        }

        // Index retrieved values by name.
        $queriedValues = $query->get()->pluck('value', 'name');

        // Process the retrieved values.
        $i = 0;
        $values = [];
        foreach($names as $k => $v) {
            // Get name and default values (if provided as assoc array or second
            // argument to method).
            list($n, $d) = is_string($k) ? [$k, $v] : [$v, $default];

            // Index values associatively, associatively using
            // aliases, or numerically.
            $key = is_array($keys) ? $keys[$i] : ($keys ? $n : $i);
            
            // Value found and is not empty.
            if ($queriedValues->has($n) && !self::isEmpty($queriedValues[$n])) {
                $values[$key] = $queriedValues[$n];
            } 
            // Not found or is empty, but default provided.
            else if ($d !== null) {
                self::logDefaultFound($n, $d);
                $values[$key] = $d;
            }
            // Not found and default not provided.
            else {
                self::logErrorNotFound($n);
            }

            $i++; // Advance loop index.
        }

        return is_array($name) ? $values : array_pop($values);
    }
    
    private static function logDefaultFound($name, $default) 
    {
        Log::warning('Setting not found, using default instead.', [
            'name'    => $name,
            'default' => $default
        ]);
    }

    private static function logErrorNotFound($name)
    {
        Log::error('Setting not found. Aborting!', [
            'name' => $name
        ]);
        abort(500);
    }

    private static function isEmpty($value)
    {
        return ($value === "" || $value === null);
    }

    private static function isType($v, $types)
    {
        $str = ' ';
        $vType = gettype($v);
        $len = count($types);
        foreach($types as $index => $type) {
            if ($vType === $type || $v === $type) {
                return true;
            }
            $str .= '"' . ($vType === $type ? $vType : $type) . '", ';
            $str .= $index === ($len - 2) ? 'or ' : '';
        }
        Log::error('`$v` is not of type' . rtrim($str, ', '));
        abort(500);
    }

    private function isJson($str)
    {
        return is_string($str) 
            && is_array(json_decode($str, true)) 
            && (json_last_error() === JSON_ERROR_NONE);
    }
}
