<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Service;

use App\AuthorisationContext\Domain\Exception\ShallNotPassException;
use App\AuthorisationContext\Infrastructure\Domain\Aggregate\User;
use App\AuthorisationContext\Infrastructure\Domain\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PreAuthenticatedUserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class Authenticator extends AbstractAuthenticator
{
    public const HEADERKEY = 'X-AUTH-TOKEN';

    public const REQUEST_USER_ID = 'request-user-id';

    public const REQUEST_USER_TOKEN = 'request-user-token';

    public const ADMIN_REQUEST = 'request-api-admin';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $logger,
        private readonly string $adminApiKey,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return '' !== trim((string) $request->headers->get(self::HEADERKEY, ''));
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get(self::HEADERKEY, '');

        if ($this->isAdminRequest($request, $token)) {
            $adminTokenUser = $this->userRepository->adminTokenUser($token);

            return new SelfValidatingPassport(
                new UserBadge($adminTokenUser->getUserIdentifier()),
                [new PreAuthenticatedUserBadge()],
            );
        }

        throw ShallNotPassException::from($token);
    }

    public function isAdminRequest(Request $request, ?string $token = null): bool
    {
        $token ??= $request->headers->get(self::HEADERKEY, '');

        return $this->adminApiKey === $token;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $authTokenUser = $token->getUser();
        if ($authTokenUser instanceof User) {
            $request->attributes->set(self::REQUEST_USER_ID, $authTokenUser->getUserIdentifier());

            $request->attributes->set(self::REQUEST_USER_ID, $authTokenUser->uuid()->id());
            $request->attributes->set(self::REQUEST_USER_TOKEN, $authTokenUser->token()->value());
            $request->attributes->set(self::ADMIN_REQUEST, $authTokenUser->token()->value() === $this->adminApiKey);
        }

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->logger->info('Token access failure', ['exception' => $exception]);

        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }
}
