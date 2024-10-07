<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Character;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Shared\Domain\DataMapping;
use App\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;
use App\LotrContext\Application\Query\Character\FetchCharacter\FetchCharacterQuery;

final class FetchCharacterPage extends ApiQueryPage
{
    use DataMapping;

    public function __invoke(Request $request): JsonResponse
    {
        $id = self::getString($request->attributes->all(), '[id]');

        return new JsonResponse(
            data: $this->ask(
                new FetchCharacterQuery($id),
            ),
            status: Response::HTTP_OK,
        );
    }
}
