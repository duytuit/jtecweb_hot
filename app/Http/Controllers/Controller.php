<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
       /**
     * Return success response
     *
     * @param null $items
     * @param int $status
     *
     * @return JsonResponse
     */
    public function success($items = null, $status = 200)
    {
        $data = ['status' => 'success'];
        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        }
        if ($items) {
            foreach ($items as $key => $item) {
                $data[$key] = $item;
            }
        }

        return response()->json($data, $status);
    }

    /**
     * Used to return error response
     *
     * @param null $items
     * @param int $status
     *
     * @return JsonResponse
     */
    public function error($items = null, $status = 422)
    {
        $data = [];
        if ($items) {
            foreach ($items as $key => $item) {
                $data['errors'][$key][] = $item;
            }
        }

        return response()->json($data, $status);
    }
    public function per_page(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        Cookie::queue('per_page', $per_page, 60 * 24 * 30);
        return back();
    }
}
