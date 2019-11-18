UPLOADER

suitcore / uploader 

Suitcore/Uploader/Upload
Suitcore/Uploader/ControllerUploaderTrait
Suitcore/Uploader/Contracts/ControllerUploaderInterface

use case :

Upload::file($fieldName);
Upload::file($fieldName)->saveAs('path/file');
Upload::file($fieldName)->isImage(); // boolean
Upload::file($fieldName)->to('s3');
Upload::file($fieldName)->to('s3', 'path/file');
Upload::file($fieldName)->delete();

Upload::model()

Upload::with($config)->file($fieldName);

