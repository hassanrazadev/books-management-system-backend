<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $status       = true;
    protected $message      = '';
    protected $statusCode   = '200';
    protected $data         = [];

    /**
     * @return bool
     */
    public function getStatus(): bool {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode(string $statusCode): void {
        $this->statusCode = $statusCode;
    }

    /**
     * @return array
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void {
        $this->data = $data;
    }




    /**
     * @param array $params
     * @return $this
     */
    public function setApiResponse(array $params): Controller {
        $this->message = $params['message'];
        $this->status = $params['status'];
        $this->statusCode = $params['statusCode'];
        $this->data = $params['data'];
        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function getApiResponse(): JsonResponse {
        return response()->json([
            'status' => $this->status,
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data
        ], $this->statusCode);
    }

}
