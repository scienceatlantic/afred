<div class="afredwp">
  <div class="panel panel-default">
    <div class="panel-body">      
      <p style="text-decoration: underline">
        <b>Equipment</b>
      <p>

      @isset($data['sections']['equipment'])
        @foreach ($data['sections']['equipment'] as $index => $equip)
          @if ($equip['_meta']['entry_section_id'] === $entrySectionId)
            @isset($equip['type'])
              <div class="row">
                <p class="col-md-4">Type</p>
                <p class="col-md-8">{{ $equip['type'] }}</p>
              </div>
            @endisset

            @isset($equip['manufacturer'])
              <div class="row">
                <p class="col-md-4">Manufacturer</p>
                <p class="col-md-8">{{ $equip['manufacturer'] }}</p>
              </div>
            @endisset

            @isset($equip['model'])
              <div class="row">
                <p class="col-md-4">Model</p>
                <p class="col-md-8">{{ $equip['model'] }}</p>
              </div>
            @endisset

            @isset($equip['purpose'])
              <div class="row">
                <p class="col-md-4">Purpose</p>
                <p class="col-md-8">{{ $equip['purpose'] }}</p>
              </div>
            @endisset

            @isset($equip['specifications'])
              <div class="row">
                <p class="col-md-4">Specifications</p>
                <p class="col-md-8">{{ $equip['specifications'] }}</p>
              </div>
            @endisset

            @isset($equip['yearManufactured'])
              <div class="row">
                <p class="col-md-4">Year manufactured</p>
                <p class="col-md-8">{{ $equip['yearManufactured'] }}</p>
              </div>
            @endisset

            @isset($equip['year_purchased'])
              <div class="row">
                <p class="col-md-4">Year purchased</p>
                <p class="col-md-8">{{ $equip['year_purchased'] }}</p>
              </div>
            @endisset
          @endif
        @endforeach
      @endisset

      <p style="text-decoration: underline">
        <b>Research Facility</b>
      </p>

      <hr>      

      @isset($data['sections']['facilities'])
        @foreach ($data['sections']['facilities'] as $facility)
          @isset($facility['name'])
            <div class="row">
              <p class="col-md-4">Facility</p>
              <p class="col-md-8">{{ $facility['name'] }}</p>
            </div>
          @endisset

          @isset($facility['organization'][0]['value'])
            <div class="row">
              <p class="col-md-4">Organization</p>
              <p class="col-md-8">{{ $facility['organization'][0]['value'] }}</p>
            </div>
          @endisset

          @isset($facility['city'])
            <div class="row">
              <p class="col-md-4">City</p>
              <p class="col-md-8">{{ $facility['city'] }}</p>
            </div>
          @endisset

          @isset($facility['province'][0]['value'])
            <div class="row">
              <p class="col-md-4">Province</p>
              <p class="col-md-8">{{ $facility['province'][0]['value'] }}</p>
            </div>
          @endisset

          @isset($facility['website'])
            <div class="row">
              <p class="col-md-4">Website</p>
              <p class="col-md-8">
                <a href="{{ $facility['website'] }}" target="_blank">
                  {{ $facility['website'] }}
                </a>
              </p>
            </div>
          @endisset

          @isset($facility['description'])
            <div class="row">
              <p class="col-md-4">Description</p>
              <p class="col-md-8">
                {{ $facility['description'] }}
              </p>
            </div>
          @endisset
          
          @if (count($facility['disciplines']))
            <div class="row">
              <p class="col-md-4">Research disciplines</p>
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
              <p class="col-md-4">Sectors of application</p>
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

  <hr>
  <p style="text-decoration: underline">
    <b>Contacts</b>
  </p>

  @isset($data['sections']['primary_contacts'])
    @foreach ($data['sections']['primary_contacts'] as $index => $contact)
      @isset($contact['first_name'])
        <div class="row">
          <p class="col-md-4">Name</p>
          <p class="col-md-8">
            {{ $contact['first_name'] }} {{ $contact['last_name'] }}
          </p>
        </div>
      @endisset

      @isset($contact['email'])
        <div class="row">
          <p class="col-md-4">Email</p>
          <p class="col-md-8">
            <a href="mailto:{{ $contact['email'] }}"></a>
            {{ $contact['email'] }}
          </p>
        </div>
      @endisset

      @isset($contact['telephone'])
        <div class="row">
          <p class="col-md-4">Telephone</p>
          <p class="col-md-8">
            {{ $contact['telephone'] }}
            @isset($contact['extension'])
              <span class="label label-default">Ext: {{ $contact['extension'] }}</span>
            @endisset          
          </p>
        </div>
      @endisset

      @isset($contact['position'])
        <div class="row">
          <p class="col-md-4">Position</p>
          <p class="col-md-8">{{ $contact['position'] }}</p>
        </div>
      @endisset

      @isset($contact['website'])
        <div class="row">
          <p class="col-md-4">Website</p>
          <p class="col-md-8">
            <a href="{{ $contact['website'] }}" target="_blank">
              {{ $contact['website'] }}
            </a>
          </p>
        </div>
      @endisset

      @if ($index !== count($data['sections']['primary_contacts']) - 1)
        <hr>
      @endif
    @endforeach
  @endisset

  @isset($data['sections']['contacts'])
    @foreach ($data['sections']['contacts'] as $index => $contact)
      @isset($contact['first_name'])
        <div class="row">
          <p class="col-md-4">Name</p>
          <p class="col-md-8">
            {{ $contact['first_name'] }} {{ $contact['last_name'] }}
          </p>
        </div>
      @endisset

      @isset($contact['email'])
        <div class="row">
          <p class="col-md-4">Email</p>
          <p class="col-md-8">
            <a href="mailto:{{ $contact['email'] }}"></a>
            {{ $contact['email'] }}
          </p>
        </div>
      @endisset

      @isset($contact['telephone'])
        <div class="row">
          <p class="col-md-4">Telephone</p>
          <p class="col-md-8">
            {{ $contact['telephone'] }}
            @isset($contact['extension'])
              <span class="label label-default">Ext: {{ $contact['extension'] }}</span>
            @endisset
          </p>
        </div>
      @endisset

      @isset($contact['position'])
        <div class="row">
          <p class="col-md-4">Position</p>
          <p class="col-md-8">{{ $contact['position'] }}</p>
        </div>
      @endisset

      @isset($contact['website'])
        <div class="row">
          <p class="col-md-4">Website</p>
          <p class="col-md-8">
            <a href="{{ $contact['website'] }}" target="_blank">
              {{ $contact['website'] }}
            </a>
          </p>
        </div>
      @endisset

      @if ($index !== count($data['sections']['contacts']) - 1)
        <hr>
      @endif
    @endforeach
  @endisset
  
  <hr>
  <div style="font-size: 10pt">
    <p>Date submitted: {{ $created_at }}</p>
    <p>Date updated: {{ $updated_at }}</p>
  </div>
</div>
