<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Candidate;
use App\Models\Post;
use App\Models\Alert;
use App\Models\Company_branch;
use App\Models\Skill;

class JobSuggestionController extends Controller
{
    public function suggestJobsForm()
    {
        $alerts = [];
        if(session()->has('user')) {
            $alerts = Alert::where('id_user', session('user.id'))->get();
        }

        // lấy tất cả kỹ năng của ứng viên
        $skills = Skill::where('id_user', session('user.id'))->get();

        // kiểm tra đã đăng nhập chưa? nếu đã thì đi đến trang tuyển dụng hay ứng viên
        if(session()->has('user')) {
            if(session('user.role') == 1) { //la nha tuyen dung
                return redirect()->route('home');
            }
            else if(session('user.role') == 2) { //la ung vien
                $candidate = Candidate::where('id_user', session('user.id'))->first();
                return view('pages.suggestJobs', compact('candidate', 'alerts', 'skills'));
            }
        }
        return redirect()->route('home');
    }

    public function suggestFromInput(Request $request)
    {
        // $skillsRequest = explode(',', $request->skill);
        $skillsRequest = $request->skill;
        $candidate = Candidate::where('id_user', session('user.id'))->first();
        $experience = (int)$request->exp;
        $province = $candidate->province;

        $jobs = Post::with('branch')
            ->where('deadline', '>=', now())
            ->where('status', '!=', 'lock')
            ->where(function ($query) use ($province) {
                $query->where('work_mode', 'remote')
                    ->orWhereHas('branch', function ($q) use ($province) {
                        $q->where('province', $province);
                    });
            })
            ->get();
        $results = [];

        Skill::where('id_user', session('user.id'))->delete(); // xóa tất cả kỹ năng cũ của ứng viên

        $skillsRaw = json_decode($skillsRequest, true); // decode thành mảng PHP
        $skills = collect($skillsRaw)->pluck('value')->toArray(); // chỉ lấy các 'value'

        // Lưu kỹ năng vào bảng skills
        foreach ($skills as $skill) {
            Skill::create([
                'id_user' => session('user.id'),
                'skill' => $skill,
            ]);
        };

        foreach ($jobs as $job) {
            // Chuyển chuỗi JSON thành mảng
            $jobSkillsRaw = json_decode($job->skills, true); // decode thành mảng PHP
            $jobSkills = collect($jobSkillsRaw)->pluck('value')->toArray(); // chỉ lấy các 'value'

            // Tính điểm kỹ năng
            $score = $this->calculateSkillScore(
                array_map('mb_strtolower', array_map('trim', $skills)),
                array_map('mb_strtolower', array_map('trim', $jobSkills))
            );

            if ($score >= 0.3) {
                $results[] = [
                    'id' => $job->id,
                    'score' => round($score, 3),
                ];
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return response()->json([
            'jobs' => $results,
        ]);
    }

    function calculateSkillScore(array $candidateSkills, array $jobSkills): float
    {
        $matchCount = count(array_intersect($candidateSkills, $jobSkills));
        $totalRequired = count($jobSkills);

        if ($totalRequired === 0) {
            return 0;
        }

        return round($matchCount / $totalRequired, 3);
    }

    public function getJobDetail($id, $score)
    {
        $post = Post::with(['branch', 'branch.company', 'branch.branchProvince', 'category_show'])
            ->where('id', $id)
            ->first();
        
        if($post->status == 'lock') return view('notFound.notfound');

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

        $address = $post->branch->branchDistrict->name.' - '.$post->branch->branchProvince->name;

        // Render view thành HTML trả về
        $html = view('partials.job_detail', compact('post', 'address', 'categoryName', 'score'))->render();

        return response()->json(['html' => $html]);
    }
















    // function to perform the problem
    private function makeWorkMode($workMode, $locationBranch) {
        $map = [
            'onsite' => $locationBranch,
            'remote' => 'Từ xa',
            'hybrid' => 'Kết hợp',
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