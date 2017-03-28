<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$dummyEmail = env('MAIL_UNIVERSAL_TO_ADDRESS', 'afred.dev@scienceatlantic.ca');

$factory->define(App\Contact::class, 
    function (Faker\Generator $faker) use ($dummyEmail) {
        return [
            'id' => null,
            'facilityId' => null,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $dummyEmail,
            'telephone' => $faker->numerify('##########'),
            'extension' => null,
            'position' => null,
            'website' => null
        ];
    }
);

$factory->defineAs(App\Contact::class, 'complete', 
    function (Faker\Generator $faker) use ($factory) {
        $contact = $factory->raw(App\Contact::class);

        return array_merge($contact, [
            'extension' => $faker->numerify('######'),
            'position' => $faker->text(80),
            'website' => $faker->url
        ]);
    }
);

$factory->define(App\Discipline::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence
    ];
});

$factory->defineAs(App\Discipline::class, 'withDates', 
    function (Faker\Generator $faker) use ($factory) {
        $discipline = $factory->raw(App\Discipline::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($discipline, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\Equipment::class, function (Faker\Generator $faker) {
    return [
        'id' => null,
        'facilityId' => null,
        'type' => $faker->text(150),
        'manufacturer' => null,
        'model' => null,
        'purpose' => '<p>' . $faker->text(1500) . '</p>',
        'purposeNoHtml' => null,
        'specifications' => null,
        'specificationsNoHtml' => null,
        'isPublic' => 1,
        'hasExcessCapacity' => 0,
        'yearPurchased' => null,
        'yearManufactured' => null,
        'keywords' => null
    ];
});

$factory->defineAs(App\Equipment::class, 'complete', 
    function (Faker\Generator $faker) use ($factory) {
        $equipment = $factory->raw(App\User::class);
        $purposeNoHtml = $faker->text(1500);
        $purpose = '<p>' . $specificationsNoHtml . '</p>';
        $specificationsNoHtml = $faker->text(1500);
        $specifications = '<p>' . $specificationsNoHtml . '</p>';

        return array_merge($equipment, [
            'manufacturer' => $faker->text(80),
            'model' => $faker->text(50),
            'purpose' => $purpose,
            'purposeNoHtml' => $purposeNoHtml,
            'specifications' => $specifications,
            'specificationsNoHtml' => $specificationsNoHtml,
            'yearPurchased' => $faker->year,
            'yearManufactured' => $faker->year,
            'keywords' => implode(', ', $faker->words(300))
        ]);
    }
);

$factory->define(App\Facility::class, function (Faker\Generator $faker) {
    return [
        'id' => null,
        'facilityRepositoryId' => null,
        'organizationId' => null,
        'provinceId' => null,
        'name' => $faker->company,
        'city' => $faker->city,
        'website' => null,
        'description' => '<p>' . $faker->text(1500) . '</p>',
        'descriptionNoHtml' => null
    ];
});

$factory->define(App\FacilityRepository::class, 
    function (Faker\Generator $faker) {
        return [
            'id' => null,
            'reviewerId' => null,
            'facilityId' => null,
            'state' => 'PENDING_APPROVAL',
            'reviewerMessage' => null,
            'data' => null
        ];
    }
);

$factory->define(App\FacilityUpdateLink::class, 
    function (Faker\Generator $faker) {
        $token = App\Http\Controllers\FacilityUpdateLinkController
            ::generateUniqueToken();

        return [
            'id' => null,
            'frIdBefore' => null,
            'frIdAfter' => null,
            'editorFirstName' => $faker->firstName,
            'editorLastName' => $faker->lastName,
            'token' => $token,
            'status' => 'OPEN',
            'dateOpened' => $faker->dateTimeThisMonth()
        ];
    }
);

$factory->define(App\Ilo::class, 
    function (Faker\Generator $faker) use ($dummyEmail) {
        return [
            'organizationId' => null,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $dummyEmail,
            'telephone' => $faker->numerify('##########'),
            'extension' => null,
            'position' => $faker->name,
            'website' => null
        ];
    }
);

$factory->defineAs(App\Ilo::class, 'withDates', 
    function (Faker\Generator $faker) use ($factory) {
        $ilo = $factory->raw(App\Ilo::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($ilo, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\Organization::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'isHidden' => 0
    ];
});

$factory->defineAs(App\Organization::class, 'withDates', 
    function (Faker\Generator $faker) use ($factory) {
        $organization = $factory->raw(App\Organization::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($organization, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\PrimaryContact::class, 
    function (Faker\Generator $faker) use ($dummyEmail) {
        return [
            'id' => null,
            'facilityId' => null,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $dummyEmail,
            'telephone' => $faker->numerify('##########'),
            'extension' => null,
            'position' => $faker->text(80),
            'website' => null
        ];
    }
);

$factory->defineAs(App\PrimaryContact::class, 'complete', 
    function (Faker\Generator $faker) use ($factory) {
        $primaryContact = $factory->raw(App\PrimaryContact::class);

        return array_merge($primaryContact, [
            'extension' => $faker->numerify('######'),
            'website' => $faker->url
        ]);
    }
);

$factory->define(App\Province::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(3),
        'isHidden' => 0
    ];
});

$factory->defineAs(App\Province::class, 'withDates', 
    function (Faker\Generator $faker) use ($factory) {
        $province = $factory->raw(App\Province::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($province, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\Sector::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence
    ];
});

$factory->defineAs(App\Sector::class, 'withDates',
    function (Faker\Generator $faker) use ($factory) {
        $sector = $factory->raw(App\Sector::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($sector, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'name'       => str_random(20),
        'permission' => rand(1, 10)
    ];
});

$factory->defineAs(App\Role::class, 'withDates', 
    function (Faker\Generator $faker) use ($factory) {
        $role = $factory->raw(App\Role::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($role, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\Setting::class, function(Faker\Generator $faker) {
    return [
        'name'             => str_random(20),
        'type'             => 'STRING',
        'value'            => $faker->sentence,
        'minAuthRoleOnGet' => 1,
        'minAuthRoleOnPut' => 1,
        'dateCreated'      => null,
        'dateUpdated'      => null
    ];
});

$factory->defineAs(App\Setting::class, 'withDates', 
    function (Faker\Generator $faker) use ($factory) {
        $setting = $factory->raw(App\Setting::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($setting, [
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);

$factory->define(App\User::class, 
    function (Faker\Generator $faker) use ($dummyEmail) {
        $email = explode('@', $dummyEmail);
        $email = $email[0] . str_random(10) . '@' . $email[1];

        return [
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $email,
            'password' => null,
            'isActive' => 1
        ];
    }
);

$factory->defineAs(App\User::class, 'withPasswordAndDates', 
    function (Faker\Generator $faker) use ($factory) {
        $user = $factory->raw(App\User::class);
        $date = $faker->dateTimeThisMonth();

        return array_merge($user, [
            'password' => Hash::make(str_random(10)),
            'dateCreated' => $date,
            'dateUpdated' => $date
        ]);
    }
);
