<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use App\Service\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $service;
    public function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    function index(Request $request) {
        $data['requests_count'] = Post::where('is_request', 1)->where('is_draft', 0)->count();
        $data['exhcanges_count'] = Post::where('is_request', 0)->where('is_image', 1)->where('is_draft', 0)->count();
        $data['users_count'] = User::where('id', '!=', 1)->count();
        $data['business_count'] = Business::count();
        $data['comments_count'] = Post::where('is_request', 0)->where('is_image', 0)->where('is_draft', 0)->count();
        return view('cpanel.index', $data);
    }

    function posts(Request $request) {
        $data['posts'] = $this->service->getPosts($request);
        $data['companies'] = Business::all();
        return view('cpanel.posts', $data);
    }

    function users(Request $request) {
        $data['users'] = $this->service->getUsers($request);
        return view('cpanel.users', $data);
    }

    function comments(Request $request) {
        $data['comments'] = $this->service->getComments($request);
        return view('cpanel.comments', $data);
    }

    function businesses(Request $request) {
        $data['companies'] = $this->service->getCompanies($request);
        return view('cpanel.companies', $data);
    }

    function ads() {
        return view('cpanel.ads');
    }

    function payments(Request $request) {
        $data['payments'] = $this->service->getPayments($request);
        return view('cpanel.payment', $data);
    }
}
