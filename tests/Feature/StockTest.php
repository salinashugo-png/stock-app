<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_obtener_lista_productos()
    {
        Product::create(['name' => 'Taladro', 'sku' => 'TAL-1', 'category' => 'Herramientas']);
        Sanctum::actingAs(User::factory()->create());
        $this->getJson('/api/stock')->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'name', 'sku', 'stock']]);
    }

    public function test_sin_token_da_401()
    {
        $this->getJson('/api/stock')->assertStatus(401);
    }

    public function test_operario_no_puede_crear_producto()
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'operario']));
        $this->postJson('/api/products', ['name' => 'x', 'sku' => 'x', 'category' => 'x'])->assertStatus(403);
    }

    public function test_entrada_y_salida_calculan_stock()
    {
        $p = Product::create(['name' => 'A', 'sku' => 'A1', 'category' => 'C']);
        Sanctum::actingAs(User::factory()->create());
        $base = ['product_id' => $p->id, 'quantity' => 10, 'date' => now()->toDateString()];

        $this->postJson('/api/movements', $base + ['type' => 'entrada', 'supplier' => 'Prov'])->assertStatus(201);
        $this->assertEquals(10, $p->fresh()->stock);

        $this->postJson('/api/movements', ['quantity' => 15, 'type' => 'salida', 'reason' => 'venta'] + $base)->assertStatus(422);
    }
}
