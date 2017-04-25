<?php
/*
 * A PHP file for supporting uploading of content, and then
 * 3shrinking it
 */

function upload_setup() {
  $client = upload_s3_client();
  $client->registerStreamWrapper();
}
function upload_s3_client() {
  return new Aws\S3\S3Client([ 'version' => 'latest',
                               'region'  => 'us-east-1']);
}

function is_upload_request() {
  return (g($_SERVER, 'REQUEST_METHOD') == 'POST' &&
          g($_SERVER, 'CONTENT_TYPE')   == 'application/octet-stream' && 
          geek_val()                    == trim(file_get_contents(__DIR__ . '/.geek.key')));
}

function do_upload() {
  $name    = md5(g($_SERVER, 'CONTENT_LENGTH', 0) . microtime(true) . rand(10000,99999) . $_SERVER['SERVER_NAME']);
  $in_fd   = fopen("php://input", 'r');
  $out_fd  = fopen("s3://files.3shrink.com/$name", 'w');
  while($data = fgets($in_fd)) {
    fputs($out_fd, $data);
  }
  fclose($in_fd);
  fclose($out_fd);

  $client = upload_s3_client();
  return $client->getObjectUrl('files.3shrink.com', $name);
}


?>