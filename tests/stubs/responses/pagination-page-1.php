<?php

return [
    'prefixes' =>
        [
            'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
            'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
            'oslc' => 'http://open-services.net/ns/core#',
        ],
    'oslc:responseInfo' =>
        [
            'oslc:nextPage' =>
                [
                    'rdf:resource' => 'http://localhost/maximo/oslc/os/mxperson?pageno=2&oslc.pageSize=1&_dropnulls=0',
                ],
            'pagenum' => 1,
            'rdf:about' => 'http://localhost/maximo/oslc/os/mxperson?oslc.pageSize=1&_dropnulls=0',
        ],
    'rdfs:member' =>
        [
            0 =>
                [
                    'rdf:resource' => 'http://localhost/maximo/oslc/os/mxperson/_T1lBTQ--',
                ],
        ],
    'rdf:about' => 'http://localhost/maximo/oslc/os/mxperson',
];
