<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Token;
use App\Services\TokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class InvestmentsController
 *
 * This controller handles operations related to investments. It allows listing,
 * viewing, creating, updating, and deleting investments. In addition, it manages token
 * generation during investment creation and token transfers between investments.
 */
class InvestmentsController extends Controller
{
    /**
     * The token service instance used for handling token related operations.
     *
     * @var TokenService
     */
    protected $tokenService;

    /**
     * InvestmentsController constructor.
     *
     * @param TokenService $tokenService The service used to manage tokens.
     */
    public function __construct(TokenService $tokenService)
    {
        // Initialize the token service
        $this->tokenService = $tokenService;
    }

    /**
     * List all investments.
     *
     * Retrieves all investment records and renders them in the investments.index view.
     *
     * @param Request $request The HTTP request instance.
     * @return View
     */
    public function index(Request $request)
    {
        // Retrieve all investment records from the database.
        $investments = Investment::all();

        // Return the view with the list of investments.
        return view('investments.index', compact('investments'));
    }

    /**
     * Show details for a single investment.
     *
     * Finds an investment by its ID and displays its details.
     *
     * @param int $id The investment ID.
     * @return View
     */
    public function show($id)
    {
        // Retrieve the investment or throw a 404 error if not found.
        $investment = Investment::findOrFail($id);

        // Return the view with the investment details.
        return view('investments.show', compact('investment'));
    }

    /**
     * Display the form to create a new investment.
     *
     * @return View
     */
    public function create()
    {
        // Return the investment creation form view.
        return view('investments.create');
    }

    /**
     * Process the submission and store a new investment along with generating tokens.
     *
     * This method validates the input data, creates a new investment, and then
     * triggers token generation for that investment.
     *
     * @param Request $request The HTTP request instance.
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data.
        $data = $request->validate([
            'investor_id'    => 'required|exists:investors,id',
            'project_id'     => 'required|exists:projects,id',
            'token_quantity' => 'required|integer|min:1',
        ]);

        // Create a new investment record using the validated data.
        $investment = Investment::create([
            'investor_id' => $data['investor_id'],
            'project_id'  => $data['project_id']
        ]);

        // Generate tokens for the new investment based on the provided token quantity.
        $this->tokenService->generateTokensForInvestment($investment, $data['token_quantity']);

        // Redirect to the investments index with a success message.
        return redirect()->route('investments.index')
            ->with('success', 'Investment created and tokens generated successfully!');
    }

    /**
     * Display the form to edit an existing investment.
     *
     * Finds the investment by ID and shows the form for editing.
     *
     * @param int $id The investment ID.
     * @return View
     */
    public function edit($id)
    {
        // Retrieve the investment record or throw a 404 error if not found.
        $investment = Investment::findOrFail($id);

        // Return the edit view with the investment details.
        return view('investments.edit', compact('investment'));
    }

    /**
     * Process the update of an investment.
     *
     * Validates input data and updates an existing investment record.
     *
     * Note: There is a possible typo in the validation rule ('project_id'). Consider verifying it for consistency.
     *
     * @param Request $request The HTTP request instance.
     * @param int $id The investment ID.
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming data.
        $data = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'project_id'  => 'required|exists:projects,id',
        ]);

        // Retrieve the investment record to update.
        $investment = Investment::findOrFail($id);

        // Update the investment with the validated data.
        $investment->update($data);

        // Redirect to the investment's detailed view with a success message.
        return redirect()->route('investments.show', $id)
            ->with('success', 'Investment updated successfully!');
    }

    /**
     * Delete an investment record.
     *
     * Finds the specified investment by its ID and deletes it.
     *
     * @param int $id The investment ID.
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // Retrieve the investment record or return a 404 error if not found.
        $investment = Investment::findOrFail($id);

        // Delete the investment from the database.
        $investment->delete();

        // Redirect to the investments index with a success message.
        return redirect()->route('investments.index')
            ->with('success', 'Investment deleted successfully!');
    }

    /**
     * Transfer a token from one investment to another.
     *
     * Validates the new investment ID, retrieves the token and target investment,
     * then performs the token transfer.
     *
     * @param Request $request The HTTP request instance.
     * @param int $tokenId The ID of the token to transfer.
     * @return RedirectResponse
     */
    public function transfer(Request $request, $tokenId)
    {
        // Validate input ensuring new_investment_id exists.
        $data = $request->validate([
            'new_investment_id' => 'required|exists:investments,id'
        ]);

        // Retrieve the token or return a 404 error if not found.
        $token = Token::findOrFail($tokenId);

        // Retrieve the destination investment record.
        $newInvestment = Investment::findOrFail($data['new_investment_id']);

        // Transfer the token to the new investment using the token service.
        $this->tokenService->transferToken($token, $newInvestment);

        // Redirect back to the previous page with a success message.
        return redirect()->back()
            ->with('success', 'Token transferred successfully!');
    }
}
