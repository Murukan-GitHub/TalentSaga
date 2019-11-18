<?php

namespace App\Repositories\Contracts;

use App\Models\ContentCategory;

interface ContentCategoryRepositoryContract
{
    public function jsonDatatable($param, $columnFormatted);

    public function get($objectId);

    public function create($param, ContentCategory &$contentCategory);

    public function update($id, $param, ContentCategory &$contentCategory);

    public function delete($id, ContentCategory &$contentCategory);
}
