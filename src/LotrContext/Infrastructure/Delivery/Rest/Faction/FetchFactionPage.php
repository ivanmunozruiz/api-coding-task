<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Faction;

use App\LotrContext\Application\Query\Faction\FetchFaction\FetchFactionQuery;
use App\Shared\Domain\DataMapping;
use App\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FetchFactionPage extends ApiQueryPage
{
    use DataMapping;

    public function __invoke(Request $request): JsonResponse
    {
        $id = self::getString($request->attributes->all(), '[id]');

        return new JsonResponse(
            data: $this->ask(
                new FetchFactionQuery($id),
            ),
            status: Response::HTTP_OK,
        );
    }
}
