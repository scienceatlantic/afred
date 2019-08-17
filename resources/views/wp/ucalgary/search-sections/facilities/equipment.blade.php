@verbatim
  <div>
    <div class="card-header">
      <h3>
        {{ s.equipment.type }}
      </h3>
      <h5>
        {{ s.facilities[0].name }}
      </h5>
    </div>
    <div class="card-body">

      <p class="small" v-if="s.facilities[0].organization || s.facilities[0].province">
        <span v-if="s.facilities[0].organization">{{ s.facilities[0].organization.value }}</span>
      </p>

      <p class="card-text">{{ makePreview(s.equipment.purpose_no_html) }}</p>

      <a :href="r.wp_post_url" class="btn btn-light">Learn More</a>

    </div>
  </div>
@endverbatim