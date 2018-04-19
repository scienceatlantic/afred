<?php

use App\Directory;
use App\Form;
use App\FormField;
use App\FormSection;
use App\StringValue;
use App\SearchSection;
use App\SearchFacet;
use App\LabelledValue;
use App\LabelledValueCategory;
use App\LanguageCode;

class AfredFormDataSeeder extends BaseFormSeeder
{
    public static $facilitiesSearchIndex = 'development_afred_facilities_facility';
    public static $equipmentSearchIndex = 'development_afred_facilities_equipment';
    public static $formWpPostId = 13;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $directories = [[
            'name'              => 'Atlantic Facilities and Research Equipment Database',
            'shortname'         => 'AFRED',
            'resource_folder'   => 'afred',
            'wp_base_url'       => 'http://localhost/afred-wp-demo',
            'wp_admin_base_url' => 'http://localhost/afred-wp-demo/wp-admin',
            'wp_api_base_url'   => 'http://localhost/afred-wp-demo/wp-json/wp/v2',
            'wp_api_password'   => 'cm9vdDp2VFptIG1LZEMgSFpDNSBNV2JiIHBlS3MgU2RSVQ=='
        ]];
        BaseSeeder::saveModels('Directory', $directories);

        $directory = Directory::findDirectory('Atlantic Facilities and Research Equipment Database');

        $languageCode = LanguageCode::findCode('en');

        $form = new Form();
        $form->directory_id = $directory->id;
        $form->language_code_id = $languageCode->id;
        $form->wp_post_id = self::$formWpPostId;
        $form->name = 'Facilities';
        $form->resource_folder = 'facilities';
        $form->pagination_section_object_key = 'facilities';
        $form->pagination_field_object_key = 'name';
        $form->save();

        $form->compatibleForms()->attach($form->id);

        $this->createFacilitySection($form->id);
        $this->createPrimaryContactSection($form->id);
        $this->createContactsSection($form->id);
        $this->createEquipmentSection($form->id);

        // Include "Primary Contacts" and "Contacts" as search sections that
        // should be included when searching for facilities.
        $form->formSections()
            ->where('object_key', 'facilities')
            ->first()
            ->formSectionsIncludedInSearch()
            ->attach(
                $form->formSections()
                    ->where('object_key', 'primary_contacts')
                    ->orWhere('object_key', 'contacts')
                    ->pluck('id')
            );

        // Include "Facilities" as a search section that should be included when
        // searching for equipment.
        $form->formSections()
            ->where('object_key', 'equipment')
            ->first()
            ->formSectionsIncludedInSearch()
            ->attach(
                $form->formSections()
                    ->where('object_key', 'facilities')
                    ->pluck('id')
            );

