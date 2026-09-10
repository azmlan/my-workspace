<?php

namespace App\Http\Controllers;

use App\Enums\EntryTax;
use App\Models\Category;
use App\Models\Entry;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EntryController extends Controller
{
    public function create(): View
    {
        $categories = Category::orderBy('name')->pluck('name');

        return view('saharituwaiq.create', [
            'categories' => $categories,
            'taxOptions' => EntryTax::cases(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rowRules());

        $submittedBy = $validated['submitted_by'];
        $rows = $validated['rows'];

        if ($errors = $this->duplicateSkusWithinBatch($rows)) {
            return response()->json([
                'message' => 'يوجد رقم تعريفي (SKU) مكرر داخل هذا الطلب.',
                'errors' => $errors,
            ], 422);
        }

        try {
            DB::transaction(function () use ($rows, $submittedBy) {
                foreach ($rows as $row) {
                    Entry::create([...$row, 'submitted_by' => $submittedBy]);
                }
            });
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                $skus = array_column($rows, 'sku');
                $taken = Entry::whereIn('sku', $skus)->pluck('sku');

                return response()->json([
                    'message' => 'تعذر الحفظ: الرقم التعريفي (SKU) التالي مستخدم بالفعل: '.$taken->implode('، '),
                    'errors' => ['duplicate_sku' => $taken->all()],
                ], 422);
            }

            throw $e;
        }

        return response()->json([
            'message' => 'تم حفظ '.count($rows).' صف بنجاح.',
            'saved' => count($rows),
        ]);
    }

    public function review(Request $request): View
    {
        $query = Entry::query();

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($submitter = $request->input('submitted_by')) {
            $query->where('submitted_by', $submitter);
        }

        if ($request->input('status') === 'incomplete') {
            $query->where(fn ($q) => $q->whereNull('sku')->orWhereNull('retail_price')->orWhereNull('quantity')->orWhereNull('tax'));
        } elseif ($request->input('status') === 'complete') {
            $query->whereNotNull('sku')->whereNotNull('retail_price')->whereNotNull('quantity')->whereNotNull('tax');
        }

        $entries = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        $categories = Entry::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        $submitters = Entry::query()->distinct()->orderBy('submitted_by')->pluck('submitted_by');
        $total = Entry::count();
        $incompleteCount = Entry::query()
            ->where(fn ($q) => $q->whereNull('sku')->orWhereNull('retail_price')->orWhereNull('quantity')->orWhereNull('tax'))
            ->count();

        return view('saharituwaiq.review', compact('entries', 'categories', 'submitters', 'total', 'incompleteCount'));
    }

    public function edit(Entry $entry): View
    {
        $categories = Category::orderBy('name')->pluck('name');

        return view('saharituwaiq.edit', [
            'entry' => $entry,
            'categories' => $categories,
            'taxOptions' => EntryTax::cases(),
        ]);
    }

    public function update(Request $request, Entry $entry): RedirectResponse
    {
        $rules = $this->singleRowRules();
        $rules['sku'][] = Rule::unique('entries', 'sku')->ignore($entry->id);

        $validated = $request->validate($rules);

        $entry->update($validated);

        return redirect()
            ->route('saharituwaiq.review')
            ->with('success', 'تم تحديث الصف.');
    }

    public function destroy(Entry $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()
            ->route('saharituwaiq.review')
            ->with('success', 'تم حذف الصف.');
    }

    private function rowRules(): array
    {
        return [
            'submitted_by' => ['required', 'string', 'max:255'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.product_name' => ['required', 'string', 'max:255'],
            'rows.*.sku' => ['nullable', 'string', 'max:255', 'unique:entries,sku'],
            'rows.*.option_name_1' => ['nullable', 'string', 'max:255'],
            'rows.*.option_value_1' => ['nullable', 'string', 'max:255'],
            'rows.*.option_name_2' => ['nullable', 'string', 'max:255'],
            'rows.*.option_value_2' => ['nullable', 'string', 'max:255'],
            'rows.*.option_name_3' => ['nullable', 'string', 'max:255'],
            'rows.*.option_value_3' => ['nullable', 'string', 'max:255'],
            'rows.*.retail_price' => ['nullable', 'numeric', 'min:0'],
            'rows.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'rows.*.category' => ['required', 'string', 'max:255', Rule::exists('categories', 'name')],
            'rows.*.quantity' => ['nullable', 'integer', 'min:0'],
            'rows.*.barcode' => ['nullable', 'string', 'max:255'],
            'rows.*.tax' => ['nullable', Rule::in(array_column(EntryTax::cases(), 'value'))],
            'rows.*.weight_grams' => ['nullable', 'integer', 'min:0'],
            'rows.*.length_cm' => ['nullable', 'numeric', 'min:0'],
            'rows.*.width_cm' => ['nullable', 'numeric', 'min:0'],
            'rows.*.height_cm' => ['nullable', 'numeric', 'min:0'],
            'rows.*.agent' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function singleRowRules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'option_name_1' => ['nullable', 'string', 'max:255'],
            'option_value_1' => ['nullable', 'string', 'max:255'],
            'option_name_2' => ['nullable', 'string', 'max:255'],
            'option_value_2' => ['nullable', 'string', 'max:255'],
            'option_name_3' => ['nullable', 'string', 'max:255'],
            'option_value_3' => ['nullable', 'string', 'max:255'],
            'retail_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255', Rule::exists('categories', 'name')],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'tax' => ['nullable', Rule::in(array_column(EntryTax::cases(), 'value'))],
            'weight_grams' => ['nullable', 'integer', 'min:0'],
            'length_cm' => ['nullable', 'numeric', 'min:0'],
            'width_cm' => ['nullable', 'numeric', 'min:0'],
            'height_cm' => ['nullable', 'numeric', 'min:0'],
            'agent' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function duplicateSkusWithinBatch(array $rows): array
    {
        $counts = collect($rows)
            ->pluck('sku')
            ->filter(fn ($sku) => filled($sku))
            ->countBy();

        $duplicated = $counts->filter(fn ($count) => $count > 1)->keys();

        if ($duplicated->isEmpty()) {
            return [];
        }

        $errors = [];
        foreach ($rows as $index => $row) {
            if (filled($row['sku'] ?? null) && $duplicated->contains($row['sku'])) {
                $errors["rows.$index.sku"] = ["الرقم التعريفي (SKU) مكرر داخل هذا الطلب: {$row['sku']}"];
            }
        }

        return $errors;
    }

    public function export(): StreamedResponse
    {
        $entries = Entry::query()
            ->whereNotNull('sku')
            ->whereNotNull('retail_price')
            ->whereNotNull('quantity')
            ->whereNotNull('tax')
            ->orderBy('created_at')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => 'اسم المنتج',
            'B' => 'الرقم التعريفي للمنتج (SKU)',
            'C' => 'اسم الخيار 1',
            'D' => 'قيم الخيار 1',
            'E' => 'اسم الخيار 2',
            'F' => 'قيم الخيار 2',
            'G' => 'اسم الخيار 3',
            'H' => 'قيم الخيار 3',
            'I' => 'سعر التجزئة (سعر البيع للعملاء)',
            'J' => 'سعر التكلفة (سعر المورد - السعر بدون الربح )',
            'K' => 'الفئة (اذا وجدت)',
            'L' => 'الكمية',
            'M' => 'الباركود (اذا وجد)',
            'N' => 'الضريبة',
            'O' => 'الوكيل',
            'P' => 'الوزن (جرام)',
            'Q' => 'الطول (سم)',
            'R' => 'العرض (سم)',
            'S' => 'الارتفاع (سم)',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col.'1', $label);
        }

        $row = 2;
        foreach ($entries as $entry) {
            $sheet->setCellValue('A'.$row, $entry->product_name);
            $sheet->setCellValueExplicit('B'.$row, $entry->sku, DataType::TYPE_STRING);
            $sheet->setCellValue('C'.$row, $entry->option_name_1 ?? '');
            $sheet->setCellValue('D'.$row, $entry->option_value_1 ?? '');
            $sheet->setCellValue('E'.$row, $entry->option_name_2 ?? '');
            $sheet->setCellValue('F'.$row, $entry->option_value_2 ?? '');
            $sheet->setCellValue('G'.$row, $entry->option_name_3 ?? '');
            $sheet->setCellValue('H'.$row, $entry->option_value_3 ?? '');
            $sheet->setCellValue('I'.$row, (float) $entry->retail_price);
            $sheet->setCellValue('J'.$row, $entry->cost_price !== null ? (float) $entry->cost_price : '');
            $sheet->setCellValue('K'.$row, $entry->category ?? '');
            $sheet->setCellValue('L'.$row, $entry->quantity);
            $sheet->setCellValueExplicit('M'.$row, $entry->barcode ?? '', DataType::TYPE_STRING);
            $sheet->setCellValue('N'.$row, $entry->tax);
            $sheet->setCellValue('O'.$row, $entry->agent ?? '');
            $sheet->setCellValue('P'.$row, $entry->weight_grams ?? '');
            $sheet->setCellValue('Q'.$row, $entry->length_cm !== null ? (float) $entry->length_cm : '');
            $sheet->setCellValue('R'.$row, $entry->width_cm !== null ? (float) $entry->width_cm : '');
            $sheet->setCellValue('S'.$row, $entry->height_cm !== null ? (float) $entry->height_cm : '');
            $row++;
        }

        $filename = 'rewaa-import-'.now()->format('Y-m-d-His').'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportFull(): StreamedResponse
    {
        $entries = Entry::orderBy('created_at')->get();
        $filename = 'saharituwaiq-full-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($entries) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'اسم المنتج', 'SKU', 'اسم الخيار 1', 'قيمة الخيار 1', 'اسم الخيار 2', 'قيمة الخيار 2',
                'اسم الخيار 3', 'قيمة الخيار 3', 'سعر التجزئة', 'سعر التكلفة', 'الفئة', 'الكمية',
                'الباركود', 'الضريبة', 'الوزن (جرام)', 'الطول (سم)', 'العرض (سم)', 'الارتفاع (سم)',
                'المدخل', 'التاريخ', 'الوكيل',
            ]);

            foreach ($entries as $entry) {
                fputcsv($out, [
                    $entry->product_name,
                    $entry->sku,
                    $entry->option_name_1,
                    $entry->option_value_1,
                    $entry->option_name_2,
                    $entry->option_value_2,
                    $entry->option_name_3,
                    $entry->option_value_3,
                    $entry->retail_price,
                    $entry->cost_price,
                    $entry->category,
                    $entry->quantity,
                    $entry->barcode,
                    $entry->tax,
                    $entry->weight_grams,
                    $entry->length_cm,
                    $entry->width_cm,
                    $entry->height_cm,
                    $entry->submitted_by,
                    $entry->created_at,
                    $entry->agent,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
