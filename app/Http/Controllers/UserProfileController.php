<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Fortify\Features;
use Illuminate\Http\Request;

class UserProfileController extends Controller {

    public function index(): View {
        return view(
            'profile/show',
            [
                'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
                'user' => Users::all()->where('id', \Auth::id())->first(),
            ]
        );
    }

    public function updateUserDetails(Request $request): \Illuminate\Http\RedirectResponse {

        $id = \Auth::id();
        /** @var Users $user */
        $user = Users::all()->where('id', $id)->first();

        $name = trim($request->all()['name'] ?? '');
        $email = trim($request->all()['email'] ?? '');

        auth()->user()->update([
            'name' => ($name === '') ? $user->name : $name,
            'email' => ($email === '') ? $user->email : $email,
        ]);

        return redirect('user/profile');
    }

    public function updateUserPassword(Request $request): \Illuminate\Http\RedirectResponse {

        $id = \Auth::id();
        /** @var Users $user */
        $user = Users::all()->where('id', $id)->first();

        $passwordCurrent = $request->input('current_password') ?? '';
        $password = $request->input('password') ?? '';
        $passwordConfirmation = $request->input('password_confirmation') ?? '';

        if (
            $passwordCurrent === ''
            || $password === ''
            || $passwordConfirmation === ''
        ) {
            exit('Passwort darf nicht leer sein');
        }

        if ($password !== $passwordConfirmation) {
            exit('Passwörter passen nicht überein');
        }

        $hasher = app('hash');
        if (!$hasher->check($passwordCurrent, $user->password)) {
            exit('Passwörter passen nicht überein.');
        }

        auth()->user()->update(['password' => Hash::make($password)]);
        \Auth::guard('web')->login($user);

        return redirect('user/profile');
    }
}
