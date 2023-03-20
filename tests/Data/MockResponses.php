<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Data;

class MockResponses
{
    public static function collectionCount(): array
    {
        return [
            "member" => [
                [
                    "href" => "oslc/os/trim/_U1IvNTc1NA--",
                ],
                [
                    "href" => "oslc/os/trim/_U1IvNDc4Ng--",
                ],
                [
                    "href" => "oslc/os/trim/_U1IvNDQ5NQ--",
                ],
                [
                    "href" => "oslc/os/trim/_U1IvVFIx",
                ],
                [
                    "href" => "oslc/os/trim/_U1IvVFIyMDg-",
                ],
            ],
            "href" => "oslc/os/trim",
            "responseInfo" => [
                "nextPage" => [
                    "href" => "oslc/os/trim?pageno=2&oslc.where=nrassignedto%3D%22ATHOMP18%22&oslc.pageSize=5&lean=1&_dropnulls=0&collectioncount=1&relativeuri=1",
                ],
                "totalPages" => 70,
                "href" => "oslc/os/trim?lean=1&relativeuri=1&oslc.where=nrassignedto=%22ATHOMP18%22&oslc.pageSize=5&_dropnulls=0&collectioncount=1",
                "totalCount" => 349,
                "pagenum" => 1,
            ],
        ];
    }
    
    public static function createNoProperties(): array
    {
        return [
            '_rowstamp' => '4604955888',
            'href' => 'oslc/os/trim/_U1IvQUJFWTEyMzUw',
        ];
    }

    public static function createWithProperties(): array
    {
        return [
            '_rowstamp' => '4604955888',
            'description_longdescription' => 'This is a test to see if the create method works as expected',
            'description' => 'Maximo Query Test',
            'href' => 'oslc/os/trim/_U1IvQUJFWTEyMzUw',
            'ticketid' => 'ABEY12350',
        ];
    }

    public static function error404(): array
    {
        return [
            "Error" => [
                "extendedError" => [
                    "moreInfo" => [
                        "href" => "http://localhost/maximo/oslc/error/messages/BMXAA8727E"
                    ]
                ],
                "reasonCode" => "BMXAA8727E",
                "message" => "BMXAA8727E - The OSLC resource MXPERSON with the ID 11911 was not found as it does not exist in the system. In the database, verify whether the resource for the ID exists.",
                "statusCode" => "404"
            ]
        ];
    }

    public static function multiRecords(): array
    {
        return [
            'member' => [
                0 => [
                    'href' => 'oslc/os/mxperson/_T1lBTQ--',
                ],
                1 => [
                    'href' => 'oslc/os/mxperson/_SkRBUkNZ',
                ],
            ],
            'href' => 'oslc/os/mxperson',
            'responseInfo' => [
                'nextPage' => [
                    'href' => 'oslc/os/mxperson?pageno=2&oslc.pageSize=2&lean=1&relativeuri=1',
                ],
                'href' => 'oslc/os/mxperson?lean=1&relativeuri=1&oslc.pageSize=2',
                'pagenum' => 1,
            ],
        ];
    }

    public static function noResults(): array
    {
        return [
            'member' => [
            ],
            'href' => 'oslc/os/mxperson',
            'responseInfo' => [
                'href' => 'oslc/os/mxperson?lean=1&relativeuri=1&oslc.where=personid=%22sausage%22',
            ],
        ];
    }

    public static function paginationPageOne(): array
    {
        return [
            'member' => [
                0 => [
                    'href' => 'oslc/os/mxperson/_T1lBTQ--',
                ],
            ],
            'href' => 'oslc/os/mxperson',
            'responseInfo' => [
                'nextPage' => [
                    'href' => 'oslc/os/mxperson?pageno=2&oslc.pageSize=1&lean=1&_dropnulls=0&relativeuri=1',
                ],
                'href' => 'oslc/os/mxperson?lean=1&relativeuri=1&pageno=1&oslc.pageSize=1&_dropnulls=0',
                'pagenum' => 1,
            ],
        ];
    }

