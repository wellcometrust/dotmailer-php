# Dotmailer REST API (v2) PHP client [![Build Status](https://travis-ci.org/wellcometrust/dotmailer-php.svg?branch=master)](https://travis-ci.org/wellcometrust/dotmailer-php)

PHP client library for the Dotmailer REST API (v2) described at https://developer.dotmailer.com/docs/

Installation
---
`composer require wellcometrust/dotmailer-php`

Usage
---
```
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client(
    [
        'base_uri' => 'https://r1-api.dotmailer.com',
        'auth' => [
            'apiuser-XYZ@apiconnector.com',
            'PASSWORD',
        ],
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ]
);

$dotmailer = new \Dotmailer\Dotmailer($client);
$addressBooks = $dotmailer->getAddressBooks();
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
    - [x] Update contact
    - [x] Add contact to address book
    - [x] Delete contact from address book
    - [x] Get contact by email
    - [x] Get contact address books
- [ ] **Programs**
    - [x] Get programs
    - [x] Create program enrolment
- [ ] **Transactional email**
    - [x] Send transactional email
    - [x] Send transactional email using a triggered campaign



