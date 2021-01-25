# Installation Guide

## Server requirements
    PHP >= 7.2.5
    BCMath PHP Extension
    Ctype PHP Extension
    Fileinfo PHP extension
    JSON PHP Extension
    Mbstring PHP Extension
    OpenSSL PHP Extension
    PDO PHP Extension
    Tokenizer PHP Extension
    XML PHP Extension

## Installation steps
    Clone this repo
    Create a database which you will use for the project
    cd into the project folder
    rename .env.example into .env
    populate the .env with your db info
    run composer install (post install script will migrate the tables and seed the db)
    run php artisan key:generate
    run npm install
    run php artisan serve
    
 You can test the behaviour from the dashboard, open the app in your local enviroment or try this demo: https://stake-limiter.herokuapp.com/


# API documentation

## Get the status for a specific device based on the global configuration.

**URL** : `/api/checkLimit`

**Method** : `POST`

**Request examples**

```json
{
    "id": "ef65ef3e-d212-3137-7f56-ec533cb7759d",
    "deviceId": "6c834452-d73f-3e63-9cf6-31372da42078",
    "stake": 300.99
}
```

**Data constraints**

```json
{
    "id": "(uuid)",
    "deviceId": "(uuid)",
    "stake": "(double)"
}
```

## Success Response

**Code** : `200`

**Content examples**

For a device which is below all limits.

```json
{
    "status": "OK"
}
```

For a device which has sum of stakes higher or equal {config hot amount} in the last {config time duration}

```json
{
   "status": "HOT"
}
```

For a device which has sum of stakes higher or equal {config stake limit} in the last {config time duration}

```json
{
   "status": "BLOCKED"
}
```

### Notes

* If the device sent in the request body is not in the database, it will be created for future tracking.


## Change the global configuration.

**URL** : `/api/config/update`

**Method** : `POST`

**Request examples**

For a device which is below all limits.

```json
{
    "stakeLimit": 1000,
    "timeDuration": 500,
    "hotAmountPctg": 80,
    "restrExpiry": 1800
}
```

**Data constraints**

```json
{
    "stakeLimit": "required|numeric|min:1|max:10000000",
    "timeDuration": "required|numeric|min:300|max:86400",
    "hotAmountPctg": "required|numeric|min:1|max:100",
    "restrExpiry": "required|numeric"
}
```

## Success Response

**Code** : `200`

**Content examples**


```json
{
    "Configuration updated."
}
```

## Validation error response

**Code** : `422`

**Content examples**


```json
{
    "message": "The given data was invalid.",
    "errors": {
        "stakeLimit": [
            "The stake limit must be at least 1."
        ],
        "timeDuration": [
            "The time duration must be at least 300."
        ],
        "hotAmountPctg": [
            "The hot amount pctg must be at least 1."
        ],
        "restrExpiry": [
            "The restr expiry must be a number."
        ]
    }
}
```

## Notes

* If the restrExpiry is 0 device will not be unblocked automatically.
