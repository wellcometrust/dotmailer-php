# Dotmailer REST API (v2) PHP client [![Build Status](https://travis-ci.org/wellcometrust/dotmailer-php.svg?branch=master)](https://travis-ci.org/wellcometrust/dotmailer-php)

PHP client library for the Dotmailer REST API (v2) described at https://developer.dotmailer.com/docs/

Installation
---
`composer require wellcometrust/dotmailer-php`

Usage
---
```
<?php

use Dotmailer\Adapter\GuzzleAdapter;
use Dotmailer\Dotmailer;

require 'vendor/autoload.php';

$adapter = GuzzleAdapter::fromCredentials('apiuser-XYZ@apiconnector.com', 'PASSWORD');
$dotmailer = new Dotmailer($adapter);

$addressBooks = $dotmailer->getAddressBooks();
print_r($addressBooks);
```

If you are using Symfony, you may choose to configure the service as follows, and then use throughout your application:
```
# app/config/services.yml

Dotmailer\Adapter:
   factory: ['Dotmailer\Adapter\GuzzleAdapter', fromCredentials]
   arguments: ['%dotmailer_username%', '%dotmailer_password%', '%dotmailer_uri%']

Dotmailer\Dotmailer:
   arguments: ['@Dotmailer\Adapter']
```

Coverage
---
Currently the following endpoints are covered:

- [ ] **Address books**
    - [x] Get address books
- [ ] **Campaigns**
    - [x] Get all campaigns
    - [x] Get campaign
- [ ] **Contacts**
    - [x] Create contact
    - [x] Delete contact
    - [x] Update contact
    - [x] Add contact to address book
    - [x] Delete contact from address book
    - [x] Get contact by email
    - [x] Get contact address books
    - [x] Get unsubscribed contacts since date
    - [x] Unsubscribe contact
    - [x] Resubscribe contact
    - [x] Resubscribe contact with no challenge
    - [x] Get suppressed contacts since date
    - [x] Bulk create contacts in address book
    - [x] Get contact import status
    - [x] Get contact import report
- [ ] **Contact data fields**
    - [x] Create contact data field
    - [x] Delete contact data field
- [ ] **Programs**
    - [x] Get programs
    - [x] Create program enrolment
- [ ] **Transactional email**
    - [x] Send transactional email
    - [x] Send transactional email using a triggered campaign



