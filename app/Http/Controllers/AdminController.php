<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(protected AdminRepository $adminRepository)
    {
    }

//    Auth
    public function auth(LoginRequest $request){
        $admin = $this->adminRepository->getAdmin($request->username);
        if (!$admin){
            return back()->with('login_error', 1);
        }
        if (Hash::check('admin', '$2y$10$go4KazGTvcWw3zB2G1GzjOJavJQLY4PkX2wHXXf.lM2GLD1qH2UsG')) {
            return 1;
        }
        return Hash::make($request->password);
    }
}
