<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chat;
use App\Models\Employer;
use App\Models\Candidate;
use App\Models\Alert;
use App\Models\Company_branch;
use App\Models\Company;
use App\Models\Application;

use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index($type ,$id_branch, $id_candidate, $id_apply) {
        // kiem tra quyen truy cap
        if ($type === 'candidate'
            && (
                $id_candidate   != session('user.id')
                || session('user.role') != 2
            )
        ) {
            // return view('notFound.notfound');
        }

        if ($type === 'branch') {
            // && (
            //     $id_branch   != session('user.id')
            //     || session('user.role') != 1
            // )
            $check1 = Company_branch::where('id', $id_branch)->first();
            // dd($check1);
            if($check1->id_user_manager != session('user.id')) {
                $check2 = Company::where('id', $check1->id_company)->first();
                if($check2->id_user_main != session('user.id')) 
                    return view('notFound.notfound');
            }
        }

        $alerts = Alert::where('id_user', session('user.id'))->get();

        $messages = Chat::where('id_branch', $id_branch)
                            -> where('id_candidate', $id_candidate)
                            ->get();

        if(empty($messages)) {
            return view('notFound.notfound');
        }

        Chat::where('id_branch', $id_branch)
            ->where('id_candidate', $id_candidate)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        $branch = Company_branch::where('id', $id_branch)->first();
        // $branchName = $branch->name;
        $company = Company::where('id', $branch->id_company)->first();
        $candidate = Candidate::where('id', $id_candidate)->first();
        // $nameAll = ['branchName' => $branchName, 'companyName' => $companyName, 'candidateName' => $candidateName];

        $apply = Application::with(['post'])->where('id', $id_apply)->first();

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('chat.chat', compact('id_branch', 'id_candidate', 'employer', 'alerts', 'type', 'messages', 'branch', 'company', 'candidate', 'apply'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('chat.chat', compact('id_branch', 'id_candidate', 'candidate', 'alerts', 'type', 'messages', 'branch', 'company', 'candidate', 'apply'));
            }
        }
        return view('chat.chat', compact('id_branch', 'id_candidate', 'type', 'messages', 'branch', 'company', 'candidate', 'apply'));
    } 

    public function getMessages($id_branch, $id_candidate, $id_post, $idMax) {
        $query = Chat::where('id_branch', $id_branch)->where('id_candidate', $id_candidate)->where('id_post', $id_post);

        if ($idMax) {
            $query->where('id', '>', $idMax);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // Gửi tin nhắn
    public function sendMessage(Request $request)
    {
        // $request->validate([
        //     'id_branch' => 'required|integer',
        //     'id_candidate' => 'required|integer',
        //     'id_post' => 'required|integer',
        //     'type' => 'required|string',
        //     'message' => 'required|string',
        // ]);

        $chat = Chat::create([
            'id_branch' => $request->id_branch,
            'id_candidate' => $request->id_candidate,
            'id_post' => $request->id_post,
            'sender' => $request->type,
            'message' => $request->message,
        ]);

        return response()->json($chat);
    }

    public function listMessage() {
        if(!session()->has('user')) {
            return view('notFound.notfound');
        }

        $userId = session('user.id');

        // $messages = DB::table('chats as c1')
        //     ->select('c1.*', 'b.name as branch_name')
        //     ->join(DB::raw('(SELECT id_branch, MAX(id) as max_id FROM chats GROUP BY id_branch) as c2'), function ($join) {
        //         $join->on('c1.id', '=', 'c2.max_id')
        //             ->on('c1.id_branch', '=', 'c2.id_branch');
        //     })
        //     ->join('company_branches as b', 'c1.id_branch', '=', 'b.id')
        //     ->where('c1.id_candidate', $userId)
        //     ->orderBy('c1.id', 'desc')
        //     ->get();
        $messages = DB::table('chats as c1')
            ->select('c1.*', 'p.title as post_title') // giả sử bảng post có 'title'
            ->join(DB::raw('
                (SELECT id_post, MAX(id) as max_id 
                FROM chats 
                WHERE id_candidate = ' . (int) $userId . '
                GROUP BY id_post
                ) as c2'
            ), function ($join) {
                $join->on('c1.id', '=', 'c2.max_id')
                    ->on('c1.id_post', '=', 'c2.id_post');
            })
            ->join('posts as p', 'c1.id_post', '=', 'p.id') // nếu muốn thêm thông tin bài post
            ->orderBy('c1.id', 'desc')
            ->get();

            // dd($messages);

        foreach($messages as $message) {
            $message->apply = Application::where('id_post', $message->id_post)->where('id_candidate', $message->id_candidate)->first();
            $message->branch = Company_branch::where('id', $message->id_branch)->first();
        }

        // dd($messages);
        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('notFound.notfound');
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('chat.listChat', compact('candidate', 'messages'));
            }
        }
        return view('chat.listChat', compact('messages'));
    }
}
