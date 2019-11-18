<?php

namespace App\Http\Middleware;

use Closure;

class JsonUTF8Response
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        ];

        $response =  $next($request);

        if (method_exists($response, 'getData')) {
            $dataArray = $response->getData();

            $decodedArray = $this->array_map_recursive('html_entity_decode', $dataArray);

            $response->setData($decodedArray);

            $response->setJsonOptions(JSON_UNESCAPED_SLASHES);

            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }

            return $response;
        } else {
            return response()->json([
                'status'  => '50',
                'message' => 'System error! Please access again later.',
            ]);
        }
    }

    private function array_map_recursive(callable $func, $arr) {
        array_walk_recursive($arr, function(&$v) use ($func) {
            $v = is_null($v) || is_numeric($v) || is_bool($v) ? $v : (is_string($v) ? $func($v) : $this->array_map_recursive($func, $v));
        });
        return $arr;
    }
}
