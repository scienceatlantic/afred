<div class="afredwp printable" style="max-width: 800px;">
  <div class="card panel-default">
    <div class="card-header">
      <h3>Equipment</h3>
    </div>
    <div class="card-body">

      @isset($formEntry->data['sections']['equipment'])
        @foreach ($formEntry->data['sections']['equipment'] as $index => $equip)
          @if ($equip['entry_section']['id'] === $listing->entry_section_id)
            @isset($equip['type'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Type</label>
                </div>
                <div class="col-md-8">{{ $equip['type'] }}</div>
              </div>
            @endisset

            @isset($equip['manufacturer'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Manufacturer</label>
                </div>
                <div class="col-md-8">{{ $equip['manufacturer'] }}</div>
              </div>
            @endisset

            @isset($equip['model'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Model</label>
                </div>
                <div class="col-md-8">{{ $equip['model'] }}</div>
              </div>
            @endisset

            @isset($equip['purpose'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Purpose</label>
                </div>
                <div class="col-md-8">{!! $equip['purpose'] !!}</div>
              </div>
            @endisset

            @isset($equip['specifications'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Specifications</label>
                </div>
                <div class="col-md-8">{!! $equip['specifications'] !!}</div>
              </div>
            @endisset

            @isset($equip['yearManufactured'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Year manufactured</label>
                </div>
                <div class="col-md-8">{{ $equip['yearManufactured'] }}</div>
              </div>
            @endisset

            @isset($equip['year_purchased'])
              <div class="row">
                <div class="col-md-4">
                  <label class="afredwp-pull-right-md control-label">Year purchased</label>
                </div>
                <div class="col-md-8">{!! $equip['year_purchased'] !!}</div>
              </div>
            @endisset
          @endif
        @endforeach
      @endisset
    </div>
  </div>

  {{-- View entire facility button --}}
  <?php
    $facilityListing = $formEntry
        ->listings()
        ->where('wp_slug', 'like', 'facility_%')
        ->first()
  ?>
  @isset($facilityListing)
    <p class="no-print">
      <button class="btn btn-secondary" onclick="location.href = '{{ $facilityListing->wp_post_url }}';">
        See all equipment for this facility
      </button>
    </p>
  @endisset

  <div class="card panel-default">
    <div class="card-header">
      <h3>Research Facility</h3>
    </div>
    <div class="card-body">

      @isset($formEntry->data['sections']['facilities'])
        @foreach ($formEntry->data['sections']['facilities'] as $facility)
          @isset($facility['name'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Facility</label>
              </div>
              <div class="col-md-8">{{ $facility['name'] }}</div>
            </div>
          @endisset

          @isset($facility['organization'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Organization</label>
              </div>
              <div class="col-md-8">{{ $facility['organization']['value'] }}</div>
            </div>
          @endisset

          @isset($facility['city'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">City</label>
              </div>
              <div class="col-md-8">{{ $facility['city'] }}</div>
            </div>
          @endisset

          @isset($facility['province'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Province</label>
              </div>
              <div class="col-md-8">
                @if (count($facility['province']) > 1)
                  <ul>
                    @foreach ($facility['province'] as $province)
                      <li>{{ $province['value'] }}</li>
                    @endforeach
                  <ul>
                @elseif (isset($facility['province'][0]['value']))
                  {{ $facility['province'][0]['value'] }}
                @endif
              </div>
            </div>
          @endisset

          @isset($facility['website'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Website</label>
              </div>
              <div class="col-md-8">
                <a href="{{ add_protocol($facility['website']) }}" target="_blank">
                  {{ $facility['website'] }}
                </a>
              </div>
            </div>
          @endisset

          @isset($facility['description'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Description</label>
              </div>
              <div class="col-md-8">
                {!! $facility['description'] !!}
              </div>
            </div>
          @endisset

          @if (count($facility['disciplines']))
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Research disciplines</label>
              </div>
              <div class="col-md-8">
                <ul>
                  @foreach ($facility['disciplines'] as $discipline)
                    <li>{{ $discipline['value'] }}</li>
                  @endforeach
                <ul>
              </div>
            </div>
          @endif

          @if (count($facility['sectors']))
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Sectors of application</label>
              </div>
              <div class="col-md-8">
                <ul>
                  @foreach ($facility['sectors'] as $sector)
                    <li>{{ $sector['value'] }}</li>
                  @endforeach
                <ul>
              </div>
            </div>
          @endif
        @endforeach
      @endisset
    </div>
  </div>

  <div class="card panel-default">
    <div class="card-header">
      <h3>Contacts</h3>
    </div>
    <div class="card-body">

      @isset($formEntry->data['sections']['primary_contacts'])
        @foreach ($formEntry->data['sections']['primary_contacts'] as $index => $contact)
          @isset($contact['first_name'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Name</label>
              </div>
              <div class="col-md-8">
                {{ $contact['first_name'] }} {{ $contact['last_name'] }}
                <span class="label label-default">Primary Contact</span>
              </div>
            </div>
          @endisset

          @isset($contact['email'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Email</label>
              </div>
              <div class="col-md-8">
                <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
              </div>
            </div>
          @endisset

          @isset($contact['telephone'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Telephone</label>
              </div>
              <div class="col-md-8">
                {{ $contact['telephone'] }}
                @isset($contact['extension'])
                  <span class="label label-default">Ext: {{ $contact['extension'] }}</span>
                @endisset
              </div>
            </div>
          @endisset

          @isset($contact['position'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Position</label>
              </div>
              <div class="col-md-8">{{ $contact['position'] }}</div>
            </div>
          @endisset

          @isset($contact['website'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Website</label>
              </div>
              <div class="col-md-8">
                <a href="{{ add_protocol($contact['website']) }}" target="_blank">
                  {{ $contact['website'] }}
                </a>
              </div>
            </div>
          @endisset

          @if ($index !== count($formEntry->data['sections']['primary_contacts']) - 1)
            <hr>
          @endif
        @endforeach
      @endisset

      @isset($formEntry->data['sections']['contacts'])
        @foreach ($formEntry->data['sections']['contacts'] as $index => $contact)
          @isset($contact['first_name'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Name</label>
              </div>
              <div class="col-md-8">
                {{ $contact['first_name'] }} {{ $contact['last_name'] }}
              </div>
            </div>
          @endisset

          @isset($contact['email'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Email</label>
              </div>
              <div class="col-md-8">
                <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
              </div>
            </div>
          @endisset

          @isset($contact['telephone'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Telephone</label>
              </div>
              <div class="col-md-8">
                {{ $contact['telephone'] }}
                @isset($contact['extension'])
                  <span class="label label-default">Ext: {{ $contact['extension'] }}</span>
                @endisset
              </div>
            </div>
          @endisset

          @isset($contact['position'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Position</label>
              </div>
              <div class="col-md-8">{{ $contact['position'] }}</div>
            </div>
          @endisset

          @isset($contact['website'])
            <div class="row">
              <div class="col-md-4">
                <label class="afredwp-pull-right-md control-label">Website</label>
              </div>
              <div class="col-md-8">
                <a href="{{ add_protocol($contact['website']) }}" target="_blank">
                  {{ $contact['website'] }}
                </a>
              </div>
            </div>
          @endisset

          @if ($index !== count($formEntry->data['sections']['contacts']) - 1)
            <hr>
          @endif
        @endforeach
      @endisset
    </div>
  </div>

  <div class="small text-muted">
    <p>
      @isset($formEntry->created_at)
        Date submitted: {{ $formEntry->created_at->toDayDateTimeString() }}<br>
      @endisset
      @isset($formEntry->updated_at)
        Date updated: {{ $formEntry->updated_at->toDayDateTimeString() }}
      @endisset
    </p>
  </div>

  <div class="small text-muted">
    <p>
      You are viewing a record that belongs to the
      <a href="https://afred.ca">Atlantic Facilities and Research Equipment Database</a>
    </p>
  </div>
</div>
