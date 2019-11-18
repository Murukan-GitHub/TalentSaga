<?php

namespace App\Repositories\Contracts;

use App\Models\ContentType;

interface ContentTypeRepositoryContract
{
    public function jsonDatatable($param, $columnFormatted);

    public function get($objectId);

    public function create($param, ContentType &$contentType);

    public function update($id, $param, ContentType &$contentType);

    public function delete($id, ContentType &$contentType);
}
