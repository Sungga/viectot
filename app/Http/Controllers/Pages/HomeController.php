<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Carbon\Carbon; 

use App\Models\Account;
use App\Models\Category;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Post;
use App\Models\Cv;
use App\Models\Application;
use App\Models\Alert;
use App\Models\AlertAdmin;
use App\Models\Company_branch;
use App\Models\GeneratedCv;

class HomeController extends Controller
{
    // home page
    public function index() {
        // Lấy danh mục các ngành nghề
        $categories = Category::with(['keyWord'])->get();

        $today = Carbon::now();
        // lấy post tuyển dụng
        $posts = Post::with(['branch', 'branch.company', 'branch.branchProvince']) 
            ->where('status', '!=', 'lock')
            ->where('deadline', '>=', $today)
            ->orderBy('id', 'desc') // Sắp xếp mới nhất trước
            ->take(6) // Giới hạn 6 dòng
            ->get();
        
            // dd($posts);
        if($posts) {
            foreach ($posts as $post) {
                // add work mode show
                $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
                // add salary show
                $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
                // add gender show
                $post->gender_show = $this->makeGender($post->gender);
                // add experience show
                $post->experience_show = $this->makeExperience($post->experience);
                // add degree show
                $post->degree_show = $this->makeDegree($post->degree);
            }
        }

        $alerts = [];
        if(session()->has('user')) {
            $alerts = Alert::where('id_user', session('user.id'))->get();
        }

        $branches = Company_branch::with('post', 'company')
                                    ->orderBy('id', 'desc') // Sắp xếp mới nhất trước
                                    ->take(9) // Giới hạn 9 dòng
                                    ->get();
        // dd($posts);

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.home', compact('employer', 'categories', 'posts', 'alerts', 'branches'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.home', compact('candidate', 'categories', 'posts', 'alerts', 'branches'));
            }
        }
        return view('pages.home', compact('categories', 'posts', 'alerts', 'branches'));
    }

    public function branch($id_branch) {
        $categories = Category::all();

        $branch = Company_branch::where('id', $id_branch)->with(['company', 'branchProvince', 'branchDistrict', 'post'])->first();

        if($branch->post) {
            foreach ($branch->post as $post) {
                // add work mode show
                $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
                // add salary show
                $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
                // add gender show
                $post->gender_show = $this->makeGender($post->gender);
                // add experience show
                $post->experience_show = $this->makeExperience($post->experience);
                // add degree show
                $post->degree_show = $this->makeDegree($post->degree);
            }
        }

        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.branch', compact('employer', 'categories', 'branch'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.branch', compact('candidate', 'categories', 'branch'));;
            }
        }
        return view('pages.branch', compact('categories', 'branch'));
    }

    public function post($id_post) {
        // Lấy danh mục các ngành nghề
        $categories = Category::all();

        $post = Post::with(['branch', 'branch.company', 'branch.branchProvince', 'category_show'])
            ->where('id', $id_post)
            ->first();
        
        if($post->status == 'lock') {
            if(session('user.role' != '0')) {
                return view('notFound.notfound');
            }
        }

            // dd($post);

        // add work mode show
        $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
        // add salary show
        $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
        // add gender show
        $post->gender_show = $this->makeGender($post->gender);
        // add experience show
        $post->experience_show = $this->makeExperience($post->experience);
        // add degree show
        $post->degree_show = $this->makeDegree($post->degree);

        // dd($post->category_show);
        $categoryName = $post->category_show->name ?? 'Không rõ';

        $address = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$post->branch->latitude,$post->branch->longitude",
            'key' => env('GOOGLE_MAPS_API_KEY')
        ])->json()['results'][0]['formatted_address'] ?? $post->branch->branchDistrict->name.' - '.$post->branch->branchProvince->name;

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.post', compact('employer', 'categories', 'post', 'address', 'categoryName'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.post', compact('candidate', 'categories', 'post', 'address', 'categoryName'));;
            }
        }
        return view('pages.post', compact('categories', 'post', 'address', 'categoryName'));
    }

    public function listPost(Request $request) {
        $page = 1;
        if(isset($request->page)) {
            $page = $request->page;
        }
        $limit = 9; // Giới hạn số lượng bài viết trên mỗi trang
        $offset = ($page - 1) * $limit; // Tính toán offset

        // Tổng số bài đăng
        $totalPosts = Post::where('status', '!=', 'lock')->count();
        $totalPages = ceil($totalPosts / $limit);

        $today = Carbon::today();

        // lấy post tuyển dụng
        $posts = Post::with(['branch', 'branch.company', 'branch.branchProvince']) 
            ->where('status', '!=', 'lock')
            ->whereDate('deadline', '>=', $today)
            ->orderBy('id', 'desc') // Sắp xếp mới nhất trước
            ->take(9) // Giới hạn 9 dòng
            ->skip($offset) // Bỏ qua các bài viết trước đó
            ->get();
            
        // Lấy danh mục các ngành nghề
        $categories = Category::with(['keyWord'])->get();
        
            // dd($posts);
        foreach ($posts as $post) {
            // add work mode show
            $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
            // add salary show
            $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
            // add gender show
            $post->gender_show = $this->makeGender($post->gender);
            // add experience show
            $post->experience_show = $this->makeExperience($post->experience);
            // add degree show
            $post->degree_show = $this->makeDegree($post->degree);
        }

        // dd($posts);
        $pageName = 'listPost';
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.listPost', compact('employer', 'categories', 'posts', 'totalPages', 'page', 'pageName'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.listPost', compact('candidate', 'categories', 'posts', 'totalPages', 'page', 'pageName'));;
            }
        }

        return view('pages.listPost', compact('categories', 'posts', 'totalPages', 'page', 'pageName'));
    }

    public function listPostSearch(Request $request) {
        // dd($request->all());
        if (isset($request->search)) {
            $search = $request->search;
            // $categories = Category::all();
            $categories = Category::with(['keyWord'])->get();


            // Lấy post tuyển dụng có quan hệ liên quan
            $query = Post::with(['branch', 'branch.company', 'branch.branchProvince']);

            $query->where('status', '!=', 'lock');

            // Tìm theo từ khóa
            // if (!empty($search) && $search !== 'undefined' && trim($search) !== '') {
            //     // dd('here');
            //     $keywords = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

            //     $query->where(function ($q) use ($keywords) {
            //         foreach ($keywords as $word) {
            //             $q->where('title', 'LIKE', '%' . $word . '%');
            //         }
            //     });
            // }
            if (!empty($search) && $search !== 'undefined' && trim($search) !== '') {
                $keywords = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
                // dd($keywords);

                // $query->where(function ($q) use ($keywords) {
                //     foreach ($keywords as $word) {
                //         $q->where(function ($subQuery) use ($word) {
                //             $subQuery->where('title', 'LIKE', '%' . $word . '%')
                //                     ->orWhereHas('branch', function ($qBranch) use ($word) {
                //                         $qBranch->where('name', 'LIKE', '%' . $word . '%');
                //                     });
                //         });
                //     }
                // });
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->where(function ($subQuery) use ($word) {
                            $subQuery->where('title', 'LIKE', '%' . $word . '%')
                                ->orWhereHas('branch', function ($qBranch) use ($word) {
                                    $qBranch->where('name', 'LIKE', '%' . $word . '%');
                                })
                                ->orWhereHas('branch.company', function ($qCompany) use ($word) {
                                    $qCompany->where('name', 'LIKE', '%' . $word . '%');
                                });
                        });
                    }
                });
            }

            // Lọc theo địa chỉ district thông qua quan hệ
            if (isset($request->district)) {
                $districts = $request->district;

                $query->whereHas('branch', function ($q) use ($districts) {
                    $q->whereIn('district', $districts);
                });
            }

            // lọc theo lương
            if ($request->has('salary')) {
                $salaryFilter = $request->salary;

                $query->where(function ($q) use ($salaryFilter) {
                    switch ($salaryFilter) {
                        case 'Dưới 10 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) < 10000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) < 10000000");
                            });
                            break;

                        case 'Từ 10-15 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 10000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 15000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 10000000 AND 15000000");
                            });
                            break;

                        case 'Từ 15-20 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 15000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 20000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 15000000 AND 20000000");
                            });
                            break;

                        case 'Từ 20-25 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 20000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 25000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 20000000 AND 25000000");
                            });
                            break;

                        case 'Từ 25-30 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 25000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 30000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 25000000 AND 30000000");
                            });
                            break;

                        case 'Từ 30-50 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 30000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 50000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 30000000 AND 50000000");
                            });
                            break;

                        case 'Trên 50 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) > 50000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) > 50000000");
                            });
                            break;

                        case 'Thỏa thuận':
                            $q->where('salary_type', 'negotiable');
                            break;
                    }
                });
            }

            // lọc theo kinh nghiệm
            $experienceFilter = $request->input('experience');
            if ($experienceFilter) {
                $query->where(function ($q) use ($experienceFilter) {
                    switch ($experienceFilter) {
                        case 'Chưa có kinh nghiệm':
                            $q->where('experience', 'no_experience');
                            break;

                        case '1 năm trở xuống':
                            $q->whereIn('experience', ['no_experience', '1_year']);
                            break;

                        case '1 năm':
                            $q->where('experience', '1_year');
                            break;

                        case '2 năm':
                            $q->where('experience', '2_years'); // Nếu có thêm giá trị này trong DB
                            break;

                        case '3 năm':
                            $q->where('experience', '3_years'); // Tương tự
                            break;

                        case 'Từ 4-5 năm':
                            $q->whereIn('experience', ['over_3_years']);
                            break;

                        case 'Trên 5 năm':
                            $q->where('experience', 'over_5_years');
                            break;
                    }
                });
            }

            // lọc theo ngành nghề
            $categoryFilter = $request->input('category');
            if($categoryFilter) {
                $category = Category::where('name', $categoryFilter)->first();
                if($category) {
                    $query->where('category', $category->id);
                }
                // dd($category);
            }

            // lọc theo miền
            $mienBac = [1, 2, 4, 6, 8, 10, 11, 12, 14, 15, 17, 19, 20, 22, 24, 25, 26, 27, 30, 31];
            $mienTrung = [32, 33, 34, 35, 36, 37, 38, 40, 42, 44, 45, 46];
            $mienNam = [48, 49, 51, 52, 54, 56, 58, 60, 62, 64, 66, 67, 68, 70, 72, 74, 75, 77, 79, 80, 82, 83, 84, 86, 87, 89, 91, 92, 93, 94, 95, 96];

            if ($request->filled('region')) {
                $region = $request->input('region');

                $provinceIds = match($region) {
                    'bac' => $mienBac,
                    'trung' => $mienTrung,
                    'nam' => $mienNam,
                    default => []
                };

                // Lọc qua quan hệ
                $query->whereHas('branch', function ($q) use ($provinceIds) {
                    $q->whereIn('province', $provinceIds);
                });
            }

            // loc theo tỉnh
            if ($request->filled('provinceSearch')) {
                $provinceId = $request->input('provinceSearch'); // VD: 1 hoặc 79

                $query->whereHas('branch', function ($q) use ($provinceId) {
                    $q->where('province', $provinceId);
                });
            }

            // Lấy kết quả
            $today = Carbon::today();
            $postsNotYetPaged = $query->orderBy('id', 'desc')
                            ->whereDate('deadline', '>=', $today)
                            ->get();

            $limit = 9;
            $totalPosts = $postsNotYetPaged->count(); // Đếm số bài viết sau khi lọc
            $totalPages = ceil($totalPosts / $limit); // Tổng số trang

            // Lấy dữ liệu của trang hiện tại:
            $page = isset($request->page) ? (int)$request->page : 1;
            $offset = ($page - 1) * $limit;
            $posts = $postsNotYetPaged->slice($offset, $limit); // Cắt ra các bài cho trang hiện tại
            
                // dd($posts);
            foreach ($posts as $post) {
                // add work mode show
                $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
                // add salary show
                $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
                // add gender show
                $post->gender_show = $this->makeGender($post->gender);
                // add experience show
                $post->experience_show = $this->makeExperience($post->experience);
                // add degree show
                $post->degree_show = $this->makeDegree($post->degree);
            }

            // dd($posts);

            $pageName = 'listPostSearch';
            if(session()->has('user')) {
                if(session('user.role') == 1) { //la nha tuyen dung
                    $employer = Employer::where('id_user', session('user.id'))->first();
                    return view('pages.listPost', compact('employer', 'categories', 'posts', 'totalPages', 'page', 'pageName', 'search'));
                }
                else if(session('user.role') == 2) { //la ung vien
                    $candidate = Candidate::where('id_user', session('user.id'))->first();
                    return view('pages.listPost', compact('candidate', 'categories', 'posts', 'totalPages', 'page', 'pageName', 'search'));
                }
            }

            return view('pages.listPost', compact('categories', 'posts', 'totalPages', 'page', 'pageName', 'search'));
        }
        if (isset($request->id_branch)) {
            $id_branch = $request->id_branch;
            // $categories = Category::all();
            $categories = Category::with(['keyWord'])->get();

            // Lấy post tuyển dụng có quan hệ liên quan
            $query = Post::with(['branch', 'branch.company', 'branch.branchProvince']);

            $query->where('status', '!=', 'lock');
            $query->where('id_branch', $id_branch);

            // Tìm theo từ khóa
            // if (!empty($id_branch) && $id_branch !== 'undefined' && trim($id_branch) !== '') {

            //     $query->where(function ($q) use ($keywords) {
            //         foreach ($keywords as $word) {
            //             $q->where('title', 'LIKE', '%' . $word . '%');
            //         }
            //     });
            // }

            // Lọc theo địa chỉ district thông qua quan hệ
            if (isset($request->district)) {
                $districts = $request->district;

                $query->whereHas('branch', function ($q) use ($districts) {
                    $q->whereIn('district', $districts);
                });
            }

            // lọc theo lương
            if ($request->has('salary')) {
                $salaryFilter = $request->salary;

                $query->where(function ($q) use ($salaryFilter) {
                    switch ($salaryFilter) {
                        case 'Dưới 10 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) < 10000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) < 10000000");
                            });
                            break;

                        case 'Từ 10-15 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 10000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 15000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 10000000 AND 15000000");
                            });
                            break;

                        case 'Từ 15-20 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 15000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 20000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 15000000 AND 20000000");
                            });
                            break;

                        case 'Từ 20-25 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 20000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 25000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 20000000 AND 25000000");
                            });
                            break;

                        case 'Từ 25-30 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 25000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 30000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 25000000 AND 30000000");
                            });
                            break;

                        case 'Từ 30-50 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) >= 30000000")
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', -1) AS UNSIGNED) <= 50000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'upto', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) BETWEEN 30000000 AND 50000000");
                            });
                            break;

                        case 'Trên 50 triệu':
                            $q->where(function ($sub) {
                                $sub->where('salary_type', 'range')
                                    ->whereRaw("CAST(SUBSTRING_INDEX(salary, '-', 1) AS UNSIGNED) > 50000000");
                            })->orWhere(function ($sub) {
                                $sub->whereIn('salary_type', ['fixed', 'starting_from'])
                                    ->whereRaw("CAST(salary AS UNSIGNED) > 50000000");
                            });
                            break;

                        case 'Thỏa thuận':
                            $q->where('salary_type', 'negotiable');
                            break;
                    }
                });
            }

            // lọc theo kinh nghiệm
            $experienceFilter = $request->input('experience');
            if ($experienceFilter) {
                $query->where(function ($q) use ($experienceFilter) {
                    switch ($experienceFilter) {
                        case 'Chưa có kinh nghiệm':
                            $q->where('experience', 'no_experience');
                            break;

                        case '1 năm trở xuống':
                            $q->whereIn('experience', ['no_experience', '1_year']);
                            break;

                        case '1 năm':
                            $q->where('experience', '1_year');
                            break;

                        case '2 năm':
                            $q->where('experience', '2_years'); // Nếu có thêm giá trị này trong DB
                            break;

                        case '3 năm':
                            $q->where('experience', '3_years'); // Tương tự
                            break;

                        case 'Từ 4-5 năm':
                            $q->whereIn('experience', ['over_3_years']);
                            break;

                        case 'Trên 5 năm':
                            $q->where('experience', 'over_5_years');
                            break;
                    }
                });
            }

            // lọc theo ngành nghề
            $categoryFilter = $request->input('category');
            if($categoryFilter) {
                $category = Category::where('name', $categoryFilter)->first();
                if($category) {
                    $query->where('category', $category->id);
                }
                // dd($category);
            }

            // lọc theo miền
            $mienBac = [1, 2, 4, 6, 8, 10, 11, 12, 14, 15, 17, 19, 20, 22, 24, 25, 26, 27, 30, 31];
            $mienTrung = [32, 33, 34, 35, 36, 37, 38, 40, 42, 44, 45, 46];
            $mienNam = [48, 49, 51, 52, 54, 56, 58, 60, 62, 64, 66, 67, 68, 70, 72, 74, 75, 77, 79, 80, 82, 83, 84, 86, 87, 89, 91, 92, 93, 94, 95, 96];

            if ($request->filled('region')) {
                $region = $request->input('region');

                $provinceIds = match($region) {
                    'bac' => $mienBac,
                    'trung' => $mienTrung,
                    'nam' => $mienNam,
                    default => []
                };

                // Lọc qua quan hệ
                $query->whereHas('branch', function ($q) use ($provinceIds) {
                    $q->whereIn('province', $provinceIds);
                });
            }

            // loc theo tỉnh
            if ($request->filled('provinceSearch')) {
                $provinceId = $request->input('provinceSearch'); // VD: 1 hoặc 79

                $query->whereHas('branch', function ($q) use ($provinceId) {
                    $q->where('province', $provinceId);
                });
            }

            // Lấy kết quả
            $today = Carbon::today();
            $postsNotYetPaged = $query->orderBy('id', 'desc')
                            ->whereDate('deadline', '>=', $today)
                            ->get();

            $limit = 9;
            $totalPosts = $postsNotYetPaged->count(); // Đếm số bài viết sau khi lọc
            $totalPages = ceil($totalPosts / $limit); // Tổng số trang

            // Lấy dữ liệu của trang hiện tại:
            $page = isset($request->page) ? (int)$request->page : 1;
            $offset = ($page - 1) * $limit;
            $posts = $postsNotYetPaged->slice($offset, $limit); // Cắt ra các bài cho trang hiện tại
            
                // dd($posts);
            foreach ($posts as $post) {
                // add work mode show
                $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
                // add salary show
                $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
                // add gender show
                $post->gender_show = $this->makeGender($post->gender);
                // add experience show
                $post->experience_show = $this->makeExperience($post->experience);
                // add degree show
                $post->degree_show = $this->makeDegree($post->degree);
            }

            // dd($posts);
            $pageName = 'listPostSearchId';
            if(session()->has('user')) {
                if(session('user.role') == 1) { //la nha tuyen dung
                    $employer = Employer::where('id_user', session('user.id'))->first();
                    return view('pages.listPost', compact('employer', 'categories', 'posts', 'totalPages', 'page', 'pageName', 'id_branch'));
                }
                else if(session('user.role') == 2) { //la ung vien
                    $candidate = Candidate::where('id_user', session('user.id'))->first();
                    return view('pages.listPost', compact('candidate', 'categories', 'posts', 'totalPages', 'page', 'pageName', 'id_branch'));
                }
            }

            return view('pages.listPost', compact('categories', 'posts', 'totalPages', 'page', 'pageName', 'id_branch'));
        }
        return view('notFound.notfound');
    }

    public function showFormApply($id_post) {
        // Lấy danh mục các ngành nghề
        $categories = Category::all();

        $post = Post::with(['branch', 'branch.company', 'branch.branchProvince'])
            ->where('id', $id_post)
            ->first();

        // Kiểm tra hạn ứng tuyển
        if($post->deadline < date('Y-m-d')) {
            return redirect()->back()->with('error', 'Đã hết hạn ứng tuyển!');
        }

        // Kiểm tra đã ứng tuyển chưa
        $application = Application::where('id_post', $id_post)->where('id_candidate', session('user.id'))->first();
        if($application) {
            return redirect()->back()->with('error', 'Bạn đã ứng tuyển công việc này rồi!');
        }

        $cvs = Cv::where('id_user', session('user.id'))->get();

        if(count($cvs) == 0) {
            return redirect()->back()->with('error', 'Bạn chưa có cv để ứng tuyển!');
        }

        if(empty($post)) {
            return view('notFound.notfound');
        }

        $generatedCvs = [];
        foreach($cvs as $cv) {
            if($cv->file_name != 'CV của hệ thống') continue;
            $generatedCv = GeneratedCv::where('cv_id', $cv->id)->first();
            $generatedCvs[$cv->id] = $generatedCv;
        }

        // add work mode show
        $post->work_mode_show = $this->makeWorkMode($post->work_mode, $post->branch->branchProvince->name);
        // add salary show
        $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
        // add gender show
        $post->gender_show = $this->makeGender($post->gender);
        // add experience show
        $post->experience_show = $this->makeExperience($post->experience);
        // add degree show
        $post->degree_show = $this->makeDegree($post->degree);

        // dd($post);

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.apply', compact('employer', 'categories', 'post', 'cvs', 'id_post', 'generatedCvs'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.apply', compact('candidate', 'categories', 'post', 'cvs', 'id_post', 'generatedCvs'));;
            }
        }
        return view('pages.apply', compact('categories', 'post', 'cvs', 'id_post', 'generatedCvs'));
    }

    public function apply(Request $request, $id_post) {
        if(session('user.role') != 2) {
            // return redirect()->route('home');
            return redirect()->back()->with('error', 'Bạn không phải ứng viên!');
        }

        Application::create([
            'profileSummary' => $request->profileSummary,
            'id_cv' => $request->idCv,
            'id_candidate' => session('user.id'),
            'id_post' => $id_post,
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('succes', 'Ứng tuyển thành công');
    }

    public function listApplyPending() {
        $page = 1;
        if(isset($request->page)) {
            $page = $request->page;
        }
        $limit = 9; // Giới hạn số lượng bài viết trên mỗi trang
        $offset = ($page - 1) * $limit; // Tính toán offset

        // Tổng số bài đăng
        $totalPosts = Application::where('id_candidate', session('user.id'))
            ->where('status', 'pending')
            ->count();
        $totalPages = ceil($totalPosts / $limit);

        $posts = Application::with(['post', 'post.branch', 'post.branch.company', 'post.branch.branchProvince'])
            ->where('id_candidate', session('user.id'))
            ->where('status', 'pending')
            ->orderBy('id', 'desc') // Sắp xếp mới nhất trước
            ->take(9) // Giới hạn 9 dòng
            ->skip($offset) // Bỏ qua các bài viết trước đó
            ->get();
        
            // dd($posts);
        foreach ($posts as $post) {
            // add work mode show
            $post->post->work_mode_show = $this->makeWorkMode($post->post->work_mode, $post->post->branch->branchProvince->name);
            // add salary show
            $post->post->salary_show = $this->makeSalary($post->post->salary_type, $post->post->salary);
            // add gender show
            $post->post->gender_show = $this->makeGender($post->post->gender);
            // add experience show
            $post->post->experience_show = $this->makeExperience($post->post->experience);
            // add degree show
            $post->post->degree_show = $this->makeDegree($post->post->degree);
        }

        $pageName = 'listApplyPending';
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.listApply', compact('employer', 'posts', 'totalPages', 'page', 'pageName'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.listApply', compact('candidate', 'posts', 'totalPages', 'page', 'pageName'));;
            }
        }

        return view('pages.listApply', compact('candidate', 'posts', 'totalPages', 'page', 'pageName'));
    }

    public function listApplyPass() {
        $page = 1;
        if(isset($request->page)) {
            $page = $request->page;
        }
        $limit = 9; // Giới hạn số lượng bài viết trên mỗi trang
        $offset = ($page - 1) * $limit; // Tính toán offset

        // Tổng số bài đăng
        $totalPosts = Application::where('id_candidate', session('user.id'))
            ->where('status', 'pending')
            ->count();
        $totalPages = ceil($totalPosts / $limit);

        $posts = Application::with(['post', 'post.branch', 'post.branch.company', 'post.branch.branchProvince'])
            ->where('id_candidate', session('user.id'))
            ->where('status', 'pass')
            ->orderBy('id', 'desc') // Sắp xếp mới nhất trước
            ->take(9) // Giới hạn 9 dòng
            ->skip($offset) // Bỏ qua các bài viết trước đó
            ->get();

        // dd($posts);
        foreach ($posts as $post) {
            // add work mode show
            $post->post->work_mode_show = $this->makeWorkMode($post->post->work_mode, $post->post->branch->branchProvince->name);
            // add salary show
            $post->post->salary_show = $this->makeSalary($post->post->salary_type, $post->post->salary);
            // add gender show
            $post->post->gender_show = $this->makeGender($post->post->gender);
            // add experience show
            $post->post->experience_show = $this->makeExperience($post->post->experience);
            // add degree show
            $post->post->degree_show = $this->makeDegree($post->post->degree);
        }

        $pageName = 'listApplyPass';
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.listApply', compact('employer', 'posts', 'totalPages', 'page', 'pageName'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.listApply', compact('candidate', 'posts', 'totalPages', 'page', 'pageName'));;
            }
        }

        return view('pages.listApply', compact('candidate', 'posts', 'totalPages', 'page', 'pageName'));
    }

    public function listApplyReject() {
        $page = 1;
        if(isset($request->page)) {
            $page = $request->page;
        }
        $limit = 9; // Giới hạn số lượng bài viết trên mỗi trang
        $offset = ($page - 1) * $limit; // Tính toán offset

        // Tổng số bài đăng
        $totalPosts = Application::where('id_candidate', session('user.id'))
            ->where('status', 'pending')
            ->count();
        $totalPages = ceil($totalPosts / $limit);

        $posts = Application::with(['post', 'post.branch', 'post.branch.company', 'post.branch.branchProvince'])
            ->where('id_candidate', session('user.id'))
            ->where('status', 'reject')
            ->orderBy('id', 'desc') // Sắp xếp mới nhất trước
            ->take(9) // Giới hạn 9 dòng
            ->skip($offset) // Bỏ qua các bài viết trước đó
            ->get();
        
            // dd($posts);
        foreach ($posts as $post) {
            // add work mode show
            $post->post->work_mode_show = $this->makeWorkMode($post->post->work_mode, $post->post->branch->branchProvince->name);
            // add salary show
            $post->post->salary_show = $this->makeSalary($post->post->salary_type, $post->post->salary);
            // add gender show
            $post->post->gender_show = $this->makeGender($post->post->gender);
            // add experience show
            $post->post->experience_show = $this->makeExperience($post->post->experience);
            // add degree show
            $post->post->degree_show = $this->makeDegree($post->post->degree);
        }

        $pageName = 'listApplyReject';
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.listApply', compact('employer', 'posts', 'totalPages', 'page', 'pageName'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.listApply', compact('candidate', 'posts', 'totalPages', 'page', 'pageName'));;
            }
        }

        return view('pages.listApply', compact('candidate', 'posts', 'totalPages', 'page', 'pageName'));
    }

    public function alert($id) {
        if (!session()->has('user.id')) {
            return view('notFound.notfound');
        }

        $alertMain = Alert::where('id', $id)->first();

        $alertMain->status = 'read';
        $alertMain->save();

        $alerts = Alert::where('id_user', session('user.id'))->get();
        // dd($alerts);

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.alert', compact('employer', 'alertMain', 'alerts'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.alert', compact('candidate', 'alertMain', 'alerts'));
            }
        }
    }

    public function listAlert() {
        if(!session()->has('user')) return view('notFound.notfound');

        $alerts = Alert::where('id_user', session('user.id'))->get();

        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.listAlert', compact('alerts', 'employer'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.listAlert', compact('alerts', 'candidate'));
            }
        }
    }

    public function showFormReport($id_post) {
        if(!session()->has('user')) return view('notFound.notfound');

        $post = Post::with('branch', 'branch.company')->where('id', $id_post)->first();

        $alerts = Alert::where('id_user', session('user.id'))->get();

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.report', compact('alerts', 'employer', 'post'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.report', compact('alerts', 'candidate', 'post'));
            }
        }
        return view('pages.home', compact('categories', 'posts', 'alerts', 'post'));
    }

    public function showFormComplaint($id_post) {
        if(!session()->has('user')) return view('notFound.notfound');

        $post = Post::with('branch', 'branch.company')->where('id', $id_post)->first();

        $alerts = Alert::where('id_user', session('user.id'))->get();

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('pages.complaint', compact('alerts', 'employer', 'post'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.complaint', compact('alerts', 'candidate', 'post'));
            }
        }
        return view('pages.home', compact('categories', 'posts', 'alerts', 'post'));
    }

    public function report($id_post, Request $request) {
        if (!session()->has('user')) return view('notFound.notfound');

        $filename = null;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            $filename = session('user.id') . '_' . now()->format('YmdHis') . '.' . $extension;
            $file->move(storage_path('app/public/report'), $filename);
        }

        AlertAdmin::create([
            'reporter_name' => 'Báo cáo bài viết',
            'reporter_id' => session('user.id'),
            'content' => $request->content,
            'image_path' => $filename,
            'post_id' => $id_post,
            'status' => 'pending',
        ]);

        return redirect()->route('post.show', ['id_post' => $id_post])->with('success', 'Báo cáo của bạn đã được gửi.');
    }

    public function complaint($id_post, Request $request) {
        if (!session()->has('user')) return view('notFound.notfound');
        

        $filename = null;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            $filename = session('user.id') . '_' . now()->format('YmdHis') . '.' . $extension;
            $file->move(storage_path('app/public/report'), $filename);
        }

        AlertAdmin::create([
            'reporter_name' => 'Khiếu nại bài viết',
            'reporter_id' => session('user.id'),
            'content' => $request->content,
            'image_path' => $filename,
            'post_id' => $id_post,
            'status' => 'pending',
        ]);

        return redirect()->route('employer.listCv', ['id_post' => $id_post])->with('success', 'Khiếu nại của bạn đã được gửi.');
    }

    public function getTotalJobToday() {
        $total = Post::whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'total' => $total,
        ]);
    }
    public function getTotalJob() {
        $total = Post::count();

        return response()->json([
            'total' => $total,
        ]);
    }

    public function dashboard() {
        $posts = Post::with(['branch', 'branch.company', 'branch.branchProvince'])->latest()->take(5)->get();
        $totalJobToday = Post::whereDate('created_at', Carbon::today())->count();
        $totalJob = Post::count();
        $totalBranch = Company_branch::count();


        // ----------total job every day-------------
        $today = Carbon::today();
        $results = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i);
            
            $count = Post::whereDate('created_at', '=', $day)->count();
            
            $results[] = [
                'date' => $day->toDateString(),
                'total' => $count
            ];
        }

        // ----------top category----------
        $topCategories = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(5) // 👈 lấy top 10
            ->get();
        return view('dashboard.dashboard', compact('posts', 'totalJobToday', 'totalJob', 'totalBranch', 'results', 'topCategories'));
    }





















    // function to perform the problem
    private function makeWorkMode($workMode, $locationBranch) {
        $map = [
            'onsite' => $locationBranch,
            'remote' => 'Từ xa',
            'hybrid' => 'Kết hợp',
            'other' => 'Hình thức đặc biệt',
        ];
        return $map[$workMode] ?? '';
    }

    private function makeSalary($salaryType, $salary) {
        if ($salaryType == 'negotiable') {
            return 'Thương lượng';
        } else if ($salaryType == 'range') {
            list($from, $to) = explode('-', $salary);
            $from = number_format($from, 0, ',', '.') . ' VNĐ';
            $to = number_format($to, 0, ',', '.') . ' VNĐ';
            return 'Từ: ' . $from . ' - Đến: ' . $to;
        } else if ($salaryType == 'upto') {
            return 'Lên đến: ' . $salary;
        } else if ($salaryType == 'fixed') {
            return $salary;
        } else if ($salaryType == 'starting_from') {
            return 'Từ: ' . $salary;
        }
        return '';
    }

    private function makeGender($gender) {
        $map = [
            'any' => '',
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác',
        ];
        return $map[$gender] ?? '';
    }

    private function makeExperience($experience) {
        $map = [
            '' => '',
            'no_experience' => 'Không yêu cầu',
            'less_than_1_year' => 'Dưới 1 năm',
            '1_year' => '1 năm',
            '2_years' => '2 năm',
            '3_years' => '3 năm',
            'over_3_years' => 'trên 3 năm',
            'over_5_years' => 'trên 5 năm',
        ];
        return $map[$experience] ?? '';
    }

    private function makeDegree($degree) {
        $map = [
            'none' => 'Không yêu cầu',
            'high_school' => 'Tốt nghiệp THPT',
            'associate' => 'Cao đẳng',
            'bachelor' => 'Đại học',
            'master' => 'Thạc sĩ',
            'doctor' => 'Tiến sĩ',
        ];
        return $map[$degree] ?? '';
    }

}
