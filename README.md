ISSFinder - avanti engineer test
=====================
ISSFinder is a simple microservice built with Symfony3.4, it supplies two endpoints for finding the current position of the ISS
and estimating the distance between that current position and any given latitude and longitude.

It employs the jsonrpc 2.0 standard for handling requests via POST, example (raw body) requests are provided below.

The service ISSFinderApi serves as the main controller of the application, and supporting services are supplied via
symfony Dependency Injection. An inheritance structure was devised for 'Calculators' in case alternative strategies for 
formulae or units of measurement are required in future. DI also permits the code to be highly testable.

## System requirements
* PHP 7.1+
* composer
* git
* Unix/Linux OS (e.g. Ubuntu)

## Installation
* Clone the repository:

`` git clone https://github.com/VindoViper/iss-finder.git ``

* Install dependencies:

`` cd iss-finder ``
 
`` composer install ``

* Ensure expected values for '%data_source_base_url%' and '%iss_satellite_id%'
  are copied from app/config/parameters.yml.dist to app/config/parameters.yml
  
* Clear cache if necessary:

`` sudo rm -rf var/cache/* ``

* Start the built-in webserver

`` bin/console server:start``

  The project can now be reached at http://localhost:8000/jsonrpc/

## Running the tests

`` vendor/bin/phpunit ``

## Usage Examples

### getCurrentISSPosition()
#### Request
```$json
{
    "jsonrpc": "2.0",
    "method": "iss_finder.api:getCurrentISSPosition",
    "params": {},
    "id": null
}
```

#### Response
```$json
{
  "jsonrpc": "2.0",
  "result": {
    "name": "iss",
    "id": 25544,
    "latitude": -26.945324566203,
    "longitude": -27.110651644246,
    "altitude": 413.72341343087,
    "velocity": 27584.263909667,
    "visibility": "daylight",
    "footprint": 4475.3921547472,
    "timestamp": 1542734992,
    "daynum": 2458443.2290741,
    "solar_lat": -19.788809983855,
    "solar_lon": 273.95409268675,
    "units": "kilometers"
  },
  "id": null
}
```

### getDistanceToISSInKilometers()
#### Request
```$json
{
    "jsonrpc": "2.0",
    "method": "iss_finder.api:getDistanceToISSInKilometers",
    "params": {
        "latitude": -26.945324566203,
        "longitude": -27.110651644246
    },
    "id": null
}
```

### Response
```$json
{
  "jsonrpc": "2.0",
  "result": 7614.5135,
  "id": null
}
```

## TODO

* Add validation for input parameters, type hints are relied upon for this, proper validation is preferable.
* Delegate test data to fixtures.
* Add more test cases.
* More comprehensive error handling.
