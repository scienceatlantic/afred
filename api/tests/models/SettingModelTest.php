<?php

use App\Setting;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SettingModelTest extends TestCase
{
    use DatabaseTransactions;

    private $faker;
    private $types;
    private $int;
    private $boolean;
    private $double;
    private $date;
    private $datetime;
    private $email;
    private $url;
    private $string;
    private $text;
    private $json;
    private $jsontext;

    function __construct()
    {
        $this->faker = Faker::create();

        $this->types = ['int','boolean','double','date','datetime','email',
                        'url', 'string','text','json','jsontext'];
        
        $this->int = [
            'valid' => [
                null,
                rand(0, 5000),
                rand(0, 5000) * -1
            ],
            'invalid' => [
                str_random(10),
                rand(1, 5000) / 0.4546,
                rand(1, 5000) / 0.4546 * -1,               
            ]
        ];
        $this->boolean = [
            'valid' => [
                null,
                true,
                false,
                1,
                0
            ],
            'invalid' => [
                str_random(10),
                rand(2, 5000)
            ]
        ];
        $this->double = [
            'valid' => [
                null,
                rand(0, 5000),
                rand(1, 5000) / 0.4546,
                rand(1, 5000) / 0.4546 * -1,
                rand(1, 5000) * -1
            ],
            'invalid' => [
                true,
                str_random(10)
            ]            
        ];
        $this->date = [
            'valid' => [
                null,
                Carbon::now(),
                '2017-01-01'
            ],
            'invalid' => [
                true,
                str_random(10)
            ]            
        ];
        $this->datetime = [
            'valid' => [
                null,
                Carbon::now(),
                '2017-02-02 01:05:45'
            ],
            'invalid' => [
                true,
                str_random(10)
            ]            
        ];
        $this->email = [
            'valid' => [
                null,
                $this->faker->email
            ],
            'invalid' => [
                true,
                rand(0, 50000),
                str_random(10)
            ]
        ];
        $this->url = [
            'valid' => [
                null,
                $this->faker->url
            ],
            'invalid' => [
                true,
                rand(0, 50000),
                str_random(10)
            ]
        ];
        $this->string = [
            'valid' => [
                null,
                str_random(10),
                $this->faker->sentence    
            ],
            'invalid' => []
        ];
        $this->text = [
            'valid' => [
                null,
                str_random(50),
                $this->faker->sentence
            ],
            'invalid' => []
        ];
        $this->json = [
            'valid' => [
                null,
                ['property' => str_random(30)],
                range(0, 10)
            ],
            'invalid' => [
                true,
                rand(0, 50000),
                str_random(10)
            ]
        ];
        $this->jsontext = [
            'valid' => [
                ['property' => str_random(400)],
                range(0, 100)
            ],
            'invalid' => [
                true,
                rand(0, 50000),
                str_random(100)
            ]
        ];
    }

    public function testDatesAreInstanceOfCarbon()
    {
        $s = factory(Setting::class, 'withDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$s->dateCreated, $s->dateUpdated]
        );
    }

    public function testUpdateValueMethodWithValidValues()
    {
        foreach($this->types as $type) {
            $arr = $this->$type;
            foreach($arr['valid'] as $value) {
                $s = factory(Setting::class, 'withDates')->create([
                    'type' => strtoupper($type)
                ]);
                $s->updateValue($value);

                $sValue = $s->value;
                $eValue = $value;
                $dbValue = $value;
                $dbTextValue = null;
                switch ($type) {
                    case 'date':
                        if ($value instanceof Carbon) {
                            $dbValue = $value->toDateString();
                            $eValue = $dbValue;
                        }
                        if ($s->value instanceof Carbon) {
                            $sValue = $s->value->toDateString();
                        }
                        break;
                    case 'datetime':
                        if ($value instanceof Carbon) {
                            $dbValue = $value->toDateTimeString();
                            $eValue = $dbValue;
                        }
                        if ($s->value instanceof Carbon) {
                            $sValue = $s->value->toDateTimeString();
                        }
                        break;
                    case 'json':
                        $dbValue = $value ? json_encode($value) : null;
                        break;
                    case 'text':
                        $dbValue = null;
                        $dbTextValue = $value;
                        break;
                    case 'jsontext':
                        $dbValue = null;
                        $dbTextValue = json_encode($value);
                        break;

                }

                $this->seeInTable('settings', $s, null, ['value' => $dbValue]);
                if ($dbTextValue) {
                    $this->seeInTable('settings_text', [
                        'settingId' => $s->id,
                        'value'     => $dbTextValue
                    ]);
                }
                $this->assertEquals($sValue, $eValue);
                $this->assertNotEquals($s->dateCreated, $s->dateUpdated);
                $this->assertNotNull($s->dateUpdated);
            }
        }
    }

    public function testUpdateValueMethodWithInvalidValues()
    {
        foreach($this->types as $type) {
            $arr = $this->$type;
            foreach($arr['invalid'] as $value) {
                $s = factory(Setting::class, 'withDates')->create([
                    'type' => strtoupper($type)
                ]);

                $e = null;
                try {
                    $s->updateValue($value);
                } catch (HttpException $e) {
                    
                }

                $this->assertEquals($e->getStatusCode(), 500);          
            }
        }
    }

    public function testFindByNameMethodWithValidValue()
    {
        $s1 = factory(Setting::class, 'withDates')->create();
        $s2 = Setting::findByName($s1->name);
        $this->assertEquals($s1->toArray(), $s2->toArray());
    }

    public function testFindByNameMethodWithInvalidValue()
    {
        $this->assertEquals(Setting::findByName(str_random(40)), null);
    }

    public function testLookupMethodWithSingleName()
    {
        $s1 = factory(Setting::class, 'withDates')->create();
        $s2 = Setting::lookup($s1->name);
        $this->assertEquals($s1->value, $s2);
    }

    public function testLookupMethodWithNonExistentNameAndDefault()
    {
        $name = str_random(15);
        $default = str_random(30);
        $value = Setting::lookup($name, $default);
        $this->assertEquals($default, $value);        
    }

    public function testLookupMethodWithNonExistentNameAndNoDefault()
    {
        $name = str_random(15);

        try {
            $value = Setting::lookup($name);
        } catch (HttpException $e) {

        }

        $this->assertEquals($e->getStatusCode(), 500);
    }    

    public function testLookupMethodWithArrayOfNamesAndExpectNumericallyIndexArrayOfValues()
    {        
        $s1 = factory(Setting::class, 'withDates', 5)->create();
        $s2 = Setting::lookup($s1->pluck('name')->toArray());
        $this->assertEquals($s1->pluck('value')->toArray(), $s2);
    }

/*    public function testLookupMethodWithArrayOfNamesWithSingleDefaultAndExpectNumericallyIndexArrayOfValues()
    {        
        $s1 = factory(Setting::class, 'withDates', 5)->create();
        $default = str_random(10);
        $s2 = Setting::lookup($s1->pluck('name')->toArray(), $default);
        array_push($s2, str_random(10));
        $this->assertEquals($s1->pluck('value')->toArray(), $s2);
    }*/
}
