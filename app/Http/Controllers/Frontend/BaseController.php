<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    // ATTRIBUTES

    // METHODS
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->detectGlobalParam();
    }

    /**
     * Setup the layout used by the controller.
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * Detect global parameter if any.
     */
    protected function detectGlobalParam()
    {
        // Global Params
    }

    public function notify($type, $msg = null)
    {
        $msg && session()->put($type, $msg);
    }

    public function notifySuccess($msg = null)
    {
        $this->notify('success', $msg);
    }

    public function notifyMessage($msg = null)
    {
        $this->notify('message', $msg);
    }

    public function notifyError($msg = null)
    {
        $this->notify('error', $msg);
    }
}
