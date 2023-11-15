<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

class ResponseHandler
{
    use HttpTraits;

    private RequestHandler $request;
    private CollectionInterface $headers;
    private ?string $content;
    private ?string $protocol;
    private ?HttpStatus $status;
    private bool $prepared = false;

    public function __construct(string $content = '', string $status = 'HTTP_OK', array $headers = [])
    {
        $this->status = HttpStatus::getStatus($status);
        $this->headers = new Collection($headers);
        $this->content = $content;
    }

    public function send() : void
    {
        if (!$this->prepared) {
            throw new ResponseException('Response need to be prepared');
        }
        $this->sendAllHeaders();
        if ($this->request->isAjax()) {
            $this->jsonResponse([$this->content]);
        } else {
            echo $this->content;
            exit();
        }
    }

    public function addHeader(string $name, string $value) : self
    {
        $this->headers->offsetGet($name, $value);

        return $this;
    }

    public function addHeaders(array $headers) : self
    {
        $this->headers->addAll($headers);

        return $this;
    }

    public function handler() : Response
    {
        if (!isset($response)) {
            $response = Container::getInstance()->make(Response::class);
            if ($response) {
                return $response;
            }
        }

        return false;
    }

    public function content(string $content) : void
    {
        $this->content = $content;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function setStatusCode(int $code) : self
    {
        http_response_code($code);

        return $this;
    }

    public function redirect(string $url, ?string $refreshtime = null) : string
    {
        if (headers_sent()) {
            echo "<script>document.location.href='" . $url . "'</script>";
        } elseif (isset($refreshtime)) {
            header('refresh:' . $refreshtime . ';url=' . $url);
        } else {
            header('Location:' . $url);
        }

        return $url;
        die();
    }

    public function is_image(string $file)
    {
        if (@is_array(getimagesize($file))) {
            $image = true;
        } else {
            $image = false;
        }
    }

    public function jsonResponse(array $resp) : void
    {
        $this->sendJsonHeader();
        $this->setResponseCode(200);
        echo json_encode($resp);
        exit;
    }

    public function sendJsonHeader() : void
    {
        header('Content-Type: application/json;charset=utf-8');
    }

    public function setResponseCode(int $code) : self
    {
        http_response_code($code);
        return $this;
    }

    public function htmlDecodeArray(array $array = []) : array
    {
        $r = [];
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                if (is_string($value)) {
                    $value = StringUtil::htmlDecode($value);
                }
                $r[$key] = $value;
            }
        }
        return empty($r) ? $array : $r;
    }

    public function prepare(RequestHandler $request) : self
    {
        $this->request = $request;
        $this->protocol = $this->request->getProtocol();
        if (!$this->headers->has('Content-Type')) {
            $this->headers->offsetSet('Content-Type', 'text/html');
        }
        if (!$this->headers->has('Content-Length')) {
            $this->headers->offsetSet('Content-Length', strlen($this->content));
        }
        $this->prepared = true;
        return $this;
    }

    private function sendAllHeaders() : void
    {
        if (headers_sent()) {
            return;
        }
        header(sprintf('%s %s %s', $this->protocol, $this->status->value, $this->status->getStatusText()), true, $this->status->value);
        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value), false, $this->status->value);
        }
        header('X-Response-Time: ' . (microtime(true) - $this->request->getRequestStartTime()), );
        // header('Access-Control-Allow-Origin: ' . $this->request->getServerVar('HTTP_ORIGIN'));
        // header('Access-Control-Max-Age: 360');
        // header('Access-Control-Allow-Credentials: true');
        // header('Access-Control-Allow-Methods: *');
        // header('Access-Control-Allow-Headers: Origin');
        // header('Access-Control-Expose-Headers: Access-Control-Allow-Origin');
        // header('Access-Control-Allow-Origin: "https://localhost');
    }
}