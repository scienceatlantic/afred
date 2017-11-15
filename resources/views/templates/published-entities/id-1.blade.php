<div class="afredwp">
  <div class="panel panel-default">
    <div class="panel-body">
      <p style="text-decoration: underline">
        <b>Research Facility</b>
      </p>

      @foreach ($facilities as $facility)
        @isset($facility['name'])
          <div class="row">
            <p class="col-md-4">Facility</p>
            <p class="col-md-8">{{ $facility['name'] }}</p>
          </div>
        @endisset

        @isset($facility['organization'])
          <div class="row">
            <p class="col-md-4">Organization</p>
            <p class="col-md-8">{{ $facility['organization'] }}</p>
          </div>
        @endisset

        @isset($facility['city'])
          <div class="row">
            <p class="col-md-4">City</p>
            <p class="col-md-8">{{ $facility['city'] }}</p>
          </div>
        @endisset

        @isset($facility['province'])
          <div class="row">
            <p class="col-md-4">Province</p>
            <p class="col-md-8">{{ $facility['province'] }}</p>
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
                  <li>{{ $discipline }}</li>
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
                  <li>{{ $sector }}</li>
                @endforeach
              <ul>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </div>

  <hr>
  <p style="text-decoration: underline">
    <b>Contacts</b>
  </p>

  @foreach ($primaryContacts as $index => $contact)
    @isset($contact['firstName'])
      <div class="row">
        <p class="col-md-4">Name</p>
        <p class="col-md-8">
          {{ $contact['firstName'] }} {{ $contact['lastName'] }}
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

    @if ($index !== count($primaryContacts) - 1)
      <hr>
    @endif
  @endforeach

  @foreach ($contacts as $index => $contact)
    @isset($contact['firstName'])
      <div class="row">
        <p class="col-md-4">Name</p>
        <p class="col-md-8">
          {{ $contact['firstName'] }} {{ $contact['lastName'] }}
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

    @if ($index !== count($contacts) - 1)
      <hr>
    @endif
  @endforeach

  <hr>
  
  <p style="text-decoration: underline">
    <b>Equipment</b>
  <p>

  @foreach ($equipment as $index => $equip)
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

    @isset($equip['yearPurchased'])
      <div class="row">
        <p class="col-md-4">Year purchased</p>
        <p class="col-md-8">{{ $equip['yearPurchased'] }}</p>
      </div>
    @endisset

    @if ($index !== count($equipment) - 1)
      <hr>
    @endif
  @endforeach

  <hr>
  <div style="font-size: 10pt">
    <p>Date submitted: {{ $created_at->toDayDateTimeString() }}</p>
    <p>Date updated: {{ $updated_at->toDayDateTimeString() }}</p>
  </div>
</div>
