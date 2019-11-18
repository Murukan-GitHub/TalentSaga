<?php

namespace Suitcore\Controllers;

use Carbon\Carbon;
use HTML;
use Input;
use Suitcore\Models\SuitModel;
use Suitcore\Repositories\SuitRepository;

class ApiController extends Controller
{
    /**
     * @var \App\Models\User
     */
    protected $authUser;

    // Default Services / Repository
    protected $repository;

    protected $baseModel;

    protected $defaultParams = [];

    /**
     * Initialize new Controller instance.
     *
     * @param SuitRepository $repository
     * @param SuitModel      $model
     */
    public function __construct(SuitRepository $repository, $model)
    {
        $this->repository = $repository;
        $this->baseModel = $model;

        self::globalXssClean();

        $this->detectIso8601Inputs();
    }

    /**
     * Response json.
     *
     * @param int $status
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function toJson($status, array $data = [])
    {
        $data = array_merge(['status' => (int) $status], $data);

        $data['result'] = array_key_exists('result', $data) ? $data['result'] : null;

        return response()->json($data);
    }

    /**
     * [getClientMedia description]
     * @return [type] [description]
     */
    protected function getClientMedia()
    {
        return 'android/ios';
    }

    /**
     * [validateIso8601 description]
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    protected function validateIso8601($date)
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false) {
                return false;
            }

            return $input_time == $time;
        } else {
            return false;
        }
    }

    /**
     * [detectIso8601Inputs description]
     * @return [type] [description]
     */
    protected function detectIso8601Inputs()
    {
        // echo (date_default_timezone_get());
        // print_r(Input::all());
        $params = [];
        foreach (Input::all() as $key => $value) {
            if (!$this->validateIso8601($value)) {
                continue;
            }
            // if Iso8601 change to UTC
            if (($datetime = Carbon::createFromFormat(Carbon::ISO8601, $value)) !== false) {
                $params[$key] = $datetime->tz('UTC');
            }
        }

        // merge to Input
        Input::merge($params);
    }

    /**
     * Check user logged
     *
     * @param  integer  $user_id
     *
     * @return boolean
     */
    protected function isUserLogged($user_id)
    {
        if ($this->authUser) {
            if ($this->authUser->id == $user_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get: Index Json (Array Json Data)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getIndex()
    {
        $param = array_merge($this->defaultParams, Input::all());

        $objects = $this->repository->getByParameter($param);

        if ($objects != null && count($objects) > 0) {
            if (request()->has('token')) {
                $token = request()->get('token');
                $objects->appends(compact('token'));
            }

            return $this->toJson(20, [
                'message' => 'List of ' . $this->baseModel->getLabel() . ' retrieved!',
                'result'  => $objects->toArray(),
            ]);
        }

        return $this->toJson(20, [
            'message' => 'Empty list of ' . $this->baseModel->getLabel() . '!',
        ]);
    }

    /**
     * Get: Detail Json (Object Json Data)
     *
     * @param  integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getDetail($id)
    {
        if ($object = $this->repository->get($id)) {
            return $this->toJson(20, [
                'message' => 'Detail of ' . $this->baseModel->getLabel() . ' #' . $id . ' retrieved!',
                'result'  => $object['object']
            ]);
        }

        return $this->toJson(30, [
            'message' => 'Couldn\'t  find detail for this ' . $this->baseModel->getLabel() . ' #' . $id
        ]);
    }

    /**
     * Create new data object model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function postCreate()
    {
        $param = Input::all();
        $baseObj = $this->baseModel;

        if ($result = $this->repository->create($param, $baseObj)) {
            return $this->toJson(20, [
                'message' => 'Data ' . $baseObj->getLabel() . ' has been created!',
                'result'  => $result,
            ]);
        }
        return $this->toJson(30, [
            'message' => 'Error when creating ' . $baseObj->getLabel() . ' data!',
            'result'  => $baseObj->errors,
            'param' => $param
        ]);
    }

    /**
     * Update data exist
     *
     * @param integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function postUpdate($id)
    {
        $param = Input::all();
        $baseObj = $this->baseModel;

        if ($result = $this->repository->update($id, $param, $baseObj)) {
            return $this->toJson(20, [
                'message' => 'Data ' . $baseObj->getLabel() . ' has been updated!',
                'result'  => $result,
            ]);
        }

        return $this->toJson(30, [
            'message' => 'Error when updating ' . $baseObj->getLabel() . ' data!',
            'result'  => $baseObj->errors,
            'param' => $param
        ]);
    }

    /**
     * Delete existed data
     *
     * @param integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function postDelete($id)
    {
        $baseObj = $this->baseModel;

        if ($result = $this->repository->delete($id, $baseObj)) {
            return $this->toJson(20, [
                'message' => 'Data ' . $baseObj->getLabel() . ' has been deleted!',
                'result'  => $result,
            ]);
        }

        return $this->toJson(30, [
            'message' => 'Error when deleting ' . $baseObj->getLabel() . ' data!',
            'result'  => $baseObj->errors
        ]);
    }

    /*
     * Method to strip tags globally.
     */
    public static function globalXssClean()
    {
        // Recursive cleaning for array [] inputs, not just strings.
        $sanitized = static::arrayStripTags(Input::get());
        Input::merge($sanitized);
    }

    /**
     * [arrayStripTags description]
     * @param  [type] $array [description]
     * @return [type]        [description]
     */
    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = HTML::entities($key);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(HTML::entities($value));
            }
        }

        return $result;
    }
}
