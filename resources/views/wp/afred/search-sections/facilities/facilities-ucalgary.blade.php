@verbatim
  <div class="card panel-default search_section_facilities_panel ucalgary_facilities_panel">
    <div class="card-header">
      {{ s.facilities.name }}
      <span v-if="s.facilities.organization">| {{ s.facilities.organization.value }}</span>
    </div>
    <div class="card-body">

      <p class="small">
        {{ s.facilities.city }}<!--
    --><span v-if="s.facilities.city && s.facilities.province">,</span>
        <span v-if="s.facilities.province">{{ s.facilities.province.value }}</span>
      </p>

      <p class="small text-muted">{{ s.facilities.description_no_html }}</p>
    </div>
  </div>
@endverbatim