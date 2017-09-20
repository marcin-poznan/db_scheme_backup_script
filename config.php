<?php

return [
    'authToken'         => '', // GH auth token
    'gitHubAccount'     => '', // GH account name
    'commiterName'      => '', // name
    'commiterEmail'     => '', // email
    'commitMessage'     => '', // some text
    'rootToMysqlDump'   => '', // for example: /usr/bin/mysqldump
    'tags'              => [ // array of arrays
        [
            'tag'           => '', // tag name, can be repo name
            'port'          => '', // port
            'host'          => '', // host
            'user'          => '', // user name
            'password'      => '', // password
            'databaseName'  => '', // database name
            'branchName'    => ''  // branch name
        ],
    ]
];
