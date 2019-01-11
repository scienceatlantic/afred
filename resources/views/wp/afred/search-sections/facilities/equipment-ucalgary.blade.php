@verbatim
  <div class="card panel-default search_section_facilities_panel ucalgary_equipment_panel">
    <div class="card-header">
      <h3>{{ s.equipment.type }} | {{ s.facilities[0].name }}</h3>
    </div>
    <div class="card-body">

      <p class="small" v-if="s.facilities[0].organization || s.facilities[0].province">
        <span v-if="s.facilities[0].organization">{{ s.facilities[0].organization.value }}</span><!--
    --><span v-if="s.facilities[0].organization && s.facilities[0].province">,</span>
        <span v-if="s.facilities[0].province">{{ s.facilities[0].province.value }}</span>
      </p>

      <p class="small text-muted">{{ s.equipment.purpose_no_html }}</p>
    </div>
  </div>
@endverbatim