<?php

namespace Suitcore\Repositories\Contract;

use Suitcore\Models\SuitModel;

interface SuitRepositoryContract
{
    public function jsonDatatable($param, $columnFormatted);

    public function get($objectId);

    public function create($param, SuitModel &$object);

    public function update($id, $param, SuitModel &$object);

    public function delete($id, SuitModel &$object);
}
