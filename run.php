<?php

require_once 'vendor/autoload.php';
$config = include('config.php');

$client = new \Github\Client();
$client->authenticate($config['authToken'], null, Github\Client::AUTH_HTTP_TOKEN);

$commiterParams = array('name' => $config['commiterName'], 'email' => $config['commiterEmail']);
$dumpContents = "";

if(count($config['tags']) > 0) {
    foreach($config['tags'] as $workingTag) {
        $dumpContents = "";
        try {
            $dumpCommand = $config['rootToMysqlDump'] . " -P " . $workingTag['port'] . " -h " . $workingTag['host'] . " -d -u " . $workingTag['user'] . " -p" . $workingTag['password'] . " -R " . $workingTag['databaseName'];
            $dumpContents = shell_exec($dumpCommand);
        } catch(Exception $exception) {
            echo "There was error during dumping DB: " . $exception->getMessage();
        }

        try {
            $fileExists = $client->api('repo')->contents()->exists(
                $config['gitHubAccount'],
                $workingTag['tag'],
                $workingTag['databaseName'] . ".sql"
            );

            if($fileExists == 1) {
                $fileFound = $client->api('repo')->contents()->show(
                    $config['gitHubAccount'],
                    $workingTag['tag'],
                    $workingTag['databaseName'] . ".sql",
                    $workingTag['branchName']
                );

                $fileEdit = $client->api('repo')->contents()->update(
                    $config['gitHubAccount'],
                    $workingTag['tag'],
                    $workingTag['databaseName'] . ".sql",
                    $dumpContents,
                    $config['commitMessage'] . " - " . date("d-m-Y H:i:s"),
                    $fileFound['sha'],
                    $workingTag['branchName'],
                    $commiterParams
                );
            } else {
                $fileInfo = $client->api('repo')->contents()->create(
                    $config['gitHubAccount'],
                    $workingTag['tag'],
                    $workingTag['databaseName'] . ".sql",
                    $dumpContents,
                    $config['commitMessage'] . " - " . date("d-m-Y H:i:s"),
                    $workingTag['branchName'],
                    $commiterParams
                );
            }
        } catch (Exception $exception) {
            echo "There was error during working with GitHub API: " . $exception->getMessage();
        }
    }
}