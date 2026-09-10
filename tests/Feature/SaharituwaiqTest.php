<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Entry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class SaharituwaiqTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['ملابس', 'إلكترونيات', 'فئة', 'أحذية'] as $name) {
            Category::create(['name' => $name]);
        }
    }

    public function test_form_page_loads_without_auth(): void
    {
        $response = $this->get('/saharituwaiq');

        $response->assertStatus(200);
    }

    public function test_review_page_loads_without_auth(): void
    {
        $response = $this->get('/saharituwaiq/review');

        $response->assertStatus(200);
    }

    public function test_it_saves_a_batch_of_rows_in_one_request(): void
    {
        $payload = [
            'submitted_by' => 'أحمد',
            'rows' => [
                [
                    'product_name' => 'قميص',
                    'category' => 'ملابس',
                    'sku' => 'SKU-001',
                    'retail_price' => 99.5,
                    'quantity' => 5,
                    'tax' => 'ضريبة',
                ],
                [
                    'product_name' => 'قميص',
                    'category' => 'ملابس',
                    'sku' => 'SKU-002',
                    'option_name_1' => 'المقاس',
                    'option_value_1' => 'L',
                    'retail_price' => 99.5,
                    'quantity' => 3,
                    'tax' => 'بدون ضريبة',
                ],
            ],
        ];

        $response = $this->postJson('/saharituwaiq', $payload);

        $response->assertOk();
        $this->assertDatabaseCount('entries', 2);
        $this->assertDatabaseHas('entries', [
            'sku' => 'SKU-001',
            'submitted_by' => 'أحمد',
            'quantity' => 5,
        ]);
    }

    public function test_only_product_name_and_category_are_required_to_save(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'نورة',
            'rows' => [[
                'product_name' => 'منتج بدون تفاصيل',
                'category' => 'إلكترونيات',
            ]],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('entries', [
            'product_name' => 'منتج بدون تفاصيل',
            'category' => 'إلكترونيات',
            'sku' => null,
            'retail_price' => null,
            'quantity' => null,
            'tax' => null,
        ]);
    }

    public function test_category_is_required(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'نورة',
            'rows' => [[
                'product_name' => 'منتج',
            ]],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rows.0.category']);
        $this->assertDatabaseCount('entries', 0);
    }

    public function test_multiple_rows_with_blank_sku_in_one_batch_are_allowed(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'ماجد',
            'rows' => [
                ['product_name' => 'منتج 1', 'category' => 'فئة'],
                ['product_name' => 'منتج 2', 'category' => 'فئة'],
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('entries', 2);
    }

    public function test_leading_zero_sku_and_barcode_are_preserved_as_strings(): void
    {
        $this->postJson('/saharituwaiq', [
            'submitted_by' => 'سارة',
            'rows' => [[
                'product_name' => 'حذاء',
                'category' => 'أحذية',
                'sku' => '0012345',
                'barcode' => '0000000012345',
                'retail_price' => 50,
                'quantity' => 1,
                'tax' => 'ضريبة',
            ]],
        ])->assertOk();

        $entry = Entry::first();
        $this->assertSame('0012345', $entry->sku);
        $this->assertSame('0000000012345', $entry->barcode);
    }

    public function test_duplicate_sku_against_existing_row_fails_cleanly_naming_the_sku(): void
    {
        Entry::create([
            'product_name' => 'قديم',
            'category' => 'فئة',
            'sku' => 'DUPLICATE-SKU',
            'retail_price' => 10,
            'quantity' => 1,
            'tax' => 'ضريبة',
            'submitted_by' => 'قديم',
        ]);

        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'خالد',
            'rows' => [[
                'product_name' => 'جديد',
                'category' => 'فئة',
                'sku' => 'DUPLICATE-SKU',
                'retail_price' => 20,
                'quantity' => 2,
                'tax' => 'ضريبة',
            ]],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rows.0.sku']);
        $this->assertDatabaseCount('entries', 1);
    }

    public function test_duplicate_sku_within_the_same_batch_fails_cleanly(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'خالد',
            'rows' => [
                ['product_name' => 'أ', 'category' => 'فئة', 'sku' => 'SAME-SKU'],
                ['product_name' => 'ب', 'category' => 'فئة', 'sku' => 'SAME-SKU'],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rows.0.sku', 'rows.1.sku']);
        $this->assertDatabaseCount('entries', 0);
    }

    public function test_a_failed_submit_does_not_save_any_row_from_the_batch(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'منى',
            'rows' => [
                [
                    'product_name' => 'صف صحيح',
                    'category' => 'فئة',
                    'sku' => 'OK-1',
                ],
                [
                    // missing required product_name to trigger failure
                    'category' => 'فئة',
                    'sku' => 'OK-2',
                ],
            ],
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('entries', 0);
    }

    public function test_tax_only_accepts_the_two_allowed_values(): void
    {
        $response = $this->postJson('/saharituwaiq', [
            'submitted_by' => 'فهد',
            'rows' => [[
                'product_name' => 'منتج',
                'category' => 'فئة',
                'sku' => 'TAX-1',
                'retail_price' => 10,
                'quantity' => 1,
                'tax' => 'قيمة غير صحيحة',
            ]],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rows.0.tax']);
    }

    public function test_destroy_removes_a_row(): void
    {
        $entry = Entry::create([
            'product_name' => 'منتج',
            'category' => 'فئة',
            'sku' => 'DEL-1',
            'retail_price' => 10,
            'quantity' => 1,
            'tax' => 'ضريبة',
            'submitted_by' => 'test',
        ]);

        $response = $this->delete('/saharituwaiq/'.$entry->id);

        $response->assertRedirect(route('saharituwaiq.review'));
        $this->assertDatabaseCount('entries', 0);
    }

    public function test_edit_page_loads_for_an_incomplete_row(): void
    {
        $entry = Entry::create([
            'product_name' => 'منتج ناقص',
            'category' => 'فئة',
            'submitted_by' => 'test',
        ]);

        $response = $this->get(route('saharituwaiq.edit', $entry));

        $response->assertOk();
    }

    public function test_update_fills_in_the_missing_fields_later(): void
    {
        $entry = Entry::create([
            'product_name' => 'منتج ناقص',
            'category' => 'فئة',
            'submitted_by' => 'test',
        ]);

        $response = $this->put(route('saharituwaiq.update', $entry), [
            'product_name' => 'منتج ناقص',
            'category' => 'فئة',
            'sku' => 'FILLED-1',
            'retail_price' => 25,
            'quantity' => 4,
            'tax' => 'ضريبة',
        ]);

        $response->assertRedirect(route('saharituwaiq.review'));
        $this->assertDatabaseHas('entries', [
            'id' => $entry->id,
            'sku' => 'FILLED-1',
            'quantity' => 4,
        ]);
    }

    public function test_update_keeping_the_same_sku_does_not_trigger_a_duplicate_error(): void
    {
        $entry = Entry::create([
            'product_name' => 'منتج',
            'category' => 'فئة',
            'sku' => 'SELF-SKU',
            'submitted_by' => 'test',
        ]);

        $response = $this->put(route('saharituwaiq.update', $entry), [
            'product_name' => 'منتج محدث',
            'category' => 'فئة',
            'sku' => 'SELF-SKU',
        ]);

        $response->assertRedirect(route('saharituwaiq.review'));
        $this->assertDatabaseHas('entries', ['id' => $entry->id, 'product_name' => 'منتج محدث']);
    }

    public function test_rewaa_export_downloads_an_xlsx_file(): void
    {
        Entry::create([
            'product_name' => 'منتج',
            'category' => 'فئة',
            'sku' => 'EXP-1',
            'retail_price' => 10,
            'quantity' => 1,
            'tax' => 'ضريبة',
            'submitted_by' => 'test',
        ]);

        $response = $this->get('/saharituwaiq/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_rewaa_export_excludes_incomplete_rows(): void
    {
        Entry::create([
            'product_name' => 'مكتمل',
            'category' => 'فئة',
            'sku' => 'COMPLETE-1',
            'retail_price' => 10,
            'quantity' => 1,
            'tax' => 'ضريبة',
            'submitted_by' => 'test',
        ]);

        Entry::create([
            'product_name' => 'غير مكتمل',
            'category' => 'فئة',
            'submitted_by' => 'test',
        ]);

        $response = $this->get('/saharituwaiq/export');
        $response->assertOk();

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tmp, $response->streamedContent());

        $spreadsheet = IOFactory::load($tmp);
        $sheet = $spreadsheet->getActiveSheet();

        $this->assertSame('مكتمل', $sheet->getCell('A2')->getValue());
        $this->assertSame(null, $sheet->getCell('A3')->getValue());

        unlink($tmp);
    }

    public function test_agent_and_dimensions_appear_after_column_n_in_the_rewaa_export(): void
    {
        Entry::create([
            'product_name' => 'منتج',
            'category' => 'فئة',
            'sku' => 'AGT-1',
            'retail_price' => 10,
            'quantity' => 1,
            'tax' => 'ضريبة',
            'submitted_by' => 'test',
            'agent' => 'وكيل الرياض',
            'weight_grams' => 500,
            'length_cm' => 10.5,
            'width_cm' => 20.25,
            'height_cm' => 5,
        ]);

        $response = $this->get('/saharituwaiq/export');
        $response->assertOk();

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tmp, $response->streamedContent());

        $spreadsheet = IOFactory::load($tmp);
        $sheet = $spreadsheet->getActiveSheet();

        // O–S sit one past Rewaa's own 14 columns (A–N) — agent first, then the
        // weight/dimension columns — so they can be selected and deleted together
        // before the file is uploaded to Rewaa.
        $this->assertSame('S', $sheet->getHighestColumn());
        $this->assertSame('الوكيل', $sheet->getCell('O1')->getValue());
        $this->assertSame('وكيل الرياض', $sheet->getCell('O2')->getValue());
        $this->assertSame('الوزن (جرام)', $sheet->getCell('P1')->getValue());
        $this->assertSame(500, $sheet->getCell('P2')->getValue());
        $this->assertSame('الطول (سم)', $sheet->getCell('Q1')->getValue());
        $this->assertSame(10.5, $sheet->getCell('Q2')->getValue());
        $this->assertSame('العرض (سم)', $sheet->getCell('R1')->getValue());
        $this->assertSame(20.25, $sheet->getCell('R2')->getValue());
        $this->assertSame('الارتفاع (سم)', $sheet->getCell('S1')->getValue());
        $this->assertSame(5.0, $sheet->getCell('S2')->getValue());

        unlink($tmp);
    }

    public function test_agent_can_be_filled_in_via_edit(): void
    {
        $entry = Entry::create([
            'product_name' => 'منتج ناقص',
            'category' => 'فئة',
            'submitted_by' => 'test',
        ]);

        $response = $this->put(route('saharituwaiq.update', $entry), [
            'product_name' => 'منتج ناقص',
            'category' => 'فئة',
            'agent' => 'وكيل جدة',
        ]);

        $response->assertRedirect(route('saharituwaiq.review'));
        $this->assertDatabaseHas('entries', ['id' => $entry->id, 'agent' => 'وكيل جدة']);
    }
}
