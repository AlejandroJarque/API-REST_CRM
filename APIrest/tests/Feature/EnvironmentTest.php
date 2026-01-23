<?php

namespace Tests\Feature;
use Tests\TestCase;

class EnvironmentTest extends TestCase {

    public function testAppRunsInTestingEnv(): void {

        $this->assertTrue(app()->environment('testing'));
    }
}
