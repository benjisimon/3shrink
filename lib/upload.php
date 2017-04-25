<?php
/*
 * A PHP file for supporting uploading of content, and then
 * 3shrinking it
 */


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
  $in_fd     = fopen("php://input", 'r');
  $buffer    = tempnam("/tmp", "upload");
  $buffer_fd = fopen($buffer, 'w');

  while($data = fgets($in_fd)) {
    fputs($buffer_fd, $data);
  }
  fclose($in_fd);
  fclose($buffer_fd);

  $info      = finfo_open();
  $mime_type = finfo_file($info, $buffer, FILEINFO_MIME_TYPE);
  $name      = md5_file($buffer);

  $s3 = upload_s3_client();
  if(!$s3->doesObjectExist(UPLOAD_BUCKET, $name)) {
    $s3->putObject(['Bucket'      => UPLOAD_BUCKET,
                    'Key'         => $name,
                    'SourceFile'  => $buffer,
                    'ContentType' => $mime_type]);
                    
  }
  return $s3->getObjectUrl(UPLOAD_BUCKET, $name);


}


?>