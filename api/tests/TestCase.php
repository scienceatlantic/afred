<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    // Do not set below 5.
    const DEF_NUM_CONTACTS_IN_FR_DATA_ATTR = 5;
    const DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR = 5;
    const DEF_NUM_EQUIPMENT_IN_FR_DATA_ATTR = 5;
    const DEF_NUM_SECTORS_IN_FR_DATA_ATTR = 5;

    const DEF_NUM_FACILITIES_IN_FR_DATA_ATTR = 1;
    const DEF_NUM_PRIMARY_CONTACTS_IN_FR_DATA_ATTR = 1;

    const MIN_NUM_CONTACTS_IN_FR_DATA_ATTR = 0;
    const MAX_NUM_CONTACTS_IN_FR_DATA_ATTR = 10;
    const MIN_NUM_EQUIPMENT_IN_FR_DATA_ATTR = 1;
    const MAX_NUM_EQUIPMENT_IN_FR_DATA_ATTR = 50;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected static function getSuperAdmin($attr = [])
    {
        $role = App\Role::where('name', 'SUPER_ADMIN')->first();
        $user = factory(App\User::class, 'withPasswordAndDates')->create($attr);
        $user->roles()->attach([$role->id]);
        return $user;
    }

    protected static function getAdmin($attr = [])
    {
        $role = App\Role::where('name', 'ADMIN')->first();
        $user = factory(App\User::class, 'withPasswordAndDates')->create($attr);
        $user->roles()->attach([$role->id]);
        return $user;
    }

    protected function getPendingApprovalFr($outputType = 'stdClass',
        $dataAttr = null)
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => $dataAttr ?: self::createFrDataAttr()
        ])->toArray();
        
        $resp = $this->actingAs($this->getAdmin())
                     ->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        Auth::logout();
        
        return self::outputFr($resp, $outputType);
    }

    protected function getPublishedFr($outputType = 'stdClass', 
        $dataAttr = null)
    {
        $fr = $this->getPendingApprovalFr('stdClass', $dataAttr);
        $params = $fr->id . '?state=PUBLISHED';

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        Auth::logout();

        return self::outputFr($resp, $outputType);
    }

    protected function getRejectedFr($outputType = 'stdClass', 
        $dataAttr = null)
    {
        $fr = $this->getPendingApprovalFr('stdClass', $dataAttr);
        $params = $fr->id . '?state=REJECTED';

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        Auth::logout();

        return self::outputFr($resp, $outputType);
    }

    protected function getPendingEditApprovalFr($outputType = 'stdClass', 
        $dataAttr = null, $dataAttrAfter = null)
    {
        $updateRequest = $this->getOpenFul('model');
        $oriFr = $updateRequest->originalFr;
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['id'] = $oriFr->data['facility']['id'];
        $params = $oriFr->id . '?token=' . $updateRequest->token;

        $resp = $this->put('/facility-repository/' . $params, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();

        return self::outputFr($resp, $outputType);        
    }

    protected function getOpenFul($outputType = 'stdClass', 
        $contactType = 'primary', $dataAttr = null)
    {
        $fr = $this->getPublishedFr('model', $dataAttr);
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $fr->publishedFacility->primaryContact->email,
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $ful = json_decode($resp, true);

        switch ($outputType) {
            case 'array':
                return $ful;
            case 'model':
                return App\FacilityUpdateLink::find($ful['id']);
            case 'stdClass':
            default:
                return (object) $ful;
        }
    }

    protected static function createFrDataAttr(
        $numContacts = self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
        $numDisciplines = self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
        $numEquipment = self::DEF_NUM_EQUIPMENT_IN_FR_DATA_ATTR, 
        $numFacilities = self::DEF_NUM_FACILITIES_IN_FR_DATA_ATTR, 
        $numPrimaryContacts = self::DEF_NUM_PRIMARY_CONTACTS_IN_FR_DATA_ATTR, 
        $numSectors = self::DEF_NUM_SECTORS_IN_FR_DATA_ATTR)
    {
        $data = [];

        if ($numContacts == 1) {
            $data['contacts'] = [
                factory(App\Contact::class, $numContacts)->make()->toArray()
            ];
        }
        else if ($numContacts) {
            $data['contacts'] = factory(App\Contact::class, $numContacts)->make()
                ->toArray();
        }
        if ($numDisciplines) {
            $data['disciplines'] = factory(App\Discipline::class, 'withDates', 
                $numDisciplines)->create()->pluck('id')->toArray();         
        }
        if ($numEquipment == 1) {
            $data['equipment'] = [
                factory(App\Equipment::class, $numEquipment)->make()->toArray()
            ];
        }
        else if ($numEquipment) {
            $data['equipment'] = factory(App\Equipment::class, $numEquipment)
                ->make()->toArray();
        }
        if ($numFacilities) {
            $data['facility'] = factory(App\Facility::class, $numFacilities)
                ->make([
                    'organizationId' => factory(App\Organization::class,
                        'withDates')->create()->id,
                    'provinceId' => factory(App\Province::class,
                        'withDates')->create()->id
                ]
            )->toArray();
        }
        if ($numPrimaryContacts) {
            $data['primaryContact'] = factory(App\PrimaryContact::class, 
                $numPrimaryContacts)->make()->toArray();
        }
        if ($numSectors) {
            $data['sectors'] = factory(App\Sector::class, 'withDates', 
                $numSectors)->create()->pluck('id')->toArray();
        }

        return $data;
    }

    private static function outputFr($jsonFr, $outputType = 'stdClass')
    {
        $fr = json_decode($jsonFr, true);
        switch ($outputType) {
            case 'array':
                return $fr;
            case 'model':
                return App\FacilityRepository::find($fr['id']);
            case 'stdClass':
            default:
                return (object) $fr;
        }
    }

    protected function seeInTable($table, $columns, $columnsToIgnore = [], 
        $columnsToAdd = [], $removeNullValuedColumns = true, $inverse = false)
    {
        // If non-array provided, try converting to array.
        if (!is_array($columns)) {
            $columns = $columns->toArray();
        }

        // Indexed array check.
        if (array_key_exists(0, $columns)) {
            foreach($columns as $column) {
                $this->seeInTable($table, $column, $columnsToIgnore, 
                    $columnsToAdd, $removeNullValuedColumns, $inverse);
            }
            return;
        }

        // Remove null valued columns.
        if ($removeNullValuedColumns) {
            $columns = $this->unnullify($columns);
        }

        // Make sure all array keys are camel cased.
        $columns = $this->toCamelCaseArr($columns);
        
        // Remove columns that should me ignored.
        $columns = array_except($columns, $columnsToIgnore);

        // Merge columns that should be added.
        $columns = array_merge($columns, $columnsToAdd);

        // Finally, assert with the database.
        if ($inverse) {
            $this->notSeeInDatabase($table, $columns);
        } else {
            $this->seeInDatabase($table, $columns);
        }
    }

    protected function notSeeInTable($table, $columns, $columnsToIgnore = [], 
        $columnsToAdd = [], $removeNullValuedColumns = true) 
    {
        $this->seeInTable($table, $columns, $columnsToIgnore, $columnsToAdd, 
            $removeNullValuedColumns, true);
    }

    protected function seeInBridgeTable($table, $leftValues, $rightValues, 
        $leftColumn = null, $rightColumn = null, $inverse = false) 
    {
        // Create default left column key from table name.
        if (!$leftColumn) {
            $leftColumn = explode('_', $table)[0] . 'Id';
        }

        // Create default right column key from table name.
        if (!$rightColumn) {
            $rightColumn = explode('_', $table)[1] . 'Id';
        }

        // Attempt to convert the values into an array.
        // 1st method - calling 'toArray()' method if object.
        if (is_object($leftValues)) {
            $leftValues = $leftValues->toArray();
        }
        if (is_object($rightValues)) {
            $rightValues = $rightValues->toArray();
        } 
        // 2nd method - push values directly into array.
        if (!is_array($leftValues)) {
            $leftValues = [$leftValues];
        }
        if (!is_array($rightValues)) {
            $rightValues = [$rightValues];
        }

        // Finally, assert with database.
        foreach($leftValues as $leftValue) {
            foreach($rightValues as $rightValue) {
                $row = [$leftColumn => $leftValue, $rightColumn => $rightValue];

                if ($inverse) {
                    $this->notSeeInDatabase($table, $row);
                } else {
                    $this->seeInDatabase($table, $row);
                }
            }
        }
    }

    protected function notSeeInBridgeTable($table, $leftValues, $rightValues, 
        $leftColumn = null, $rightColumn = null) 
    {
        $this->seeInBridgeTable($table, $leftValues, $rightValues, $leftColumn, 
            $rightColumn, true);
    }

    protected function seeInArray($array1, $array2, 
        $keysToIgnoreFromArray1 = [], $keysToAddToArray2 = [], 
        $removeNullValuedKeysFromArray2 = true)
    {
        //Indexed array check.
        if (array_key_exists(0, $array1) && array_key_exists(0, $array2)) {
            $this->assertEquals(count($array1), count($array2));
            foreach($array1 as $i => $val) {
                $this->seeInArray($array1[$i], $array2[$i], 
                    $keysToIgnoreFromArray1, $keysToAddToArray2, 
                    $removeNullValuedKeysFromArray2);
            }
            return;
        } else if (array_key_exists(0, $array1)) {
            foreach($array1 as $arr) {
                $this->seeInArray($arr, $array2, $keysToIgnoreFromArray1, 
                    $keysToAddToArray2, $removeNullValuedKeysFromArray2);
            }
            return;
        } else if (array_key_exists(0, $array2)) {
            foreach($array2 as $arr) {
                $this->seeInArray($array1, $arr, $keysToIgnoreFromArray1, 
                    $keysToAddToArray2, $removeNullValuedKeysFromArray2);
            }
            return;
        }

        // Remove null valued keys.
        if ($removeNullValuedKeysFromArray2) {
            $array2 = $this->unnullify($array2);
        }
        
        // Remove keys that should be ignored.
        $array2 = array_except($array2, $keysToIgnoreFromArray1);

        // Merge keys that should be added.
        $array2 = array_merge($array2, $keysToAddToArray2);
        
        // Assert array1 and array2.
        foreach($array2 as $key => $val) {
            $this->assertArrayHasKey($key, $array1);
            $this->assertEquals($array1[$key], $array2[$key]);
        }
    }

    protected function unnullify($arr, $strict = true, $deep = false)
    {
        return array_where($arr, function($key, $value) use ($strict, $deep) {
            if (is_array($value) && $deep) {
                return $this->unnullify($value, $strict, $deep);
            } else {
                if ($strict) {
                    return $value !== null;
                } else {
                    return !is_empty($value);
                }
            }
        });
    }

    protected function toCamelCaseArr($arr)
    {
        $camelCaseKeyedArray = [];
        foreach($arr as $key => $value) {
            $camelCaseKeyedArray[camel_case($key)] = $value;
        }
        return $camelCaseKeyedArray;
    }
}
