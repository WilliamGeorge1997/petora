<?php

namespace Modules\Import\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Import\Http\Requests\ImportRequest;
use Modules\Import\Service\ImportService;

class ImportController extends Controller
{
    private ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
        $this->middleware(['auth:admin', 'prevent-back-history', 'role:Super Admin']);
    }

    public function index()
    {
        return view('import::index', [
            'importTemplateJson' => $this->templateJson(),
            'importInstructions' => $this->instructions(),
        ]);
    }

    public function show(ImportRequest $request)
    {
        try {
            $preview = $this->importService->show($request->input('data'));

            return redirect()
                ->route('import.index')
                ->withInput()
                ->with('import_preview', $preview);
        } catch (\Exception $e) {
            return redirect()
                ->route('import.index')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function store(ImportRequest $request)
    {
        try {
            $result = $this->importService->save(
                $request->input('branch_id'),
                $request->input('data')
            );

            return redirect()
                ->route('import.index')
                ->with(
                    'success',
                    'تم الاستيراد بنجاح: ' . $result['categories'] . ' فئة، '
                    . $result['products'] . ' منتج'
                    . ($result['addons'] > 0
                        ? '، ' . $result['addons'] . ' إضافة (' . $result['addon_values'] . ' قيمة).'
                        : '.')
                );
        } catch (\Exception $e) {
            return redirect()
                ->route('import.index')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    private function templateJson()
    {
        $path = module_path('Import', 'Resources/assets/import-template.json');

        if (!is_file($path)) {
            return '{}';
        }

        $decoded = json_decode(file_get_contents($path), true);

        return json_encode(
            $decoded,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ) ?: '{}';
    }

    private function instructions()
    {
        $path = module_path('Import', 'Resources/prompts/import-instructions.txt');

        if (!is_file($path)) {
            return '';
        }

        return file_get_contents($path);
    }
}
