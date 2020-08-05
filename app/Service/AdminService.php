<?php

namespace App\Service;

use App\Models\Business;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminService
 *
 * @package \App\Service
 */
class AdminService
{
    function getUsers($request){
        $search = $request->get('search');
        $users = User::whereNotNull('id');
        if($search){
            $users->where(function($w) use($search){
                $w->where(DB::raw('CONCAT(first_name," ",last_name)'), 'LIKE', '%'. $search . '%' )
                    ->orWhere('username', 'LIKE', '%'. $search . '%' )
                    ->orWhere('email', 'LIKE', '%'. $search . '%' )
                    ;
            });
        }

        return $users->paginate();
    }

    function getPosts($request){
        $search = $request->get('search');
        $users = Post::whereNotNull('id')->where('is_image', 1);
        if($search){
            $users->whereHas('user', function($w) use($search){
                $w->where(DB::raw('CONCAT(first_name," ",last_name)'), 'LIKE', '%'. $search . '%' )
                    ->orWhere('username', 'LIKE', '%'. $search . '%' )
                    ->orWhere('email', 'LIKE', '%'. $search . '%' )
                ;
            });
        }

        return $users->paginate();
    }

    function getComments($request){
        $search = $request->get('search');
        $comemnts = Post::where('is_image', 0)->where('is_request', 0);

        if($search){
            $comemnts->whereHas('user', function($w) use($search){
                $w->where(DB::raw('CONCAT(first_name," ",last_name)'), 'LIKE', '%'. $search . '%' )
                    ->orWhere('username', 'LIKE', '%'. $search . '%' )
                    ->orWhere('email', 'LIKE', '%'. $search . '%' )
                ;
            });
        }

        return $comemnts->paginate();
    }

    function getCompanies($request){
        $search = $request->get('search');
        $companies = Business::whereNotNull('id');
        if($search){
            $companies->where(function($w) use($search){
                $w->where('name', 'LIKE', '%'. $search . '%' )
                  ->orWhere('email', 'LIKE', '%'. $search . '%' )
                  ->orWhere('phone', 'LIKE', '%'. $search . '%' )
                  ->orWhere('contact_person', 'LIKE', '%'. $search . '%' )
                  ->orWhere('contact_email', 'LIKE', '%'. $search . '%' )
                  ->orWhere('subdomain', 'LIKE', '%'. $search . '%' )
               ;
            });
        }

        return $companies->paginate();
    }

    function getPayments($request) {
        $payments =  Payment::with('business');
        return $payments->paginate();
    }
}
