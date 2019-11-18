<?php 

namespace Suitcore\Notification\Adapters\GcmAdapter;

interface GcmRecipientInterface
{
    public function addGcmRegistrationId($gcm_registration_id);
	public function removeGcmRegistrationId($gcm_registration_id);
	public function getGcmRegistrationIds();
}