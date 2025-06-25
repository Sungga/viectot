<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

use App\Models\Account;
use App\Models\Candidate;
use App\Models\Employer;

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback () {
        $googleUser = Socialite::driver('google')->stateless()->user();
        // dd($googleUser);

        // $user = User::updateOrCreate([
        //     'email' => $googleUser->getEmail(),
        // ], [
        //     'name' => $googleUser->getName(),
        //     'google_id' => $googleUser->getId(),
        //     'avatar' => $googleUser->getAvatar(),
        // ]);

        $user = Account::where('email', $googleUser->email)->first();
        if($user == null) {
            $user = Account::create([
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
            ]);
            $id = $user->id;
            return view('pages.addRole', compact('id'));
        }
        if($user->role == 3) {
            $id = $user->id;
            return view('pages.addRole', compact('id'));
        }
        session(['user' => ['id' => $user->id, 'role' => $user->role]]);
        return redirect()->route('home');
    }

    public function addRole(Request $request) {
        $id = $request->id ? $request->id : '';
        $role = $request->role ? $request->role : '3';
        $name = $request->name ? $request->name : 'Không xác định';
        Account::where('id', $id)->update(['role' => $role]);
        
        if($role == '1') {
            Employer::updateOrCreate(
                ['id_user' => $id], 
                ['id_user' => $id, 'name' => $name] 
            );
        }
        elseif($role == '2') {
            Candidate::updateOrCreate(
                ['id_user' => $id], 
                ['id_user' => $id, 'name' => $name] 
            );
        }
        
        session(['user' => ['id' => $id, 'role' => $role]]);

        return redirect()->route('home');
    }
}
