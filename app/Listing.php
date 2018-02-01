<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public function entrySection()
    {
        return $this->belongsTo('App\EntrySection');
    }

    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    public function getTemplateAttribute()
    {
        $listing = self
            ::with([
                'entrySection.formSection',
                'formSection'
            ])
            ->find($this->id);

        return 'templates.r.'
            . $listing
                ->entrySection
                ->formSection
                ->listing_template_prefix
            . '_'
            . $listing
                ->formSection
                ->listing_template_prefix;
    }
}
