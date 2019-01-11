@verbatim
  <div class="card panel-default">
    <div class="card-block">
      <p class="h4">
        {{ s.equipment.type }} | {{ s.facilities[0].name }}
      </p>

      <p class="small" v-if="s.facilities[0].organization || s.facilities[0].province">
        <span v-if="s.facilities[0].organization">{{ s.facilities[0].organization.value }}</span><!--
    --><span v-if="s.facilities[0].organization && s.facilities[0].province">,</span>
        <span v-if="s.facilities[0].province">{{ s.facilities[0].province.value }}</span>
      </p>
      
      <p class="small text-muted">{{ s.equipment.purpose_no_html }}</p>
    </div>
  </div>
@endverbatim