# thankster
Laravel wrapper for thankster api integration.

#Api Documentation
For documentation on the api please refer to http://www.thankster.com/api-documentation/ 

## Installation

Open `composer.json` and add this line below.

```json
{
    "require": {
        "riazxrazor/thankster": "^1.0.0"
    }
}
```

Or you can run this command from your project directory.

```console
composer require riazxrazor/thankster
```

## Configuration

Open the `config/app.php` and add this line in `providers` section.

```php
Riazxrazor\Thankster\ThanksterServiceProvider::class,
```

add this line in the `aliases` section.

```php
'Thankster' => Riazxrazor\Thankster\ThanksterFacade::class

```

get the `config` by running this command.

```console
php artisan vendor:publish --tag=config
```

config option can be found `app/thankster.php`

```
   
    'API_KEY' => 'Thankster API KEY',

    'DEBUG' => FALSE
```

## Basic Usage

You can use the function like this.

```php
// Load, create, or update a user account.
\Thankster::findOrCreateUserByEmail([
                                          'email'     => 'iframetester@igicom.com',
                                          'fname'     => 'Michael',
                                          'lname'     => 'Scharf',
                                          'address'   => '123 Road',
                                          'address2'  => 'Apartment 5F',
                                          'city'      => 'New Martinsville',
                                          'state'     => 'WV',
                                          'zip'       => '26155',
                                          'company'   => 'Igicom LLC'
                                      ])->getResponse();


// This creates a new Project with a single card in it.
\Thankster::createCardProject([
                                        'templateID'=> 1433354,
                                        'thanksterUserID'=>7655,
                                        'r_fname'     => 'Michael',
                                        'r_lname'     => 'Scharf',
                                        'r_company'   => 'Igicom LLC',
                                        'r_address'   => '123 Road',
                                        'r_address2'  => 'Apartment 5F',
                                        'r_city'      => 'New Martinsville',
                                        'r_state'     => 'WV',
                                        'r_zip'       => '26155',
                                        'r_country'   => 'US',
                                        'r_email'     => 'iframetester@igicom.com'
                            
                                      ])->getResponse();
\Thankster::applyMessages([
                                'thanksterRecipientID' => 1221,
                                'thanksterUserID' => 1,
                                'inside1' => "TEXT",
                                'inside2' => "TEXT 2"
                            ])->getResponse();
                         
                            
\Thankster::orderProject([
                             'thanksterProjectID' => 1212,
                             'thanksterUserID' => 1,
                         ])->getResponse();
                         
                         
                         
\Thankster::setPartnerOrderID([
                              'thanksterOrderID' => 1212,
                              'orderID' => 34,
                          ])->getResponse();
  
                          
\Thankster::approveForPrinting([
                              'thanksterOrderID' => 1212,
                              'orderID' => 34,
                          ])->getResponse();
                                      


```