<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class UIComponentsTest extends TestCase
{
    /**
     * Test x-button component rendering.
     */
    public function test_button_component_renders(): void
    {
        $view = Blade::render('<x-button>Click Me</x-button>');
        
        $this->assertStringContainsString('button', $view);
        $this->assertStringContainsString('Click Me', $view);
        $this->assertStringContainsString('transition-all', $view);
        $this->assertStringContainsString('ps-6', $view);
    }

    /**
     * Test x-input component renders with logical properties.
     */
    public function test_input_component_renders(): void
    {
        $view = Blade::render('<x-input id="email" name="email" type="email" />');
        
        $this->assertStringContainsString('input', $view);
        $this->assertStringContainsString('ps-4', $view);
        $this->assertStringContainsString('pe-4', $view);
    }

    /**
     * Test x-card component rendering.
     */
    public function test_card_component_renders(): void
    {
        $view = Blade::render('<x-card>Card Content</x-card>');
        
        $this->assertStringContainsString('Card Content', $view);
        $this->assertStringContainsString('shadow-xl', $view);
    }
}
