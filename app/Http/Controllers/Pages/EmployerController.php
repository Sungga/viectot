<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\Category;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Cv;
use App\Models\Company;
use App\Models\Company_branch;
use App\Models\Company_document;
use App\Models\Branch_img;
use App\Models\Post;
use App\Models\Application;
use App\Models\Alert;
use App\Models\Chat;
use App\Models\GeneratedCv;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployerController extends Controller
{
    public function listCv() {
        // Lấy danh mục các ngành nghề
        $categories = Category::all();

        // Lấy những Cv là main của ứng viên
        $cvs = Cv::where('status', '1')->get();
        $generatedCvs = [];
        foreach($cvs as $cv) {
            if($cv->file_name != 'CV của hệ thống') continue;
            $generatedCv = GeneratedCv::where('cv_id', $cv->id)->first();
            $generatedCvs[$cv->id] = $generatedCv;
        }

        // Lấy tất cả ứng viên
        $candidates = Candidate::all();

        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('employer.listCv', compact('employer', 'categories', 'cvs', 'candidates', 'generatedCvs'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('employer.listCv', compact('candidate', 'categories', 'cvs', 'candidates', 'generatedCvs'));
            }
        }

        return view('employer.listCv', compact('candidate', 'categories', 'cvs', 'candidates', 'generatedCvs'));
    }

    public function candidateProfile(Request $request) {
        if(!isset($request->id)) return redirect()->route('employer.listCv');
        $id = $request->id;
        $candidatePrf = Candidate::where('id_user', $id)->first();
        $categories = Category::all();

        $apply = [];
        if(isset($request->id_apply)) {
            $apply = Application::where('id', $request->id_apply)->first();
            $cv = Cv::where('id_user', $id)->where('id', $apply->id_cv)->first();
        }
        else {
            $cv = Cv::where('id_user', $id)->where('status', '1')->first();
        }

        $generatedCvs = [];
        if($cv->file_name == 'CV của hệ thống') {
            $generatedCv = GeneratedCv::where('cv_id', $cv->id)->first();
            $generatedCvs[$cv->id] = $generatedCv;
        }
        // dd($candidatePrf);
       
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                $employer = Employer::where('id_user', session('user.id'))->first();
                return view('employer.candidateProfile', compact('employer', 'candidatePrf', 'categories', 'cv', 'generatedCvs', 'apply'));
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('employer.candidateProfile', compact('candidate', 'candidatePrf', 'categories', 'cv', 'generatedCvs', 'apply'));
            }
        }

        return view('employer.candidateProfile', compact('candidatePrf', 'categories', 'cv', 'generatedCvs', 'apply'));
    }

    public function listBranch() {
        $employer = Employer::where('id_user', session('user.id'))->first();
        $yourCompanies = Company::where('id_user_main', session('user.id'))->get();

        $ownedBranches = Company_branch::with(['images', 'branchProvince', 'branchDistrict'])
            ->whereIn('id_company', $yourCompanies->pluck('id'))
            ->get();

        $countPosts = [];
        $managedBranches = [];
        // Tính số lượng bài đăng cho mỗi chi nhánh
        foreach ($ownedBranches as $branch) {
            $countPosts[$branch->id] = Post::where('id_branch', $branch->id)->count();
            $managedBranches[$branch->id] = Employer::where('id_user', $branch->id_user_manager)->first();
        }

        // Lấy tất cả các chi nhánh mà người dùng quản lý
        $branchManagers = Company_branch::with(['images', 'branchProvince', 'branchDistrict'])
            ->where('id_user_manager', session('user.id'))
            ->get();

        // Tính số lượng bài đăng cho mỗi chi nhánh quản lý
        foreach ($branchManagers as $branch) {
            $countPosts[$branch->id] = Post::where('id_branch', $branch->id)->count();
        }

        // lấy danh sách provinces và districts
        $provinces = \App\Models\Province::all();
        $districts = \App\Models\District::all();
        
        return view('employer.listBranch', compact('employer', 'yourCompanies', 'ownedBranches', 'countPosts', 'managedBranches', 'branchManagers', 'provinces', 'districts'));
    }

    public function addCompany(Request $request) {
        $company = $request->all();
        $companyDocuments = $request->file('document');
        
        // xu lt logo
        $logo = $request->file('logo');
        $company['logo'] = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path('storage/uploads'), $company['logo']);

        // Tạo công ty
        $newCompany = Company::create([
            'name' => $company['name'],
            'desc' => $company['desc'],
            'id_user_main' => session('user.id'),
            'logo' => $company['logo'],
            'status' => 0,
        ]);
    
        // Xử lý tài liệu đính kèm
        foreach ($companyDocuments as $document) {
            $fileName = time() . '_' . $document->getClientOriginalName();
            
            $mimeType = $document->getMimeType();
            
            $type = 'unknown';
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif ($mimeType === 'application/pdf') {
                $type = 'pdf';
            }
            
            $document->move(public_path('storage/companyDocuments'), $fileName);

            Company_document::create([
                'id_company' => $newCompany->id,
                'type' => $type, // hoặc 'pdf' tùy thuộc vào loại tài liệu
                'file_path' => $fileName,
            ]);
        }
    
        return redirect()->route('employer.listBranch')->with('success', 'Thêm công ty thành công!');
    }

    public function deleteCompany($id_company) {
        $companyId = $id_company;
        $company = Company::find($companyId);

        if (!$company) {
            return redirect()->route('employer.listBranch')->with('error', 'Công ty không tồn tại!');
        }
        if($company->id_user_main != session('user.id')) {
            return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền xóa công ty này!');
        }

        // Xóa công ty
        $company->delete();

        return redirect()->route('employer.listBranch')->with('success', 'Xóa công ty thành công!');
    }

    public function updateCompany(Request $request) {
        $company = $request->all();
        $companyDocuments = $request->file('document');
        
        // xu lt logo
        if($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $company['logo'] = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('storage/uploads'), $company['logo']);
        } else {
            unset($company['logo']);
        }

        // Cập nhật công ty
        $existingCompany = Company::find($company['id_company']);
        if (!$existingCompany) {
            return redirect()->route('employer.listBranch')->with('error', 'Công ty không tồn tại!');
        }
        if($existingCompany->id_user_main != session('user.id')) {
            return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền cập nhật công ty này!');
        }

        $existingCompany->update([
            'name' => $company['name'],
            'desc' => $company['desc'],
            'logo' => isset($company['logo']) ? $company['logo'] : $existingCompany->logo,
        ]);

        // Xử lý tài liệu đính kèm
        if ($companyDocuments) {
            foreach ($companyDocuments as $document) {
                $fileName = time() . '_' . $document->getClientOriginalName();
                
                $mimeType = $document->getMimeType();
                
                $type = 'unknown';
                if (str_starts_with($mimeType, 'image/')) {
                    $type = 'image';
                } elseif ($mimeType === 'application/pdf') {
                    $type = 'pdf';
                }
                
                $document->move(public_path('storage/companyDocuments'), $fileName);

                Company_document::create([
                    'id_company' => $existingCompany->id,
                    'type' => $type, // hoặc 'pdf' tùy thuộc vào loại tài liệu
                    'file_path' => $fileName,
                ]);
            }
        }

        return redirect()->route('employer.listBranch')->with('success', 'Cập nhật thông tin công ty thành công!');
    }
    
    public function showFormAddBranch(Request $request) {
        $companyId = $request->get('id_company');
        return view('employer.formAddBranch', compact('companyId'));
    }

    public function addBranch(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|integer',
            'district' => 'required|integer',
            'desc' => 'nullable|string',
            // 'latitude' => 'nullable|numeric',
            // 'longitude' => 'nullable|numeric',
            'img.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Tạo chi nhánh mới
        $branch = new Company_branch();
        $branch->name = $request->input('name');
        $branch->id_company = $request->input('id_company');
        $branch->province = $request->input('province');
        $branch->district = $request->input('district');
        $branch->desc = $request->input('desc');
        // $branch->latitude = $request->input('latitude');
        // $branch->longitude = $request->input('longitude');
        $branch->save(); // Lúc này $branch->id đã có

        // Lưu ảnh vào bảng table_branch_imgs
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalName();
                $image->move(public_path('storage/branches'), $filename);

                $img = new Branch_img();
                $img->id_branch = $branch->id;
                $img->img = 'storage/branches/' . $filename;
                $img->save();
            }
        }

        return redirect()->route('employer.listBranch')->with('success', 'Thêm chi nhánh thành công!');
    }

    public function deleteBranch($id_branch) {
        $branchId = $id_branch;
        $branch = Company_branch::find($branchId);

        if (!$branch) {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }
        if($branch->id_user_manager != session('user.id')) {
            // Kiểm tra xem người dùng có phải là người quản lý chi nhánh không
            $company = Company::where('id', $branch->id_company)->first();
            if ($company && $company->id_user_main != session('user.id')) {
                return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền xóa chi nhánh này!');
            }
        }

        // Xóa tất cả ảnh liên quan đến chi nhánh
        $branchImages = Branch_img::where('id_branch', $branchId)->get();
        foreach ($branchImages as $image) {
            Storage::delete($image->img);
            $image->delete();
        }

        // Xóa chi nhánh
        $branch->delete();

        return redirect()->route('employer.listBranch')->with('success', 'Xóa chi nhánh thành công!');
    }

    public function updateBranch(Request $request) {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|integer',
            'district' => 'required|integer',
            'desc' => 'nullable|string',
            'imgDel' => 'nullable|array',         // Là mảng
            'imgDel.*' => 'integer',              // Các phần tử là số nguyên
            'img.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Cập nhật chi nhánh
        $branch = Company_branch::find($request->input('id_branch'));
        if (!$branch) {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }
        if($branch->id_user_manager != session('user.id')) {
            // Kiểm tra xem người dùng có phải là người quản lý chi nhánh không
            $company = Company::where('id', $branch->id_company)->first();
            if ($company && $company->id_user_main != session('user.id')) {
                return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền cập nhật chi nhánh này!');
            }
        }

        $branch->name = $request->input('name');
        $branch->province = $request->input('province');
        $branch->district = $request->input('district');
        $branch->desc = $request->input('desc');
        $branch->save();

        // Xử lý xóa ảnh nếu có
        if ($request->has('imgDel')) {
            $imgDel = $request->input('imgDel');
            foreach ($imgDel as $id) {
                $image = Branch_img::find($id);
                if ($image) {
                    // Storage::delete($image->img);
                    Storage::disk('public')->delete(str_replace('storage/', '', $image->img));
                    $image->delete();
                }
            }
        }

        // Lưu ảnh vào bảng table_branch_imgs
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalName();
                $image->move(public_path('storage/branches'), $filename);

                $img = new Branch_img();
                $img->id_branch = $branch->id;
                $img->img = 'storage/branches/' . $filename;
                $img->save();
            }
        }

        return redirect()->route('employer.listBranch')->with('success', 'Cập nhật chi nhánh thành công!');

    }

    public function listPost(Request $request) {
        if(!isset($request->id_branch) || $request->id_branch == '') {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }

        $jobCategories = Category::all();
        // dd($request->all());
        $yourBranch = Company_branch::with(['branchProvince'])->where('id', $request->id_branch)->first();
        if (!$yourBranch) {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }
        $yourCompany = Company::where('id', $yourBranch->id_company)->first();

        // Kiểm tra quyền truy cập vào chi nhánh
        if($yourBranch->id_user_manager != session('user.id')) {
            // User không phải là người quản lý chi nhánh, kiểm tra xem có phải là chủ công ty không
            if($yourCompany->id_user_main != session('user.id')) {
                return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền truy cập vào chi nhánh này!');
            }
        }

        if (!$yourBranch) {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }

        if ($yourBranch->id_user_manager != session('user.id')) {
            if (!$yourCompany) {
                return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền truy cập vào chi nhánh này!');
            } else {
                // Chi nhánh thuộc công ty mà user làm chủ
            }
        } else {
            // Chi nhánh do user quản lý trực tiếp
        }

        
        $employer = Employer::where('id_user', session('user.id'))->first();
        $posts = Post::where('id_branch', $request->id_branch)
                    ->where('deadline', '>=', date('Y-m-d')) // Lọc các bài đăng chưa hết hạn
                    ->get();

        $applications = []; 
        foreach($posts as $post) {
            $applications[$post->id] = Application::where('id_post', $post->id)->get();
        }

        
        foreach ($posts as $post) {
            // add work mode show
            $post->work_mode_show = $this->makeWorkMode($post->work_mode, $yourBranch->branchProvince->name);
            // add salary show
            $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
            // add gender show
            $post->gender_show = $this->makeGender($post->gender);
            // add experience show
            $post->experience_show = $this->makeExperience($post->experience);
            // add degree show
            $post->degree_show = $this->makeDegree($post->degree);
        }

        $page = 'listPost';
        return view('employer.listPost', compact('posts', 'employer', 'yourBranch', 'yourCompany', 'jobCategories', 'applications', 'page'));
    }

    public function listExpiredPosts(Request $request) {
        if(!isset($request->id_branch) || $request->id_branch == '') {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }

        $jobCategories = Category::all();
        // dd($request->all());
        $yourBranch = Company_branch::with(['branchProvince'])->where('id', $request->id_branch)->first();
        if (!$yourBranch) {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }
        $yourCompany = Company::where('id', $yourBranch->id_company)->first();

        // Kiểm tra quyền truy cập vào chi nhánh
        if($yourBranch->id_user_manager != session('user.id')) {
            // User không phải là người quản lý chi nhánh, kiểm tra xem có phải là chủ công ty không
            if($yourCompany->id_user_main != session('user.id')) {
                return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền truy cập vào chi nhánh này!');
            }
        }

        if (!$yourBranch) {
            return redirect()->route('employer.listBranch')->with('error', 'Chi nhánh không tồn tại!');
        }

        if ($yourBranch->id_user_manager != session('user.id')) {
            if (!$yourCompany) {
                return redirect()->route('employer.listBranch')->with('error', 'Bạn không có quyền truy cập vào chi nhánh này!');
            } else {
                // Chi nhánh thuộc công ty mà user làm chủ
            }
        } else {
            // Chi nhánh do user quản lý trực tiếp
        }

        
        $employer = Employer::where('id_user', session('user.id'))->first();
        $posts = Post::where('id_branch', $request->id_branch)
                    ->where('deadline', '<', date('Y-m-d')) // Lọc các bài đăng đã hết hạn
                    ->get();

        $applications = []; 
        foreach($posts as $post) {
            $applications[$post->id] = Application::where('id_post', $post->id)->get();
        }

        
        foreach ($posts as $post) {
            // add work mode show
            $post->work_mode_show = $this->makeWorkMode($post->work_mode, $yourBranch->branchProvince->name);
            // add salary show
            $post->salary_show = $this->makeSalary($post->salary_type, $post->salary);
            // add gender show
            $post->gender_show = $this->makeGender($post->gender);
            // add experience show
            $post->experience_show = $this->makeExperience($post->experience);
            // add degree show
            $post->degree_show = $this->makeDegree($post->degree);
        }

        $page = 'listExpiredPosts';
        return view('employer.listPost', compact('posts', 'employer', 'yourBranch', 'yourCompany', 'jobCategories', 'applications', 'page'));
    }

    public function updatePost(Request $request) {
        if($request->input('deadline') < date('Y-m-d')) {
            return redirect()->back()->with('error', 'Ngày hết hạn không được nhỏ hơn ngày hiện tại!');
        }

        $salary = '';
        if($request->input('salary_type') == 'negotiable') {
        } else if($request->input('salary_type') == 'range') {
            $salary = $request->input('salary_from') . '-' . $request->input('salary_to');
        } else if($request->input('salary_type') == 'upto') {
            $salary = $request->input('salary');
        } else if($request->input('salary_type') == 'fixed') {
            $salary = $request->input('salary');
        } else if($request->input('salary_type') == 'starting_from') {
            $salary = $request->input('salary');
        }
        // dd($request->all());

        $post = Post::find($request->input('id_post'));
        if (!$post) {
            return redirect()->route('employer.listPost', ['id_branch' => $request->input('id_branch')])->with('error', 'Bài đăng không tồn tại!');
        }
        // dd($post);
        // Kiểm tra quyền truy cập vào bài đăng
        $branch = Company_branch::where('id', $post->id_branch)->first();
        if (!$branch) {
            return redirect()->route('employer.listPost', ['id_branch' => $request->input('id_branch')])->with('error', 'Chi nhánh không tồn tại!');
        }
        if ($branch->id_user_manager != session('user.id')) {
            // User không phải là người quản lý chi nhánh, kiểm tra xem có phải là chủ công ty không
            $company = Company::where('id', $branch->id_company)->first();
            if ($company && $company->id_user_main != session('user.id')) {
                return redirect()->route('employer.listPost', ['id_branch' => $request->input('id_branch')])->with('error', 'Bạn không có quyền cập nhật bài đăng này!');
            }
        }
        // Kiểm tra đã đén ngày hết hạn chưa
        if ($post->deadline < date('Y-m-d')) {
            return redirect()->route('employer.listPost', ['id_branch' => $request->input('id_branch')])->with('error', 'Không thể cập nhật bài đăng đã hết hạn!');
        }
        // Cập nhật bài đăng
        $post->update([
            'title' => $request->input('title'),
            'employment_type' => $request->input('employment_type'),
            'work_mode' => $request->input('work_mode'),
            'salary_type' => $request->input('salary_type'),
            'salary' => $salary,
            'description' => $request->input('description'),
            'skills' => $request->input('skills'),
            'gender' => $request->input('gender'),
            'experience' => $request->input('experience'),
            'degree' => $request->input('degree'),
            'quantity' => $request->input('quantity'),
            'deadline' => $request->input('deadline'),
            'job_category' => $request->input('jobCategory'),
        ]);
        return redirect()->route('employer.listPost', ['id_branch' => $branch->id])->with('success', 'Cập nhật thông tin công ty thành công!');
    }

    public function addPost(Request $request) {
        // dd($request->input('description'));
        // $desc = "Mô tả công việc – Lập trình viên Fullstack";
        // check salary

        if($request->input('deadline') < date('Y-m-d')) {
            return redirect()->back()->with('error', 'Ngày hết hạn không được nhỏ hơn ngày hiện tại!');
        }

        $salary = '';
        if($request->input('salary_type') == 'negotiable') {
        } else if($request->input('salary_type') == 'range') {
            $salary = $request->input('salary_from') . '-' . $request->input('salary_to');
        } else if($request->input('salary_type') == 'upto') {
            $salary = $request->input('salary');
        } else if($request->input('salary_type') == 'fixed') {
            $salary = $request->input('salary');
        } else if($request->input('salary_type') == 'starting_from') {
            $salary = $request->input('salary');
        }
        // dd($request->all());
        Post::create([
            'title' => $request->input('title'),
            'id_branch' => $request->input('id_branch'),
            'employment_type' => $request->input('employment_type'),
            'work_mode' => $request->input('work_mode'),
            'salary_type' => $request->input('salary_type'),
            'salary' => $salary,
            'description' => $request->input('description'),
            'skills' => $request->input('skills'),
            'gender' => $request->input('gender'),
            'experience' => $request->input('experience'),
            'degree' => $request->input('degree'),
            'quantity' => $request->input('quantity'),
            'deadline' => $request->input('deadline'),
            'job_category' => $request->input('jobCategory'),
        ]);
        return redirect()->route('employer.listPost', ['id_branch' => $request->input('id_branch')])->with('success', 'Thêm bài đăng thành công!');
    }

    public function deletePost($id_post) {
        $post = Post::find($id_post);
        if (!$post) {
            return redirect()->back()->with('error', 'Bài đăng không tồn tại!');
        }

        // Kiểm tra quyền truy cập vào bài đăng
        $branch = Company_branch::where('id', $post->id_branch)->first();
        if (!$branch) {
            return redirect()->back()->with('error', 'Chi nhánh không tồn tại!');
        }
        if ($branch->id_user_manager != session('user.id')) {
            // User không phải là người quản lý chi nhánh, kiểm tra xem có phải là chủ công ty không
            $company = Company::where('id', $branch->id_company)->first();
            if ($company && $company->id_user_main != session('user.id')) {
                return redirect()->back()->with('error', 'Bạn không có quyền xóa bài đăng này!');
            }
        }

        // Xóa bài đăng
        $post->delete();

        return redirect()->route('employer.listPost', ['id_branch' => $branch->id])->with('success', 'Xóa bài đăng thành công!');
    }

    public function listApply($id_post) {
        $employer = Employer::where('id_user', session('user.id'))->first();
        $post = Post::with(['branch', 'branch.company', 'branch.branchProvince'])
            ->where('id', $id_post)
            ->first();

        // $applications = Application::with('cvNotReject', 'candidate')->where('id_post', $post->id)->get();
        $applications = Application::with('cvNotReject', 'candidate')->where('id_post', $post->id)->get();
        // $cvs = $applications->pluck('cvNotReject')->filter();
        // $cvs = collect($applications)->map(function ($app) {
        //     // if (!empty($app['cvNotReject'])) {
        //     //     // Gộp status từ application vào cvNotReject
        //     //     // $app['cvNotReject']['statusApply'] = $app['status'];
        //     //     return $app['cvNotReject'];
        //     // }
        //     return null;
        // })->filter(); // loại bỏ null nếu có
        // $generatedCvs = [];
        // foreach($cvs as $cv) {
        //     $cv->apply = Application::where('id_cv', $cv->id)->where('id_post', $id_post)->first();
        //     // dd($apply);

        //     if($cv->file_name != 'CV của hệ thống') continue;
        //     $generatedCv = GeneratedCv::where('cv_id', $cv->id)->first();
        //     $generatedCvs[$cv->id] = $generatedCv;
        // }
        // // dd($generatedCvs);
        // $candidates = $applications->pluck('candidate')->filter();
        // // dd($cvs);

        // return view('employer.listApply', compact('employer', 'post', 'cvs', 'candidates', 'generatedCvs'));
    }

    public function reject($id_post, $id_candidate) {
        $apply = Application::where('id_post', $id_post)
                                ->where('id_candidate', $id_candidate)
                                ->first();
        
        if(empty($apply)) {
            return redirect()->back();
        }

        $apply->status = 'reject';
        $apply->save();

        // tao thong bao
        $post = Post::with(['branch', 'branch.company'])
            ->where('id', $id_post)
            ->first();
        $this->makeAlert('reject', $post->branch->company->name, $id_candidate);


        return redirect()->back();
    }

    public function pass(Request $request) {
        $id_post = $request->id_post;
        $id_candidate = $request->id_candidate;
        $message = $request->message;

        $post = Post::where('id', $id_post)->first();
        $id_branch = $post->id_branch;

        Chat::create([
            'id_candidate' => $id_candidate,
            'id_branch' => $id_branch,
            'id_post' => $id_post,
            'message' => $message,
            'sender' => 'branch',
        ]);

        $apply = Application::where('id_post', $id_post)
                                ->where('id_candidate', $id_candidate)
                                ->first();
        
        if(empty($apply)) {
            return redirect()->back();
        }

        $apply->status = 'pass';
        $apply->save();

        // tao thong bao
        $post = Post::with(['branch', 'branch.company'])
            ->where('id', $id_post)
            ->first();
        $this->makeAlert('pass', $post->branch->company->name, $id_candidate, $id_branch);

        return redirect()->back();
    }

    public function searchEmployer(Request $request) {
        $name = $request->name;
        $id = $request->id;

        $user = Employer::where('name', $name)
                                ->where('id_user', $id)
                                ->first();

        return response()->json(['user' => $user]);
    }

    public function addBranchManager(Request $request) {
        $id_branch = $request->id_branch;
        $id_user = $request->id_user;
        $name = $request->name;

        if($id_user == session('user.id')) {
            // Người dùng không thể tự mình làm quản lý chi nhánh
            return redirect()->back()->with('error', 'Bạn không thể tự mình làm quản lý chi nhánh.');
        }

        // Kiểm tra xem người dùng có phải là nhà tuyển dụng không
        $checkIsEmployer = Employer::where('id_user', session('user.id'))->first();

        $branch = Company_branch::where('id', $id_branch)->first();
        // Cập nhật id_user_manager của chi nhánh
        if ($branch) {
            $checkIsOwnerCompany = Company::where('id_user_main', session('user.id'))
                                ->where('id', $branch->id_company)
                                ->first();
            if (!$checkIsEmployer && !$checkIsOwnerCompany) {
                // Người dùng không phải là nhà tuyển dụng hoặc không sở hữu công ty
                return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
            }

            $branch->id_user_manager = $id_user;
            $branch->save();
            return redirect()->back()->with('success', 'Cập nhật quản lý chi nhánh thành công!');
        } else {
            return redirect()->back()->with('error', 'Chi nhánh không tồn tại.');
        }
    }





    // -----------------------<< function to perform the problem >>-------------------------
    private function makeAlert($type, $title, $idUser, $id_branch = null) {
        if($type == 'pass') {
            $content = 'Xin chúc mừng bạn đã vượt qua vòng xét duyệt hồ sơ của chúng tôi, hãy liên hệ với chúng tôi để được phỏng vấn';
            Alert::create([
                'type' => $type,
                'title' => $title,
                'id_user' => $idUser,
                'content' => $content,
                'status' => 'unread',
                'id_branch' => $id_branch,
            ]);
        }
        if($type == 'reject') {
            $content = 'Rất tiếc! Hồ sơ của bạn không phù hợp với yêu cầu công việc của chúng tôi, hẹn gặp bạn vào một ngày không xa chúng ta có thể làm việc cùng nhau';
            Alert::create([
                'type' => $type,
                'title' => $title,
                'id_user' => $idUser,
                'content' => $content,
                'status' => 'unread',
            ]);
        }
    }
    private function makeWorkMode($workMode, $locationBranch) {
        if($workMode == 'onsite') {
            return $locationBranch;
        } else if($workMode == 'remote') {
            return 'Từ xa';
        } else if($workMode == 'hybrid') {
            return 'Kết hợp';
        }
    }

    private function makeSalary($salaryType, $salary) {
        if($salaryType == 'negotiable') {
            return 'Thương lượng';
        } else if($salaryType == 'range') {
            list($from, $to) = explode('-', $salary);
            $from = number_format($from, 0, ',', '.') . ' VNĐ';
            $to = number_format($to, 0, ',', '.') . ' VNĐ';
            $salary = 'Từ: ' . $from . ' - Đến: ' . $to;
            return $salary;
        } else if($salaryType == 'upto') {
            return 'Lên đến: ' . $salary;
        } else if($salaryType == 'fixed') {
            return $salary;
        } else if($salaryType == 'starting_from') {
            return 'Từ: ' . $salary;
        }
    }

    private function makeGender($gender) {
        if($gender == 'any') {
            return '';
        }
        else if($gender == 'male') {
            return 'Nam';
        }
        else if($gender == 'female') {
            return 'Nữ';
        }
        else if($gender == 'other') {
            return 'Khác';
        }
    }

    private function makeExperience($gender) {
        if($gender == '') {
            return '';
        }
        else if($gender == 'no_experience') {
            return 'Chưa có kinh nghiệm';
        }
        else if($gender == 'less_than_1_year') {
            return 'Dưới 1 năm';
        }
        else if($gender == '1_year') {
            return '1 năm';
        }
        else if($gender == '2_years') {
            return '2 năm';
        }
        else if($gender == '3_years') {
            return '3 năm';
        }
        else if($gender == 'over_3_years') {
            return 'trên 3 năm';
        }
        else if($gender == 'over_5_years') {
            return 'trên 5 năm';
        }
    }

    private function makeDegree($gender) {
        if($gender == 'none') {
            return 'Không yêu cầu';
        }
        else if($gender == 'high_school') {
            return 'Tốt nghiệp THPT';
        }
        else if($gender == 'associate') {
            return 'Cao đẳng';
        }
        else if($gender == 'bachelor') {
            return 'Đại học';
        }
        else if($gender == 'master') {
            return 'Thạc sĩ';
        }
        else if($gender == 'doctor') {
            return 'Tiến sĩ';
        }
    }
}
