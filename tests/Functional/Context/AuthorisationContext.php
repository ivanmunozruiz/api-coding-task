<?php

declare(strict_types=1);

namespace App\Tests\Functional\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;

trait AuthorisationContext
{
    /** @BeforeScenario */
    public function authHeadersValidation(BeforeScenarioScope $scope): void
    {
        $scenario = $scope->getScenario();
        $feature = $scope->getFeature();

        $tagToken = [
            'emptyAuthToken' => '',
            'invalidAuthToken' => 'invalid',
            'adminToken' => 'ivan.sazo@gmail.com', // I Know...
        ];

        foreach ($tagToken as $tag => $token) {
            if ($scenario->hasTag($tag) || $feature->hasTag($tag)) {
                $this->restContext->theHeaderIsSetEqualTo('X-AUTH-TOKEN', $token);

                return;
            }
        }
    }
}
