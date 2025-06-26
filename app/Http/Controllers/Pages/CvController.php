<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhpOffice\PhpWord\IOFactory; // dùng thư viện này để đọc Word
use Spatie\PdfToImage\Pdf; // chuyển pdf sang ảnh
use Illuminate\Support\Str;

use App\Models\Account;
use App\Models\Category;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Cv;
use App\Models\GeneratedCv;


class CvController extends Controller
{
    public function listCv() {
        $candidate = Candidate::where('id_user', session('user.id'))->first();
        $cvs = Cv::where('id_user', session('user.id'))->get();

        $generatedCvs = [];
        foreach($cvs as $cv) {
            if($cv->file_name != 'CV của hệ thống') continue;
            $generatedCv = GeneratedCv::where('cv_id', $cv->id)->first();
            $generatedCvs[$cv->id] = $generatedCv;
        }
        // dd($generatedCvs);
        
        // return view('cv.listCv', compact('candidate', 'cvs', 'generatedCvs'));
        return view('cv.listCv', compact('candidate', 'cvs', 'generatedCvs'));
    }

    public function showMakeCvForm() {
        $candidate = Candidate::where('id_user', session('user.id'))->first();

        return view('cv.makeCv', compact('candidate'));
    }

    public function demoCv(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nameCV' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'career_goal' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'projects' => 'nullable|string',
            'skills' => 'nullable|string',
            'certificates' => 'nullable|string',
            'template' => 'required|string',
        ]);

        $data = $validated;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('avatars', 'public');
            $data['avatar_path'] = 'storage/' . $avatarPath;
        } else {
            $data['avatar_path'] = null;
        }

        $template = $data['template'];

        if ($template === 'classic') {
            return view('cv_templates.demoClassic', ['cv' => (object) $data]);
        } elseif ($template === 'modern') {
            return view('cv_templates.demoModern', ['cv' => (object) $data]);
        } else {
            return view('cv_templates.demoClassic', ['cv' => (object) $data]);
        }
        // dd('im here');
    }

    public function makeCv(Request $request) {
        $validated = $request->validate([
            'nameCV' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:255',
            'career_goal' => 'nullable|string',
            'template' => 'required|string|in:classic,modern',
            'education' => 'nullable|string',     // Chuẩn bị dạng JSON string
            'experience' => 'nullable|string',
            'projects' => 'nullable|string',
            'skills' => 'nullable|string',
            'certificates' => 'nullable|string',
        ]);

        // dd($validated);

        // Tạo bản ghi mới
        $data = $validated;
        $data['user_id'] = session('user.id');
        $data['avatar_path'] = $validated['avatar'] ?? null;
        unset($data['avatar']);

        $cv = Cv::create([
            'id_user' => session('user.id'),
            'name' => $data['nameCV'],
            'status' => '2',
            'file_name' => 'CV của hệ thống',
        ]);
        $data['cv_id'] = $cv->id;
        // dd($data);
        GeneratedCv::create($data);

        return redirect()->route('listCv')->with('success', 'Lưu CV thành công!');
    }


    public function uploadCv(Request $request) {
        if (!session()->has('user.id')) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để upload CV.');
        }

        $request->validate([
            'cv' => 'required|mimes:pdf,doc,docx|max:2048',
            'name' => 'required',
        ]);

        // Check xem đã quá giới hạn lưu trữ cv chưa
        $cvs = Cv::where('id_user', session('user.id'))->get();
        $countCvs = $cvs->count();
        $candidate = Candidate::where('id_user', session('user.id'))->first();
        if($candidate->cv_limit == $countCvs) {
            return back()->with('error', 'Bạn cần mua thêm giới hạn lưu trữ');
        }
    
        $file = $request->file('cv');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString(); // tên file random
    
        // ✅ Tạo thư mục nếu chưa có
        \Storage::makeDirectory('public/cv');
        \Storage::makeDirectory('public/cv_images');
    
        $pdfPath = storage_path("app/public/cv/{$filename}.pdf");
        if (in_array($extension, ['doc', 'docx'])) {
            // Chuyển Word -> PDF
            $phpWord = IOFactory::load($file->getPathname());

            // Cấu hình trình chuyển PDF bằng TCPDF
            \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_TCPDF);
            \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/tecnickcom/tcpdf'));
            // \PhpOffice\PhpWord\Settings::setPdfRendererExtension('.php');

            $pdfWriter = new \PhpOffice\PhpWord\Writer\PDF($phpWord);
            $pdfWriter->save($pdfPath);
        } else {
            // Lưu file PDF trực tiếp
            $file->move(storage_path('app/public/cv'), "{$filename}.pdf");
        }

        // Thêm cv vào database
        $id_user = session('user.id');
        $name = $request->name;
        $status = CV::userHasCV(session('user.id')) ? 2 : 1;
        Cv::create([
            'id_user' => $id_user,
            'file_name' => $filename . '.pdf',
            'name' => $name,
            'status' => $status
        ]);
    
        return back()->with('success', 'CV đã được tải lên!');
    }

    public function deleteCv($id) {
        $cv = Cv::where('id', $id)->first();

        // Kiểm tra nếu không có CV
        if (!$cv) {
            return back()->with('info', 'CV không tồn tại!');
        }

        if(session('user.id') != $cv->id_user) return back()->with('info', 'Xóa CV thất bại! Bạn không có quyền xóa CV này.');

        $cv->delete();
        return back()->with('success', 'Xóa thành công!');
    }

    public function changeMainCv($id) {
        $cv = Cv::where('id', $id)->first();

        // Kiểm tra nếu không có CV
        if (!$cv) {
            return back()->with('info', 'CV không tồn tại!');
        }

        if(session('user.id') != $cv->id_user) return back()->with('info', 'Thay đổi CV thất bại! Bạn không có quyền chỉnh sửa CV này.');

        Cv::where('id_user', $cv->id_user)->update(['status' => 2]);
        $cv->status = 1;
        $cv->save();
        return back()->with('success', 'Thay đổi thành công!');
    }
}
