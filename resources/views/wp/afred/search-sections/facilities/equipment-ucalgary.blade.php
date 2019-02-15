@verbatim
  <div class="search_section_facilities_panel ucalgary_equipment_panel">
    <div class="card-header">
        <h3>
          {{ s.facilities.name }}
        </h3>
        <h5 v-if="s.facilities[0]">
          {{ s.facilities[0].name }}
        </h5>
    </div>
    <div class="card-body">

      <p class="small" v-if="s.facilities[0].organization || s.facilities[0].province">
        <span v-if="s.facilities[0].organization">{{ s.facilities[0].organization.value }}</span><!--
    --><span v-if="s.facilities[0].organization && s.facilities[0].province">,</span>
        <span v-if="s.facilities[0].province">{{ s.facilities[0].province.value }}</span>
      </p>


      <p class="card-text">{{ s.equipment.purpose_no_html }}</p>

      <a :href="r.wp_post_url" class="btn btn-light">Learn More</a>

    </div>
  </div>
@endverbatim