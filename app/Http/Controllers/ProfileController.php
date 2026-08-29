<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

/*     1. $request
    is an object containing things like:

    form data
    authenticated user
    validation methods
    request information */



    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

/*         fill() 
            is an Eloquent Model method.
            It fills the model's attributes with the given data. 
            does NOT save to the database. It only changes the model in memory.*/


/*         validated()
 */
/*         This comes from Laravel's FormRequest.
        It returns only the data that passed validation.

        Suppose the request contains:

        public function rules(): array
        {
            return [
                'name' => ['required', 'string'],
                'email' => ['required', 'email'],
            ];
        } */
        
        if ($request->user()->isDirty('email')) {
            /* If the email changed: */
            $request->user()->email_verified_at = null;
        }
        /* You can use:

        $user->isDirty()

        Check if anything changed.
        Or:
        $user->isDirty('email')
        Check if email changed.
        Or multiple attributes:
        $user->isDirty(['email', 'name'])
        Check whether any of those attributes changed.
        So:
        isDirty('email')
        means:"Has the email attribute been modified?" */


        $request->user()->save(); 
        /* is another Eloquent Model method.
        It saves the model's current state into the database. */

        return Redirect::route('profile.edit')->with('status', 'profile-updated');

        /* Redirect is Laravel's redirect helper/facade.
        It creates an HTTP redirect response.
        Redirect::route('profile.edit')
        means:
        Redirect the user to the route named profile.edit.

        You can also pass route parameters:
        Redirect::route('profile.show', ['id' => 5]);

        10. with()
        Then:
        ->with('status', 'profile-updated');
        This attaches flash session data to the redirect.
        So:
        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
        means:
        Redirect to profile.edit and put status = profile-updated in the session for the next request.

        Then your Blade can do:

        @if (session('status') === 'profile-updated')
            <p>Profile updated successfully!</p>
        @endif */
    } 

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', 
            [
                'password' => ['required', 'current_password'],
            ]);

          /*   because it wants to put validation errors into a specific error bag.
            The error bag is:
            Each form can have its own validation errors. */

        $user = $request->user();

        Auth::logout();/* It removes the authentication state from the session. */

        $user->delete();

        $request->session()->invalidate();/* This gets Laravel's current session store.
        Think of the session as temporary server-side data associated with the user's browser. */
/* 
       invalidate()  Destroy the current session and start fresh.
        This is useful after account deletion because you don't want the old authenticated session to remain active. */

        $request->session()->regenerateToken();/* This generates a new CSRF token.
        Laravel uses CSRF tokens to protect forms against Cross-Site Request Forgery attacks. */

        return Redirect::to('/');
    }
}
