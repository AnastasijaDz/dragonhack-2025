<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Class ProfileController
 *
 * This controller manages user profile operations such as displaying the profile edit form,
 * updating profile information, and deleting the user account.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * Retrieves the currently authenticated user from the request and
     * renders the 'profile.edit' view with the user data.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The view displaying the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        // Retrieve the authenticated user from the request and pass it to the view.
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * Validates the incoming profile update request using ProfileUpdateRequest,
     * updates the user's data, and handles email verification reset if the email was changed.
     *
     * @param ProfileUpdateRequest $request The validated profile update request.
     * @return RedirectResponse Redirects back to the profile edit page with a status message.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Fill the user model with validated data from the request.
        $user = $request->user();
        $user->fill($request->validated());

        // If the email has been changed, reset the email verification timestamp.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the updated user model.
        $user->save();

        // Redirect back to the profile edit page with a status notification.
        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * Validates that the provided password is correct,
     * logs out the user, deletes the user account, invalidates the session,
     * and regenerates the session token before redirecting to the homepage.
     *
     * @param Request $request The incoming HTTP request.
     * @return RedirectResponse Redirects to the homepage after deleting the account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that the entered password is correct using the 'userDeletion' error bag.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Retrieve the current user before deleting.
        $user = $request->user();

        // Log the user out.
        Auth::logout();

        // Delete the user account.
        $user->delete();

        // Invalidate the user's current session.
        $request->session()->invalidate();
        // Regenerate the CSRF token to prevent potential CSRF attacks.
        $request->session()->regenerateToken();

        // Redirect the visitor to the homepage.
        return Redirect::to('/');
    }
}
