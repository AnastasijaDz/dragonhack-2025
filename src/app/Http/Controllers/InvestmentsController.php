<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use App\Models\Token;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class InvestmentsController extends Controller
{
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $investorId = $user->investor ? $user->investor->id : null;
        $investments = Investment::with(['project', 'tokens'])
            ->where('investor_id', $investorId)
            ->get();
        return view('investments.index', compact('investments'));
    }
    
    public function getTokens(Request $request, $investmentId): JsonResponse
    {
        try {
            $user = Auth::user();
            $investorId = $user->investor ? $user->investor->id : null;
            
            $investment = Investment::where('id', $investmentId)
                ->where('investor_id', $investorId)
                ->firstOrFail();
            
            $tokens = $investment->tokens;
            
            return response()->json([
                'success' => true,
                'tokens' => $tokens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tokens: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function transferTokens(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'token_ids' => 'required|array',
                'token_ids.*' => 'required|integer|exists:tokens,id',
                'recipient_email' => 'required|email',
                'project_id' => 'required|integer|exists:projects,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $user = Auth::user();
            $investorId = $user->investor ? $user->investor->id : null;
            
            $tokenCount = Token::whereIn('id', $request->token_ids)
                ->whereHas('investment', function ($query) use ($investorId) {
                    $query->where('investor_id', $investorId);
                })
                ->count();
            
            if ($tokenCount !== count($request->token_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not own all of these tokens'
                ], 403);
            }
            
            $this->tokenService->transferTokensToInvestorByEmail(
                $request->token_ids,
                $request->recipient_email,
                $request->project_id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Tokens transferred successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to transfer tokens: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): View
    {
        $investment = Investment::findOrFail($id);
        return view('investments.show', compact('investment'));
    }

    public function create(): View
    {
        return view('investments.create');
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user->type !== 'investor' || !$user->investor) {
            return response()->json([
                'error' => 'Only investors can invest.'
            ], 403);
        }

        $data = $request->validate([
            'project_id'      => 'required|exists:projects,id',
            'number_of_trees' => 'required|integer|min:1',
        ]);

        $investment = Investment::create([
            'investor_id' => $user->investor->id,
            'project_id'  => $data['project_id'],
        ]);

        $this->tokenService->generateTokensForInvestment($investment, $data['number_of_trees']);

        return response()->json([
            'message'    => 'Investment created and tokens generated successfully!',
            'investment' => $investment,
        ]);
    }

    public function edit(int $id): View
    {
        $investment = Investment::findOrFail($id);
        return view('investments.edit', compact('investment'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'project_id'  => 'required|exists:projects,id',
        ]);

        $investment = Investment::findOrFail($id);
        $investment->update($data);

        return redirect()->route('investments.show', $id)
            ->with('success', 'Investment updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        return redirect()->route('investments.index')
            ->with('success', 'Investment deleted successfully!');
    }
}
