# Tepilo

Framework: Laravel

Server: Localhost server (php artisan serve)

Programs used: phpstorm (IDE), postman (API client),  gitbash (as terminal) 

Packages used: guzzlehttp (docs http://docs.guzzlephp.org/en/stable/), laravel-ide-helper(helper for phpstorm)


I created an API application which passes 2 post requests. The first request should pass an email address, validate it and search the Close. io API to find the Lead. The second post request is to create a Lead from scratch.

To find a Lead by email address, post a json object which passes email parameter to url below:

http://localserver.com/api/v1/find-lead/:	

{
     “email”:  “email@address.com”
}

The email address is required and validated In order to make this request. When querying from close.io the email address will be urlencoded to be passed as a url.

If a lead is found the custom field (number of valuations) will be updated with an incremented number

To create a new lead, post the json object below to the corresponding url below:

http://localserver.com/api/v1/create-lead/

{
    "name": "Example Lead",
    "url": "www.tepilo.com",
    "description": "",
    "status_id": "stat_yE4J4QxxowV6IKNI931O7RrbtTn3iQtYwS9u52l4D2P",
    "contacts": [
        {
            "name": "Example",
            "emails": [
                {
                    "type": "office",
                    "email": "example@example.com"
                }
            ],
            "phones": [
                {
                    "type": "office",
                    "phone": "012345123123"
                }
            ]
        }
    ]
}

There is no required validation on the name, url, description fields, will be validated to match a correct type. Null is also acceptable.

The contact fields can accept an array of contacts, emails and phones, depending on number of mini-object included.

The email and phone fields will be required per mini-object added.
