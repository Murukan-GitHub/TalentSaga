<?php

namespace App\Repositories\Contracts;

use App\Models\Content;

interface FaqRepositoryContract
{
    public function jsonDatatable($param, $columnFormatted);

    public function get($objectId);

    public function create($param, Content &$content);

    public function update($id, $param, Content &$content);

    public function delete($id, Content &$content);
}
