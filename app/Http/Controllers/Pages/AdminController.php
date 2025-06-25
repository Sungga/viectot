<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AlertAdmin;
use App\Models\Candidate;
use App\Models\Account;
use App\Models\Employer;
use App\Models\Post;
use App\Models\Alert;
use App\Models\Category;
use App\Models\Company_branch;
use App\Models\Company;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function listAlert() {
        $alerts = AlertAdmin::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        $reporter = [];
        // dd($alerts);

        foreach($alerts as $alert) {
            $account  = Account::where('id', $alert->reporter_id)->first();
            if($account->role == 1) {
                $reporter[$alert->id] =  Employer::where('id_user', $account->id)->first();
            }
            else if($account->role == 2) {
                $reporter[$alert->id] =  Candidate::where('id_user', $account->id)->first();
            }
            else {
                $reporter[$alert->id]['name'] = "Không rõ";
            }
        }

        // dd($reporter);

        return view('admin.listAlert', compact('alerts', 'reporter'));
    }

    public function listAlertOk() {
        $alerts = AlertAdmin::where('status', '!=', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        $reporter = [];
        // dd($alerts);

        foreach($alerts as $alert) {
            $account  = Account::where('id', $alert->reporter_id)->first();
            if($account->role == 1) {
                $reporter[$alert->id] =  Employer::where('id_user', $account->id)->first();
            }
            else if($account->role == 2) {
                $reporter[$alert->id] =  Candidate::where('id_user', $account->id)->first();
            }
            else {
                $reporter[$alert->id]['name'] = "Không rõ";
            }
        }

        // dd($reporter);

        return view('admin.listAlertOk', compact('alerts', 'reporter'));
    }

    public function alert($id) {
        $alert = AlertAdmin::where('id', $id)->first();
        // dd($alerts);

        $account  = Account::where('id', $alert->reporter_id)->first();
        if($account->role == 1) {
            $reporter =  Employer::where('id_user', $account->id)->first();
        }
        else if($account->role == 2) {
            $reporter =  Candidate::where('id_user', $account->id)->first();
        }
        else {
            $reporter['name'] = "Không rõ"; 
        }

        // dd($reporter);

        return view('admin.alert', compact('alert', 'reporter'));
    }

    public function lockPost($id) {
        $report = AlertAdmin::where('id', $id)->first();
        if($report == []) return view('notFound.notfound');

        $report->status = 'pass';
        $report->save();
        $post = Post::with('branch', 'branch.company')->where('id', $report->post_id)->first();
        $post->status = 'lock';

        // Thông báo cho người báo cáo
        Alert::create([
            'title' => 'Thông báo hệ thống',
            'content' => 'Báo cáo của bạn được duyệt và chúng tôi đã thực hiện khóa bài đăng: '.$post->title,
            'type' => 'lock',
            'status' => 'unread',
            'id_user' => $report->reporter_id,
        ]);

        // Thông báo cho chủ công ty
        Alert::create([
            'title' => 'Thông báo hệ thống',
            'content' => '<p>Tin tuyển dụng "'.$post->title.'" của chi nhánh "'.$post->branch->name.'" thuộc công ty "'.$post->branch->company->name.'" đã bị khóa do nhận được báo cáo vi phạm</p>
            <p><strong>Nội dung báo cáo vi phạm: </strong></p>
            <p>'.$report->content.'</p>',
            'type' => 'lock',
            'status' => 'unread',
            'id_user' => $post->branch->company->id_user_main,
            'id_post' => $post->id,
        ]);

        return redirect()->route('admin');
    }

    public function unLockPost($id) {
        $report = AlertAdmin::where('id', $id)->first();
        if($report == []) return view('notFound.notfound');

        $report->status = 'pass';
        $report->save();
        $post = Post::with('branch', 'branch.company')->where('id', $report->post_id)->first();
        $post->status = 'active';
        $post->save();

        // Thông báo cho người báo cáo
        Alert::create([
            'title' => 'Thông báo hệ thống',
            'content' => 'Khiếu nại của bạn được duyệt và chúng tôi đã thực hiện mở khóa bài đăng: '.$post->title,
            'type' => 'unlock',
            'status' => 'unread',
            'id_user' => $report->reporter_id,
            'id_post' => $post->id,
        ]);

        return redirect()->route('admin');
    }

    public function rejectAlert($id) {
        $report = AlertAdmin::where('id', $id)->first();
        // dd($report);
        if($report == []) return view('notFound.notfound');

        $report->status = 'reject';
        $report->save();
        $post = Post::with('branch', 'branch.company')->where('id', $report->post_id)->first();
        
        if($report->reporter_name == 'Báo cáo bài viết') {
            // Thông báo cho người báo cáo
            Alert::create([
                'title' => 'Thông báo hệ thống',
                'content' => 'Báo cáo bài đăng '.$post->title.' của bạn không được duyệt',
                'type' => 'reject',
                'status' => 'unread',
                'id_user' => $report->reporter_id,
            ]);
        }
        else {
            // Thông báo cho người khiếu nại
            Alert::create([
                'title' => 'Thông báo hệ thống',
                'content' => 'Khiếu nại bài đăng '.$post->title.' của bạn không được duyệt',
                'type' => 'reject',
                'status' => 'unread',
                'id_user' => $report->reporter_id,
            ]);
        }


        return redirect()->route('admin');
    }

    public function listCandidate() {
        $candidates = Candidate::with('account')->get();
        $idEmployer = 'false'; 
        // dd($candidates);
        // dd($candidate);
        return view('admin.listUser', compact('candidates', 'idEmployer'));
    }

    public function listEmployer() {
        $employers = Employer::with('account')->get();
        // dd($employers);
        $idEmployer = 'true'; 
        // dd($candidates);
        // dd($candidate);
        return view('admin.listUser', compact('employers', 'idEmployer'));
    }

    public function listPost() {
        $posts = Post::with('branch', 'branch.company')->get();
        return view('admin.listPost', compact('posts'));
    }

    public function lockUser($id_user) {
        Account::where('id', $id_user)->update([
            'status' => 'lock',
        ]);

        return redirect()->back();
    }

    public function unlockUser($id_user) {
        Account::where('id', $id_user)->update([
            'status' => 'active',
        ]);

        return redirect()->back();
    }

    public function alertLockAccount() {
        return view('pages.alertLockAccount');
    }

    public function dashboard() {
        // ----------total job----------
        $results = [];
        $today = Carbon::today();

        for ($i = 30; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i);
            
            $count = Post::whereDate('created_at', '<=', $day)->count();
            
            $results[] = [
                'date' => $day->toDateString(),
                'total' => $count
            ];
        }

        // $jobTotalToDay = Post::where('created_at', $today)->count();
        $jobTotalToDay = Post::whereDate('created_at', Carbon::today())->count();
        $jobTotal = Post::count();
        $branchTotal = Post::count();

        // ----------top category----------
        $topCategories = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(10) // 👈 lấy top 10
            ->get();

        // dd($topCategories);

        // dd($results);
        return view('admin.dashboard', compact('results', 'topCategories', 'jobTotalToDay', 'jobTotal', 'branchTotal'));
    }
}
