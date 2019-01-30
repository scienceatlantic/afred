@verbatim
  <div class="search_section_facilities_panel ucalgary_facilities_panel">
    <div class="card-header">
      <h3>
        {{ s.facilities.name }}
      </h3>
      <h5 v-if="s.facilities.organization">
        {{ s.facilities.organization.value }}
      </h5>
    </div>
    <div class="card-body">

      <p class="small">
        {{ s.facilities.city }}<!--
    --><span v-if="s.facilities.city && s.facilities.province">,</span>
        <span v-if="s.facilities.province">{{ s.facilities.province.value }}</span>
      </p>


      <p class="card-text">{{ s.facilities.description_no_html }}</p>

      <a href="#" class="btn btn-light">Learn More</a>

    </div>
  </div>
@endverbatim