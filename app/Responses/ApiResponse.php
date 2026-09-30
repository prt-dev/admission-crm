<?php

namespace App\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Return a standardized success JSON response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    public static function success(
        mixed $data = null,
        ?string $message = null,
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'code' => $code,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        $response['data'] = $data;

        return response()->json($response, $code, $headers);
    }

    /**
     * Return a standardized error JSON response.
     *
     * @param string $message
     * @param int $code
     * @param mixed $data
     * @param array $headers
     * @return JsonResponse
     */
    public static function error(
        string $message = 'An error occurred.',
        int $code = Response::HTTP_BAD_REQUEST,
        mixed $data = null,
        array $headers = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $code, $headers);
    }

    /**
     * Return a standardized paginated JSON response.
     *
     * @param LengthAwarePaginator|Paginator $paginator
     * @param string|null $resourceClass Optional JsonResource class to transform items
     * @param string|null $message
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    public static function paginate(
        LengthAwarePaginator|Paginator $paginator,
        ?string $resourceClass = null,
        ?string $message = null,
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        $items = $paginator->items();

        if ($resourceClass !== null && class_exists($resourceClass)) {
            $items = $resourceClass::collection($items);
        }

        $data = [
            'items' => $items,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator instanceof LengthAwarePaginator ? $paginator->total() : null,
                'last_page' => $paginator instanceof LengthAwarePaginator ? $paginator->lastPage() : null,
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ];

        return self::success($data, $message, $code, $headers);
    }
}
