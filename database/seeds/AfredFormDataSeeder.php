<?php

use App\Directory;
use App\Entity;
use App\Form;
use App\FormEntry;
use App\FormField;
use App\FieldType;
use App\FormSection;
use App\StringValue;
use App\NumberValue;
use App\LabelledValue;
use App\LabelledValueCategory;
use App\LanguageCode;
use Illuminate\Database\Seeder;

class AfredFormDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $afredDirectory = Directory
            ::where('name', 'Atlantic Facilities and Research Equipment Database')
            ->first();

        $languageCode = LanguageCode::where('iso_639_1', 'en')->first();

        $afredForm = new Form();
        $afredForm->directory_id = $afredDirectory->id;
        $afredForm->language_code_id = $languageCode->id;
        $afredForm->name = 'Facilities';
        $afredForm->save();

        $this->createFacilitySection($afredForm->id);
        $this->createPrimaryContactSection($afredForm->id);
        $this->createContactsSection($afredForm->id);
        $this->createEquipmentSection($afredForm->id);
    }

    private function createFacilitySection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'facility';
        $formSection->label_singular = 'Facility';
        $formSection->label_plural = 'Facilities';
        $formSection->object_key = 'facilities';
        $formSection->min = 1;
        $formSection->max = 1;
        $formSection->placement_order = 1;
        $formSection->save();

        $fieldStringType = FieldType::where('name', 'string')->first();
        $fieldRichTextType = FieldType::where('name', 'richtext')->first();
        $fieldDropdownType = FieldType::where('name', 'dropdown')->first();
        $fieldCheckboxType = FieldType::where('name', 'checkbox')->first();

        // Facility/Lab
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Facility/Lab';
        $formField->object_key = 'name';
        $formField->help_text = 'This is the name of the facility/lab hosting the equipment. You should create a separate record for each facility/lab you have.';
        $formField->placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // City
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'City';
        $formField->object_key = 'city';
        $formField->placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Organization
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldDropdownType->id;
        $formField->label = 'Organization';
        $formField->object_key = 'organization';
        $formField->placement_order = 3;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $organizationIds = LabelledValueCategory
            ::where('name', 'Organizations')
            ->first()
            ->values()
            ->pluck('labelled_values.id');

        $formField->labelledValues()->attach($organizationIds);

        // Province
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldDropdownType->id;
        $formField->label = 'Province';
        $formField->object_key = 'province';
        $formField->placement_order = 4;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $provinceIds = LabelledValueCategory
            ::where('name', 'Canadian Provinces')
            ->first()
            ->values()
            ->pluck('labelled_values.id');

        $formField->labelledValues()->attach($provinceIds);

        // Facilty website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Website';
        $formField->object_key = 'website';
        $formField->placeholder = 'http://example.com';
        $formField->placement_order = 5;
        $formField->input_pattern = "https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)";
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Facility description
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldRichTextType->id;
        $formField->label = 'Description';
        $formField->object_key = 'description';
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
        $formField->field_type_id = $fieldCheckboxType->id;
        $formField->label = 'Research disciplines';
        $formField->object_key = 'disciplines';
        $formField->placement_order = 7;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $disciplineIds = LabelledValueCategory
            ::where('name', 'Research Disciplines')
            ->first()
            ->values()
            ->pluck('labelled_values.id');
        
        $formField->labelledValues()->attach($disciplineIds);

        // Sectors of application
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldCheckboxType->id;
        $formField->label = 'Sectors of application';
        $formField->object_key = 'sectors';
        $formField->placement_order = 8;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $sectorsIds = LabelledValueCategory
            ::where('name', 'Sectors of Application')
            ->first()
            ->values()
            ->pluck('labelled_values.id');

        $formField->labelledValues()->attach($sectorsIds);
    }

    public function createPrimaryContactSection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'primary-contact';
        $formSection->label_singular = 'Primary Contact';
        $formSection->label_plural = 'Primary Contacts';
        $formSection->object_key = 'primaryContacts';
        $formSection->min = 1;
        $formSection->max = 1;
        $formSection->placement_order = 2;
        $formSection->save();

        $fieldStringType = FieldType::where('name', 'string')->first();
        $fieldRichTextType = FieldType::where('name', 'richtext')->first();
        $fieldDropdownType = FieldType::where('name', 'dropdown')->first();

        // First name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'First name';
        $formField->object_key = 'firstName';
        $formField->placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Last name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Last name';
        $formField->object_key = 'lastName';
        $formField->placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Email
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Email';
        $formField->object_key = 'email';
        $formField->placeholder = 'person@example.com';
        $formField->input_pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$";
        $formField->placement_order = 3;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Telephone
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Telephone';
        $formField->object_key = 'telephone';
        $formField->placeholder = '9999999999';
        $formField->input_pattern = '^\(?([0-9]{3})\)?[-.●]?([0-9]{3})[-.●]?([0-9]{4})$';
        $formField->placement_order = 4;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Position
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Position';
        $formField->object_key = 'position';
        $formField->placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Website';
        $formField->object_key = 'website';
        $formField->placeholder = 'http://example.com';
        $formField->placement_order = 6;
        $formField->input_pattern = "https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)";
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Extension
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Extension';
        $formField->object_key = 'extension';
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
        $formSection->max = 10;
        $formSection->repeat_key = 'firstName';
        $formSection->repeat_placeholder = 'Contact';
        $formSection->placement_order = 3;
        $formSection->save();

        $fieldStringType = FieldType::where('name', 'string')->first();
        $fieldRichTextType = FieldType::where('name', 'richtext')->first();
        $fieldDropdownType = FieldType::where('name', 'dropdown')->first();

        // First name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'First name';
        $formField->object_key = 'firstName';
        $formField->placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Last name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Last name';
        $formField->object_key = 'lastName';
        $formField->placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Email
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Email';
        $formField->object_key = 'email';
        $formField->placeholder = 'person@example.com';
        $formField->placement_order = 3;
        $formField->input_pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$";
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Telephone
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Telephone';
        $formField->object_key = 'telephone';
        $formField->placeholder = '9999999999';
        $formField->input_pattern = '^\(?([0-9]{3})\)?[-.●]?([0-9]{3})[-.●]?([0-9]{4})$';
        $formField->placement_order = 4;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Position
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Position';
        $formField->object_key = 'position';
        $formField->placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Website';
        $formField->object_key = 'website';
        $formField->placeholder = 'http://example.com';
        $formField->placement_order = 6;
        $formField->input_pattern = "https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)";
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Extension
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Extension';
        $formField->object_key = 'extension';
        $formField->placement_order = 7;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();
    }

    public function createEquipmentSection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->slug_prefix = 'equipment';
        $formSection->label_singular = 'Equipment';
        $formSection->label_plural = 'Equipment';
        $formSection->object_key = 'equipment';
        $formSection->min = 1;
        $formSection->max = 50;
        $formSection->repeat_key = 'type';
        $formSection->repeat_placeholder = 'Equipment';
        $formSection->placement_order = 4;
        $formSection->save();

        $fieldStringType = FieldType::where('name', 'string')->first();
        $fieldRichTextType = FieldType::where('name', 'richtext')->first();
        $formNumberType = FieldType::where('name', 'number')->first();
        $formRadioType = FieldType::where('name', 'radio')->first();
        $fieldDropdownType = FieldType::where('name', 'dropdown')->first();

        // Type
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Type';
        $formField->object_key = 'type';
        $formField->placement_order = 1;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Model
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Model';
        $formField->object_key = 'model';
        $formField->placement_order = 2;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Manufacturer
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->label = 'Manufacturer';
        $formField->object_key = 'manufacturer';
        $formField->placement_order = 3;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Purpose
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldRichTextType->id;
        $formField->label = 'Equipment purpose';
        $formField->object_key = 'purpose';
        $formField->placement_order = 4;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Specifications
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldRichTextType->id;
        $formField->label = 'Equipment specifications';
        $formField->object_key = 'specifications';
        $formField->placement_order = 5;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Year purchased
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $formNumberType->id;
        $formField->label = 'Year purchased';
        $formField->object_key = 'yearPurchased';
        $formField->placement_order = 6;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Year manufactured
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $formNumberType->id;
        $formField->label = 'Year manufactured';
        $formField->object_key = 'yearManufactured';
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
        $formField->field_type_id = $formRadioType->id;
        $formField->label = 'Excess capacity';
        $formField->object_key = 'excessCapacity';
        $formField->placement_order = 8;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->label = 'Yes';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->label = 'No';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);

        // Search visibility
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $formRadioType->id;
        $formField->label = 'Search visibility';
        $formField->object_key = 'searchVisibility';
        $formField->placement_order = 9;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->label = 'Public';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->label = 'Private';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);
    }
}
