<?php
namespace App\Http\Controllers;

use App\Services\StatDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    protected StatDataService $statDataService;

    public function __construct(StatDataService $statDataService)
    {
        $this->statDataService = $statDataService;
    }

    public function calculate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount'     => 'required|numeric|min:1',
            'years'      => 'required|integer|min:1',
            'project_id' => 'required|exists:projects,id'
        ]);

        $yield = $this->statDataService->getLatestYield();
        $amount = $data['amount'];
        $years  = (int)$data['years'];
        $chartData = [];

        for ($year = 1; $year <= $years; $year++) {
            $value = $amount * pow((1 + $yield), $year);
            $chartData[] = ['year' => $year, 'value' => round($value, 2)];
        }

        return response()->json($chartData);
    }
}