    public static function paginationPageTwo(): array
    {
        return [
            'member' => [
                0 => [
                    'href' => 'oslc/os/mxperson/_SkRBUkNZ',
                ],
            ],
            'href' => 'oslc/os/mxperson',
            'responseInfo' => [
                'previousPage' => [
                    'href' => 'oslc/os/mxperson?pageno=1&oslc.pageSize=1&lean=1&_dropnulls=0&relativeuri=1',
                ],
                'nextPage' => [
                    'href' => 'oslc/os/mxperson?pageno=3&oslc.pageSize=1&lean=1&_dropnulls=0&relativeuri=1',
                ],
                'href' => 'oslc/os/mxperson?lean=1&relativeuri=1&pageno=2&oslc.pageSize=1&_dropnulls=0',
                'pagenum' => 2,
            ],
        ];
    }

    public static function singleRecord(): array
    {
        return [
            'member' => [
                0 => [
                    'personuid' => 1191,
                    'firstname' => 'Christopher',
                    'plusgptwissuecounter' => 0,
                    'plusgperperfauth' => false,
                    'transemailelection_description' => 'Never Notify',
                    'loctoservreq' => true,
                    'brdacreatedby' => 'MAXIMO',
                    'language' => 'EN',
                    'plusgisospec' => false,
                    'plusgsolnapprover' => false,
                    'locale' => 'en_GB',
                    'plusgiscertified' => false,
                    'wfmailelection' => 'PROCESS',
                    'employeetype_description' => 'Employee',
                    '_rowstamp' => '2860813470',
                    'plusgauditapprover' => false,
                    'plusgmocrevapp' => false,
                    'wfmailelection_description' => 'Notify based on the process',
                    'personid' => 'CABEY',
                    'sms_collectionref' => 'oslc/os/mxperson/_Q0FCRVk-/sms',
                    'href' => 'oslc/os/mxperson/_Q0FCRVk-',
                    'department' => 'Business Systems Support (Development)',
                    'email' => [
                        0 => [
                            '_rowstamp' => '131475213',
                            'localref' => 'oslc/os/mxperson/_Q0FCRVk-/email/0-842',
                            'emailaddress' => 'christopher.abey@networkrail.co.uk',
                            'emailid' => 842,
                            'href' => 'http://childkey#UEVSU09OL0VNQUlML2NocmlzdG9waGVyLmFiZXlAbmV0d29ya3JhaWwuY28udWs-',
                            'isprimary' => true,
                        ],
                    ],
                    'plusgrcfaapprover' => false,
                    'email_collectionref' => 'oslc/os/mxperson/_Q0FCRVk-/email',
                    'employeetype' => 'EMPLOYEE',
                    'status_description' => 'Active',
                    'driverarchived' => false,
                    'plusgtechauthority' => false,
                    'plusgperareaauth' => false,
                    'phone_collectionref' => 'oslc/os/mxperson/_Q0FCRVk-/phone',
                    'transemailelection' => 'NEVER',
                    'authtodrive' => false,
                    'lastname' => 'Abey',
                    'statusdate' => '2011-12-14T14:30:56+00:00',
                    'jobcode_description' => 'User',
                    'nrcontractcomp' => 'NR',
                    'statusiface' => false,
                    'locale_description' => 'English (United Kingdom)',
                    'acceptingwfmail' => true,
                    'plusgfinauthority' => false,
                    'displayname' => 'Christopher Abey',
                    'jobcode' => 'USER',
                    'driverpoints' => 0,
                    'plusgperissueauth' => false,
                    'primaryemail' => 'christopher.abey@networkrail.co.uk',
                    'supervisor' => 'ATHOMP18',
                    'status' => 'ACTIVE',
                ],
            ],
            'href' => 'oslc/os/mxperson',
            'responseInfo' => [
                'href' => 'oslc/os/mxperson?oslc.select=*&oslc.where=personid=%22cabey%22&lean=1&relativeuri=1',
            ],
        ];
    }

    public static function updateNoProperties(): array
    {
        return [
            '_rowstamp' => '4604956590',
            'href' => 'oslc/os/trim/_U1IvQUJFWTEyMzUx',
        ];
    }

    public static function updateWithProperties(): array
    {
        return [
            '_rowstamp' => '4604956590',
            'description_longdescription' => 'This is a test to see if the create method works as expected',
            'description' => 'Maximo Query Test Update',
            'href' => 'oslc/os/trim/_U1IvQUJFWTEyMzUx',
            'ticketid' => 'ABEY12351',
        ];
    }
}
