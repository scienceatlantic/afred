<?php

use App\FieldType;
use App\SearchFacetOperator;
use Illuminate\Database\Seeder;

abstract class BaseFormSeeder extends Seeder
{
    // Form field types
    public $fieldStringType;
    public $fieldPlaintextType;
    public $fieldRichtextType;
    public $fieldNumberType;
    public $fieldDateType;
    public $fieldRadioType;
    public $fieldCheckboxType;
    public $fieldDropdownType;

    // Common input patterns
    // E.g. example.com, http://example.com, https://example.com, etc.
    public static $websiteInputPattern = '(https?:\/\/(www\.)?)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)';
    // E.g. person@example.com
    public static $emailInputPattern = "^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$";
    // E.g. 9999999999
    public static $telephoneInputPattern = '^[0-9]{10}$';

    // Search facet operators
    public $andOperator;
    public $orOperator;

    public function __construct()
    {
        // Form field types
        $this->fieldStringType = FieldType::findType('string');
        $this->fieldPlaintextType = FieldType::findType('plaintext');
        $this->fieldRichtextType = FieldType::findType('richtext');
        $this->fieldNumberType = FieldType::findType('number');
        $this->fieldDateType = FieldType::findType('date');
        $this->fieldRadioType = FieldType::findType('radio');
        $this->fieldCheckboxType = FieldType::findType('checkbox');
        $this->fieldDropdownType = FieldType::findType('dropdown');

        // Search facet operators
        $this->andOperator = SearchFacetOperator::findOperator('AND');
        $this->orOperator = SearchFacetOperator::findOperator('OR');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */    
    abstract function run();
}
