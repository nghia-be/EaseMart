<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Traits\DataTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends MY_Controller
{
    //
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        $paramUpdate = [
            'password' => Hash::make($request->password)
        ];
        $this->updateById(new User(), Auth::user()->id, $paramUpdate);
        auth()->setUser(User::find(Auth::user()->id));
        $this->addToLog(request());
        return true;
    }
    public function  updateprofile(UpdateProfileRequest $request)
    {
        $paramUpdate = [
            'name' => $request->name
        ];
        $this->updateById(new User(), Auth::user()->id, $paramUpdate);
        $this->addToLog(request());
        return redirect()->back()->withSuccess('success');
    }
}
