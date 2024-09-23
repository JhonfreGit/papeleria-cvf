<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function getS3Client() {
    return new S3Client([
        'version' => 'latest',
        'region'  => 'us-east-1',
        'credentials' => [
            'key'    => getenv('AWS_ACCESS_KEY'),
            'secret' => getenv('AWS_SECRET_KEY'),
        ],
    ]);
}

function uploadProfileImageToS3($username, $file) {
    $bucket = 'profile-photo-papeleria-cvf';
    $client = getS3Client();
    $folder = "$username";
    $filename = basename($file['name']);
    $filepath = "$folder/$filename";

    try {
        $client->putObject([
            'Bucket' => $bucket,
            'Key'    => $filepath,
            'SourceFile' => $file['tmp_name'],
            'ACL'    => 'public-read',
        ]);
        
        return $client->getObjectUrl($bucket, $filepath);
    } catch (AwsException $e) {
        error_log($e->getMessage());
        return false;
    }
}

function deletePreviousProfileImageFromS3($username, $imageUri) {
    $bucket = 'profile-photo-papeleria-cvf';
    $client = getS3Client();

    $key = str_replace("https://$bucket.s3.amazonaws.com/", "", $imageUri);

    try {
        $client->deleteObject([
            'Bucket' => $bucket,
            'Key'    => $key,
        ]);
    } catch (AwsException $e) {
        error_log($e->getMessage());
    }
}
?>