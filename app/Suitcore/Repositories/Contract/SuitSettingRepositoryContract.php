<?php

namespace Suitcore\Repositories\Contract;

interface SuitSettingRepositoryContract
{
	public function updateByKey($key, $value);

	public function getValue($key, $default);

    public function save($settingArray);
}
