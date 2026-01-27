<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    protected function assertApiError(TestResponse $response, int $status, bool $expectsFields = false): void
    {
        $response->assertStatus($status);

        $response->assertJsonStructure([
            'message',
            'details',
        ]);

        $response->assertJsonPath('message', fn($value) => is_string($value));

        if($expectsFields) {
            $response->assertJsonStructure([
                'details' => [
                    'fields',
                ],
            ]);

            $response->assertJsonPath(
                'details.fields',
                fn($value)=>is_array($value)
            );

        }else {
            $response->assertJsonPath('details', null);
        }
    }
}
