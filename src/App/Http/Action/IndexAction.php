<?php

namespace App\Http\Action;

use App\ReadModel\Pagination;
use App\ReadModel\PhoneRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class IndexAction implements RequestHandlerInterface
{
    private const PER_PAGE = 5;

    private $phones;

    public function __construct(PhoneRepository $phones)
    {
        $this->phones = $phones;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pager = new Pagination(
            $this->phones->countAll(),
            $request->getAttribute('page') ?: 1,
            self::PER_PAGE
        );

        $phones = $this->phones->all(
            $pager->getOffset(),
            $pager->getLimit()
        );

        return new JsonResponse(['phones' => $phones, 'pager' => $pager,]);
    }
}
