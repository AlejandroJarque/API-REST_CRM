<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    
    public function testRequiresAuthentication()
    {
        $response = $this->getJson('/api/v1/dashboard');

        $response->assertStatus(401);
    }
}
