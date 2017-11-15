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
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $afredDirectory = Directory::find(1);
        $afredForm = Form::find(1);

        $entity = new Entity();
        $entity->save();
        $entity->directories()->attach($afredDirectory->id);

        $formEntry = new FormEntry();
        $formEntry->entity_id = $entity->id;
        $formEntry->save();

        $this->createFacilitySection($afredForm->id);
        $this->createContactsSection($afredForm->id);
        $this->createEquipmentSection($afredForm->id);
    }

    private function createFacilitySection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->form_label = 'Facility';
        $formSection->object_key = 'facilities';
        $formSection->min = 1;
        $formSection->max = 1;
        $formSection->form_placement_order = 1;
        $formSection->save();

        $fieldStringType = FieldType::where('name', 'string')->first();
        $fieldRichTextType = FieldType::where('name', 'richtext')->first();
        $fieldDropdownType = FieldType::where('name', 'dropdown')->first();

        // Facility/Lab
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Facility/Lab';
        $formField->object_key = 'name';
        $formField->help_text = 'This is the name of the facility/lab hosting the equipment. You should create a separate record for each facility/lab you have.';
        $formField->form_placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // City
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'City';
        $formField->object_key = 'city';
        $formField->form_placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Organization
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldDropdownType->id;
        $formField->form_label = 'Organization';
        $formField->object_key = 'organization';
        $formField->form_placement_order = 3;
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
        $formField->form_label = 'Province';
        $formField->object_key = 'province';
        $formField->form_placement_order = 4;
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
        $formField->form_label = 'Website';
        $formField->object_key = 'website';
        $formField->placeholder = 'http://example.com';
        $formField->form_placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Facility description
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldRichTextType->id;
        $formField->form_label = 'Description';
        $formField->object_key = 'description';
        $formField->placeholder = 'What does the facility do?';
        $formField->form_placement_order = 6;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();
    }

    public function createContactsSection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->form_label = 'Contacts';
        $formSection->object_key = 'contacts';
        $formSection->min = 0;
        $formSection->max = 10;
        $formSection->form_placement_order = 2;
        $formSection->save();

        $fieldStringType = FieldType::where('name', 'string')->first();
        $fieldRichTextType = FieldType::where('name', 'richtext')->first();
        $fieldDropdownType = FieldType::where('name', 'dropdown')->first();

        // First name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'First name';
        $formField->object_key = 'firstName';
        $formField->form_placement_order = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Last name
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Last name';
        $formField->object_key = 'lastName';
        $formField->form_placement_order = 2;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Email
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Email';
        $formField->object_key = 'email';
        $formField->placeholder = 'person@example.com';
        $formField->form_placement_order = 3;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Telephone
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Telephone';
        $formField->object_key = 'telephone';
        $formField->placeholder = '9999999999';
        $formField->form_placement_order = 4;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Position
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Position';
        $formField->object_key = 'position';
        $formField->form_placement_order = 5;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Website
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Website';
        $formField->object_key = 'website';
        $formField->placeholder = 'http://example.com';
        $formField->form_placement_order = 6;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Extension
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Extension';
        $formField->object_key = 'extension';
        $formField->form_placement_order = 7;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();
    }

    public function createEquipmentSection($formId)
    {
        $formSection = new FormSection();
        $formSection->form_id = $formId;
        $formSection->form_label = 'Equipment';
        $formSection->object_key = 'equipment';
        $formSection->min = 1;
        $formSection->max = 50;
        $formSection->form_placement_order = 3;
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
        $formField->form_label = 'Type';
        $formField->object_key = 'type';
        $formField->form_placement_order = 1;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Model
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Model';
        $formField->object_key = 'model';
        $formField->form_placement_order = 2;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Manufacturer
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldStringType->id;
        $formField->form_label = 'Manufacturer';
        $formField->object_key = 'manufacturer';
        $formField->form_placement_order = 3;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Purpose
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldRichTextType->id;
        $formField->form_label = 'Equipment purpose';
        $formField->object_key = 'purpose';
        $formField->form_placement_order = 4;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        // Specifications
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $fieldRichTextType->id;
        $formField->form_label = 'Equipment specifications';
        $formField->object_key = 'specifications';
        $formField->form_placement_order = 5;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Year purchased
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $formNumberType->id;
        $formField->form_label = 'Year purchased';
        $formField->object_key = 'yearPurchased';
        $formField->form_placement_order = 6;
        $formField->is_single_column = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 0;
        $formField->is_active = 1;
        $formField->save();

        // Year manufactured
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $formNumberType->id;
        $formField->form_label = 'Year manufactured';
        $formField->object_key = 'yearManufactured';
        $formField->form_placement_order = 7;
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
        $formField->form_label = 'Excess capacity';
        $formField->object_key = 'excessCapacity';
        $formField->form_placement_order = 8;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->form_label = 'Yes';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->form_label = 'No';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);

        // Search visibility
        $formField = new FormField();
        $formField->form_section_id = $formSection->id;
        $formField->field_type_id = $formRadioType->id;
        $formField->form_label = 'Search visibility';
        $formField->object_key = 'searchVisibility';
        $formField->form_placement_order = 9;
        $formField->is_single_column = 1;
        $formField->is_inline = 1;
        $formField->is_searchable = 1;
        $formField->is_required = 1;
        $formField->is_active = 1;
        $formField->save();

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->form_label = 'Public';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);

        $formFieldRadioValue = new LabelledValue();
        $formFieldRadioValue->form_label = 'Private';
        $formFieldRadioValue->save();
        $formFieldRadioValue->formFields()->attach($formField->id);
    }
}
