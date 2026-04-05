<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutTest extends TestCase
{
    /**
     * Test the layout includes necessary fonts and assets.
     */
    public function test_layout_includes_design_system_assets(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('fonts.googleapis.com');
        $response->assertSee('family=Inter');
        $response->assertSee('family=Outfit');
    }
}
