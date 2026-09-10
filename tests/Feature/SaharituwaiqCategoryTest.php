<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaharituwaiqCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_page_loads_without_auth(): void
    {
        $response = $this->get(route('saharituwaiq.categories.index'));

        $response->assertOk();
    }

    public function test_entry_form_x_data_attribute_stays_well_formed_with_tricky_category_names(): void
    {
        // Category names with a leading space or embedded quote previously broke the
        // `x-data="entryForm(...)"` attribute by injecting a raw `"` into the JSON,
        // which terminated the attribute early and crashed Alpine on page load.
        Category::create(['name' => ' فئة بمسافة']);
        Category::create(['name' => 'فئة "بعلامة اقتباس"']);

        $response = $this->get(route('saharituwaiq.create'));

        $response->assertOk();
        $response->assertSee('entryForm(JSON.parse(', false);
        $response->assertDontSee('entryForm(["', false);
    }

    public function test_it_bulk_creates_categories_from_newline_separated_text(): void
    {
        $response = $this->post(route('saharituwaiq.categories.store'), [
            'names' => "ملابس\nإلكترونيات\n\nأحذية",
        ]);

        $response->assertRedirect(route('saharituwaiq.categories.index'));
        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseHas('categories', ['name' => 'ملابس']);
        $this->assertDatabaseHas('categories', ['name' => 'إلكترونيات']);
        $this->assertDatabaseHas('categories', ['name' => 'أحذية']);
    }

    public function test_bulk_add_skips_duplicates_case_insensitively(): void
    {
        Category::create(['name' => 'ملابس']);

        $this->post(route('saharituwaiq.categories.store'), [
            'names' => "ملابس\nإلكترونيات",
        ]);

        $this->assertDatabaseCount('categories', 2);
    }

    public function test_it_deletes_a_category(): void
    {
        $category = Category::create(['name' => 'ملابس']);

        $response = $this->delete(route('saharituwaiq.categories.destroy', $category));

        $response->assertRedirect(route('saharituwaiq.categories.index'));
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_json_endpoint_returns_category_names(): void
    {
        Category::create(['name' => 'ملابس']);
        Category::create(['name' => 'أحذية']);

        $response = $this->getJson(route('saharituwaiq.categories.json'));

        $response->assertOk();
        $response->assertJson(['أحذية', 'ملابس']);
    }

    public function test_entry_submission_rejects_a_category_not_in_the_list(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'test',
            'rows' => [[
                'product_name' => 'منتج',
                'category' => 'فئة غير موجودة',
            ]],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rows.0.category']);
        $this->assertDatabaseCount('entries', 0);
    }

    public function test_entry_submission_accepts_an_existing_category(): void
    {
        Category::create(['name' => 'ملابس']);

        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'test',
            'rows' => [[
                'product_name' => 'منتج',
                'category' => 'ملابس',
            ]],
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('entries', 1);
    }
}
