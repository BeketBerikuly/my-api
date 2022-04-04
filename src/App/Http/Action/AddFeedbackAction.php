<?php

namespace App\Http\Action;

use App\Entity\Phone\Phone;
use App\Helpers\Phones;
use App\ReadModel\PhoneRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class AddFeedbackAction implements RequestHandlerInterface
{
    private $phone;

    public function __construct(PhoneRepository $phone)
    {
        $this->phone = $phone;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $phoneNumber = $request->getAttribute('phone_number');
        $text = $request->getQueryParams()['text'];
        $name = $request->getQueryParams()['name'] ?? null;
        $rating = $name ? $request->getQueryParams()['rating'] : 0;

        if (!$phoneNumber) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Не найдено номер телефона'
            ], 401);
        }

        if (!$text) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Не найдено текст отзыва'
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

        $phone = new Phone($phoneNumber);

        $phone->addFeedback($text, $name, $rating);

        return new JsonResponse([
                'status' => 'ok',
                'message' => 'Отзыв успешно сохранен'
            ]
        );
    }
}
