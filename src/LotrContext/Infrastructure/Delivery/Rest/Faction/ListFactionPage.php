<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Faction;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Shared\Domain\DataMapping;
use App\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;
use App\LotrContext\Application\Query\Faction\ListFaction\ListFactionsQuery;

final class ListFactionPage extends ApiQueryPage
{
    use DataMapping;

    public function __invoke(Request $request): JsonResponse
    {
        $page = self::getInt($request->query->all(), '[page]', 1);
        $limit = self::getInt($request->query->all(), '[limit]', 10);
        return new JsonResponse(
            $this->ask(new ListFactionsQuery(
                page: $page,
                limit: $limit,
            )),
            Response::HTTP_OK,
        );
    }
}
