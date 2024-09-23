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

// Define el ARN de tu bucket
$bucketArn = 'arn:aws:s3:::profile-photo-papeleria-cvf';
$bucketName = basename($bucketArn); // Extrae el nombre del bucket del ARN

function uploadProfileImageToS3($username, $file) {
    global $bucketName; // Utiliza el nombre del bucket definido anteriormente
    
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'message' => 'Error en el archivo subido.',
        ];
    }

    $client = getS3Client();
    $folder = "$username";
    $filename = basename($file['name']);
    $filepath = "$folder/$filename";

    try {
        $result = $client->putObject([
            'Bucket' => $bucketName,
            'Key'    => $filepath,
            'SourceFile' => $file['tmp_name'],
            'ACL'    => 'public-read',
        ]);

        return [
            'success' => true,
            'url' => $client->getObjectUrl($bucketName, $filepath),
            'message' => 'Imagen subida correctamente.',
        ];
    } catch (AwsException $e) {
        error_log($e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al subir la imagen: ' . $e->getMessage(),
        ];
    }
}

function deletePreviousProfileImageFromS3($username, $imageUri) {
    global $bucketName; // Utiliza el nombre del bucket definido anteriormente

    $client = getS3Client();

    $key = str_replace("https://$bucketName.s3.amazonaws.com/", "", $imageUri);

    try {
        $client->deleteObject([
            'Bucket' => $bucketName,
            'Key'    => $key,
        ]);
    } catch (AwsException $e) {
        error_log($e->getMessage());
    }
}
?>
