<?php

return [
    /**
     * The Petfinder API Access requires the use of an API Key and API Secret
     * The key and secret can be found in the [Developer Settings](https://www.petfinder.com/user/developer-settings/)
     */

    'key' => env('PETFINDER_API_KEY', ''),

    'secret' => env('PETFINDER_API_SECRET', ''),

    /**
     * This is the organization ID assigned by Petfinder. Adding the organization ID will limit
     * the results to the organizations animals.
     */

    'organization' => env('PETFINDER_ORGANIZATION_ID', ''),
];