        $this->createFacilitySearchSection($form->id);
        $this->createEquipmentSearchSection($form->id);
    }

    private function createFacilitySection($formId)
    {
        $organizationIds = LabelledValueCategory
            ::findCategory('Organizations')
            ->values()
            ->pluck('labelled_values.id');

        $provinceIds = LabelledValueCategory
            ::findCategory('Canadian Atlantic Provinces')
            ->values()
            ->pluck('labelled_values.id');

        $disciplineIds = LabelledValueCategory
            ::findCategory('Research Disciplines')
            ->values()
            ->pluck('labelled_values.id');
            
        $sectorsIds = LabelledValueCategory
            ::findCategory('Sectors of Application')
            ->values()
            ->pluck('labelled_values.id');

        $notApplicable = LabelledValue::findLabel('N/A');

        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'facility';
        $formSection->search_index = self::$facilitiesSearchIndex;
        $formSection->label_singular = 'Facility';
        $formSection->label_plural = 'Facilities';
        $formSection->object_key = 'facilities';
        $formSection->min = 1;
        $formSection->max = 1;
        $formSection->placement_order = 1;
        $formSection->field_resource_title_object_key = 'name';
        $formSection->is_resource = true;
        $formSection->save();

        // Facility/Lab
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Facility/Lab';
        $formField->object_key = 'name';
        $formField->max_length = 200;
        $formField->help_text = 'This is the name of the facility/lab hosting '
                              . 'the equipment. You should create a separate ' 
                              . 'record for each facility/lab you have.';
        $formField->placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // City
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'City';
        $formField->object_key = 'city';
        $formField->max_length = 150;
        $formField->help_text = 'If your facility/lab has locations in '
                              . 'multiple cities, please list all cities ' 
                              . 'separated by commas (or leave blank).';
        $formField->placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Organization
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldDropdownType->id;
        $formField->label = 'Organization';
        $formField->object_key = 'organization';
        $formField->help_text = 'Refers to the name of the ' 
                              . 'institute/company/association/etc with which '
                              . 'your facility/lab is affiliated. Please '
                              . 'select organization from drop down list. '
                              . 'If the organization name does not differ from '
                              . 'the facility/lab name, please select "N/A".';
        $formField->placement_order = 3;
        $formField->has_ilo = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formField->labelledValues()->attach($notApplicable->id);
        $formField->labelledValues()->attach($organizationIds);

        // Province
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldCheckboxType->id;
        $formField->label = 'Province';
        $formField->object_key = 'province';
        $formField->help_text = 'If your facility/lab has locations in '
                              . 'multiple provinces, please select all that '
                              . 'apply. If your province is not listed, please '
                              . 'contact afred@scienceatlantic.ca.';
        $formField->placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->is_single_column = 1;
        $formField->is_split_list = 1;
        $formField->show_select_all = 1;
        $formField->save();

        $formField->labelledValues()->attach($provinceIds);

        // Facilty website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Website';
        $formField->object_key = 'website';
        $formField->max_length = 2083;
        $formField->placeholder = 'http://example.com';
        $formField->placement_order = 4;
        $formField->input_pattern = self::$websiteInputPattern;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Facility description
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldRichtextType->id;
        $formField->label = 'Description';
        $formField->object_key = 'description';
        $formField->max_length = 2000;
        $formField->help_text = 'Describe what your facility/lab does, such as '
                              . 'type of research, services offered, etc.';
        $formField->placeholder = 'What does the facility do?';
        $formField->placement_order = 6;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Research disciplines
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldCheckboxType->id;
        $formField->label = 'Research disciplines';
        $formField->object_key = 'disciplines';
        $formField->help_text = 'Please check all research disciplines for '
                              . 'which your facility/lab could be relevant. '
                              . 'You must select at least one research '
                              . 'discipline. If none apply, please contact '
                              . 'afred@scienceatlantic.ca.';
        $formField->placement_order = 7;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->is_single_column = 1;
        $formField->is_split_list = 1;
        $formField->show_select_all = 1;
        $formField->save();
        
        $formField->labelledValues()->attach($disciplineIds);

        // Sectors of application
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldCheckboxType->id;
        $formField->label = 'Sectors of application';
        $formField->object_key = 'sectors';
        $formField->help_text = 'Please check all sectors of application for '
                              . 'which your facility/lab could be relevant. '
                              . 'You must select at least one sector of '
                              . 'application. If none apply, please contact '
                              . 'afred@scienceatlantic.ca.';
        $formField->placement_order = 8;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->is_single_column = 1;
        $formField->is_split_list = 1;
        $formField->show_select_all = 1;
        $formField->save();

        $formField->labelledValues()->attach($sectorsIds);
    }

    public function createPrimaryContactSection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'primary-contact';
        $formSection->label_singular = 'Primary Contact';
        $formSection->label_plural = 'Primary Contacts';
        $formSection->object_key = 'primary_contacts';
        $formSection->min = 1;
        $formSection->max = 1;
        $formSection->placement_order = 2;
        $formSection->is_primary_contact = true;
        $formSection->is_editor = true;
        $formSection->is_resource = false;
        $formSection->save();

        // First name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'First name';
        $formField->object_key = 'first_name';
        $formField->max_length = 50;
        $formField->placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Last name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Last name';
        $formField->object_key = 'last_name';
        $formField->max_length = 50;
        $formField->placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Email
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Email';
        $formField->object_key = 'email';
        $formField->max_length = 254;
        $formField->placeholder = 'person@example.com';
        $formField->input_pattern = self::$emailInputPattern;
        $formField->placement_order = 3;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Telephone
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Telephone';
        $formField->object_key = 'telephone';
        $formField->max_length = 10;
        $formField->placeholder = '9999999999';
        $formField->input_pattern = self::$telephoneInputPattern;
        $formField->placement_order = 4;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Position
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Position';
        $formField->object_key = 'position';
        $formField->max_length = 100;
        $formField->placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Website';
        $formField->object_key = 'website';
        $formField->max_length = 2083;
        $formField->help_text = 'If you have your own website, separate from '
                              . 'the facility website, enter it here.';
        $formField->placeholder = 'http://example.com';
        $formField->placement_order = 6;
        $formField->input_pattern = self::$websiteInputPattern;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Extension
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Extension';
        $formField->object_key = 'extension';
        $formField->max_length = 10;
        $formField->placement_order = 7;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();
    }    

    public function createContactsSection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'contact';
        $formSection->label_singular = 'Contact';
        $formSection->label_plural = 'Contacts';
        $formSection->object_key = 'contacts';
        $formSection->min = 0;
        $formSection->max = 3;
        $formSection->repeat_object_key = 'first_name';
        $formSection->repeat_placeholder = 'Contact';
        $formSection->placement_order = 3;
        $formSection->is_editor = true;
        $formSection->is_resource = false;
        $formSection->save();

        // First name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'First name';
        $formField->object_key = 'first_name';
        $formField->max_length = 50;
        $formField->placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Last name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Last name';
        $formField->object_key = 'last_name';
        $formField->max_length = 50;
        $formField->placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Email
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Email';
        $formField->object_key = 'email';
        $formField->max_length = 254;
        $formField->placeholder = 'person@example.com';
        $formField->placement_order = 3;
        $formField->input_pattern = self::$emailInputPattern;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Telephone
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Telephone';
        $formField->object_key = 'telephone';
        $formField->max_length = 10;
        $formField->placeholder = '9999999999';
        $formField->input_pattern = self::$telephoneInputPattern;
        $formField->placement_order = 4;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Position
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Position';
        $formField->object_key = 'position';
        $formField->max_length = 100;
        $formField->placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Website';
        $formField->object_key = 'website';
        $formField->max_length = 2083;
        $formField->help_text = 'If you have your own website, separate from '
                              . 'the facility website, enter it here.';        
        $formField->placeholder = 'http://example.com';
        $formField->placement_order = 6;
        $formField->input_pattern = self::$websiteInputPattern;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Extension
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Extension';
        $formField->object_key = 'extension';
        $formField->max_length = 10;
        $formField->placement_order = 7;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();
    }

    public function createEquipmentSection($formId)
    {
        $excessCapacityIds = LabelledValueCategory
            ::findCategory('Excess capacity')
            ->values()
            ->pluck('labelled_values.id');

        $searchVisibilityIds = LabelledValueCategory
            ::findCategory('Search visibility')
            ->values()
            ->pluck('labelled_values.id');
                
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'equipment';
        $formSection->search_index = self::$equipmentSearchIndex;
        $formSection->label_singular = 'Equipment';
        $formSection->label_plural = 'Equipment';
        $formSection->object_key = 'equipment';
        $formSection->min = 1;
        $formSection->max = 50;
        $formSection->repeat_object_key = 'type';
        $formSection->repeat_placeholder = 'Equipment';
        $formSection->placement_order = 4;
        $formSection->field_resource_title_object_key = 'type';
        $formSection->is_resource = true;
        $formSection->save();

        // Type
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Type';
        $formField->object_key = 'type';
        $formField->max_length = 200;
        $formField->help_text = 'The full name of the piece of equipment.';
        $formField->placeholder = 'E.g. Magnetic resonance imaging (MRI)';
        $formField->placement_order = 1;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Manufacturer
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Manufacturer';
        $formField->object_key = 'manufacturer';
        $formField->max_length = 100;
        $formField->placeholder = 'E.g. Hitachi Medical';
        $formField->placement_order = 2;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();        

        // Model
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Model';
        $formField->object_key = 'model';
        $formField->placeholder = 'E.g. Echelon Oval 1.5T Ultra-Wide MRI system';
        $formField->max_length = 100;
        $formField->placement_order = 3;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Purpose
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldRichtextType->id;
        $formField->label = 'Equipment purpose';
        $formField->object_key = 'purpose';
        $formField->max_length = 2000;
        $formField->help_text = 'Provide a brief description of the piece of '
                              . 'equipment, its functions, possible uses, '
                              . 'special features, etc.';
        $formField->placement_order = 4;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Specifications
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldRichtextType->id;
        $formField->label = 'Equipment specifications';
        $formField->object_key = 'specifications';
        $formField->max_length = 2000;
        $formField->help_text = 'Include information such as dimensions, ' 
                              . 'weight, power supply, battery life, software, '
                              . 'resolution, range, etc. Please include units '
                              . 'of measurement.';
        $formField->placement_order = 5;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Year purchased
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldNumberType->id;
        $formField->label = 'Year purchased';
        $formField->object_key = 'year_purchased';
        $formField->min_value = 1000;
        $formField->max_value = 3000;
        $formField->placeholder = 'E.g. 1995';
        $formField->placement_order = 6;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Year manufactured
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldNumberType->id;
        $formField->label = 'Year manufactured';
        $formField->object_key = 'year_manufactured';
        $formField->min_value = 1000;
        $formField->max_value = 3000;
        $formField->placeholder = 'E.g. 1990';
        $formField->placement_order = 7;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Excess capacity
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldRadioType->id;
        $formField->label = 'Excess capacity';
        $formField->notes = 'Note: this field will not be publicly viewable.';
        $formField->object_key = 'excess_capacity';
        $formField->help_text = 'Excess capacity means that the piece of '
                              . 'equipment is not being fully utilized, that '
                              . 'there is time available for it to be used by '
                              . 'others or on other projects.';
        $formField->placement_order = 8;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formField->labelledValues()->attach($excessCapacityIds);

        // Search visibility
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldRadioType->id;
        $formField->label = 'Search visibility';
        $formField->object_key = 'is_public';
        $formField->help_text = 'To ensure that we have a complete listing of '
                              . 'everything within your facility, please '
                              . 'include all equipment, even that which you do '
                              . 'not want publicly viewable. If you do not '
                              . 'want the piece of equipment to be publicly '
                              . 'viewable, check "Private".';
        $formField->placement_order = 9;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formField->labelledValues()->attach($searchVisibilityIds);

        // Keywords
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $this->fieldStringType->id;
        $formField->label = 'Keywords';
        $formField->object_key = 'keywords';
        $formField->max_length = 500;
        $formField->help_text = 'Please include any common terms, acronyms, '
                              . 'and/or associated words that apply to this '
                              . 'piece of equipment. This improves the '
                              . 'likelihood that it will appear in search '
                              . 'results.';
        $formField->placeholder = 'E.g. NMRI, MRT, etc.';
        $formField->placement_order = 10;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();        

        $formField->labelledValues()->attach($searchVisibilityIds);
    }

    public function createFacilitySearchSection($formId)
    {
        $formSection = Form
            ::find($formId)
            ->formSections()
            ->where('object_key', 'facilities')
            ->first();

        $searchSection = new SearchSection();
        $searchSection->form_section_id = $formSection->id;
        $searchSection->label = 'Facilities/Contacts';
        $searchSection->input_placeholder = 'e.g. electron microscope';
        $searchSection->placement_order = 2;
        $searchSection->is_default = false;
        $searchSection->save();

        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->orOperator->id;
        $searchFacet->label = 'Provinces';
        $searchFacet->algolia_object_key = 'sections.facilities.province.value';
        $searchFacet->placement_order = 1;
        $searchFacet->save();

        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->orOperator->id;
        $searchFacet->label = 'Organizations';
        $searchFacet->algolia_object_key = 'sections.facilities.organization.value';
        $searchFacet->placement_order = 2;
        $searchFacet->save();

        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->andOperator->id;
        $searchFacet->label = 'Disciplines';
        $searchFacet->algolia_object_key = 'sections.facilities.disciplines.value';
        $searchFacet->placement_order = 3;
        $searchFacet->save();
        
        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->andOperator->id;
        $searchFacet->label = 'Sectors';
        $searchFacet->algolia_object_key = 'sections.facilities.sectors.value';
        $searchFacet->placement_order = 4;
        $searchFacet->save();        
    }

    public function createEquipmentSearchSection($formId)
    {           
        $formSection = Form
            ::find($formId)
            ->formSections()
            ->where('object_key', 'equipment')
            ->first();

        $searchSection = new SearchSection();
        $searchSection->form_section_id = $formSection->id;
        $searchSection->label = 'Equipment';
        $searchSection->input_placeholder = 'e.g. electron microscope';
        $searchSection->placement_order = 1;
        $searchSection->is_default = true;
        $searchSection->save();

        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->orOperator->id;
        $searchFacet->label = 'Provinces';
        $searchFacet->algolia_object_key = 'sections.facilities.province.value';
        $searchFacet->placement_order = 1;
        $searchFacet->save();

        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->orOperator->id;
        $searchFacet->label = 'Organizations';
        $searchFacet->algolia_object_key = 'sections.facilities.organization.value';
        $searchFacet->placement_order = 2;
        $searchFacet->save();

        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->andOperator->id;
        $searchFacet->label = 'Disciplines';
        $searchFacet->algolia_object_key = 'sections.facilities.disciplines.value';
        $searchFacet->placement_order = 3;
        $searchFacet->save();
        
        $searchFacet = new SearchFacet();
        $searchFacet->search_section_id = $searchSection->id;
        $searchFacet->search_facet_operator_id = $this->andOperator->id;
        $searchFacet->label = 'Sectors';
        $searchFacet->algolia_object_key = 'sections.facilities.sectors.value';
        $searchFacet->placement_order = 4;
        $searchFacet->save();        
    }    
}
