<?php

namespace App\Http\Action;

use App\Helpers\Phones;
use App\ReadModel\PhoneRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ShowPhonesAction implements RequestHandlerInterface
{
    private $phone;

    public function __construct(PhoneRepository $phone)
    {
        $this->phone = $phone;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $phoneNumber = $request->getAttribute('phone_number');

        if (!$phoneNumber) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Не найдено номер телефона'
            ], 401);
        }

        $phoneNumber = Phones::normalize($phoneNumber);
        $phoneIsValid = Phones::isDigits($phoneNumber);

        if (!$phoneIsValid) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Номер телефона не валиден'
            ], 400);
        }

        if (!$phones = $this->phone->find($phoneNumber)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Название страны не найдено'
            ], 404);
        }

        return new JsonResponse([
                'status' => 'ok',
                'phones' => $phones
            ]
        );
    }
}
