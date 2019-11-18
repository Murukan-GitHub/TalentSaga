<?php

namespace App\Models;

use Suitcore\Models\SuitModel;

/**
email_verifications :
- id
- email
- activation_code
- created_at (datetime)
- updated_at (datetime)
**/
class EmailVerification extends SuitModel
{
    // CONSTANT
    // --- USER STATUS
    const REGISTERED = "registered";
    const VERIFIED = "verified"; 


 	// ATTRIBUTES
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_verifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['activation_code', 'email', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = [];
	
	
	// METHODS	
}
