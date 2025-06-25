@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>
    @vite(['resources/css/employer/post.css'])
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>

    <!-- Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <style>
        .tagify {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            font-size: 1.4rem;
        }
    </style>
@endsection

@section('content')
    <section class="post">
        {{-- {{ dd($posts) }} --}}
        <div class="grid-container">
            <div class="post__container">
                <div class="post__top">
                    <img class="post__logo" src="{{ asset('storage/uploads/1746673572_vubobien.png') }}" alt="">
                    <h1 class="post__nameCompany">{{ $yourCompany->name }}</h1>
                    <p>Chi nhánh</p>
                    <h2 class="post__nameBranch">{{ $yourBranch->name }}</h2>
                </div>
                <h2 style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px; font-size: 1.6rem; color: var(--color-white);">
                    <a href="{{ route('employer.listPost', ['id_branch' => $yourBranch->id]) }}" style="padding: 8px 16px; background: var(--color-base); margin: 0 20px; border-radius: 12px;">Chưa hết hạn</a>
                    <a href="{{ route('employer.listExpiredPosts', ['id_branch' => $yourBranch->id]) }}" style="padding: 8px 16px; background: var(--color-base); margin: 0 20px; border-radius: 12px;">Đã hết hạn</a>
                </h2>
                <div class="listJob__list">
                    <h3 class="listJob__title">
                        <span>
                                @if ($page == 'listPost')
                                Danh sách tin tuyển dụng chưa hết hạn
                            @else
                                Danh sách tin tuyển dụng đã hết hạn    
                            @endif
                        </span>
                        <p class="listJob__add">Thêm tin tuyển dụng</p>
                    </h3>
                    @foreach ($posts as $post)
                        <div class="listJob__item">
                            <div class="{{ $post->status == 'lock' ? 'complaint' : 'complaintNone' }}">
                                <div>
                                    <p style="margin-bottom: 8px;">Bài viết của bạn đã bị khóa</p>
                                    <p style="margin-bottom: 8px;"><a href="{{ route('complaint.form', ['id_post' => $post->id]) }}">Khiếu nại</a></p>
                                    <p><a href="{{ route('post.show', ['id_post' => $post->id]) }}">Xem bài viết</a></p>
                                </div>
                            </div>
                            <div class="listJob__item--body">
                                <a href="{{ route('post.show', ['id_post' => $post->id]) }}">
                                    <div class="listJob__item--left">
                                        <img src="{{ asset('storage/uploads/'.$yourCompany->logo) }}" alt="" class="listJob__item--logo">
                                    </div>
                                </a>
                                <div class="listJob__item--right">
                                    <a href="{{ route('post.show', ['id_post' => $post->id]) }}" class="listJob__item--title">{{ $post->title }}</a>
                                    <a href="#" class="listJob__item--company">{{ $yourCompany->name }}</a>
                                </div>
                            </div>
                            <div class="listJob__item--footer">
                                <div class="listJob__item--footerItem">
                                    <span class="listJob__item--info">{{ $post->salary_show }}</span>
                                    <span class="listJob__item--info">{{ $post->work_mode_show }}</span>
                                </div>
                            </div>
                            <div class="listJob__item--desc" id="bigBlock">
                                <h3 class="listJob__item--descTitle">{{ $post->title }}</h3>
                                <p class="listJob__item--descDeadline">Hạn đến: {{ \Carbon\Carbon::parse($post->deadline)->format('d/m/Y') }} - Số lượng: {{ $post->quantity }}</p>
                                <div class="listJob__item--descItem">
                                    <span>{{ $post->salary_show }}</span>
                                    <span>{{ $post->work_mode_show }}</span>
                                    @if ($post->gender_show != '')
                                        <span>Giới tính: {{ $post->gender_show }}</span>
                                    @endif
                                    <span>{{ $post->experience_show }}</span>
                                    <span>{{ $post->degree_show }}</span>
                                </div>
                                <div class="listJob__item--descDesc">{!! $post->description !!}</div>
                            </div>
                            <div class="listJob__item--btn">
                                <a href="{{ route('listApply', ['id_post' => $post->id]) }}" class="listJob__item--btnItemFull">Có {{ count($applications[$post->id]) }} ứng viên</a>
                                <a id_post="{{ $post->id }}" class="listJob__item--btnItem listJob__item--updateOpen">Sửa</a>
                                <a href="{{ route('employer.deletePost', ['id_post' => $post->id]) }}" class="listJob__item--btnItem">Xóa</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- add post --}}
    <section class="formAddPost">
        <div class="formAddPost__container">
            <div class="formAddPost__top">
                <i class="fa-solid fa-circle-xmark formAddPost__close"></i>
            </div>
            <div class="formAddPost__body">
                <h1 class="formAddPost__title">Thêm tin tuyển dụng</h1>
                <form action="{{ route('employer.addPost') }}" method="POST" enctype="multipart/form-data" id="formAddPost">
                    @csrf
                    {{-- id branch --}}
                    <input type="hidden" name="id_branch" value="{{ $yourBranch->id }}">
                    {{-- title --}}
                    <div class="formAddPost__item">
                        <label for="">Tiêu đề công việc</label>
                        <input type="text" name="title" id="" placeholder="Nhập tên công việc" required>
                    </div>
                    {{-- employment type --}}
                    <div class="formAddPost__item">
                        <label for="employment_type">Loại hình công việc</label>
                        <select name="employment_type" id="employment_type">
                            <option value="fulltime">Toàn thời gian</option>
                            <option value="parttime">Bán thời gian</option>
                            <option value="internship">Thực tập</option>
                            <option value="freelance">Tự do</option>
                            <option value="contract">Hợp đồng ngắn hạn</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    {{-- work mode --}}
                    <div class="formAddPost__item">
                        <label for="work_mode">Địa điểm làm việc</label>
                        <select name="work_mode" id="work_mode">
                            <option value="onsite">Làm tại chi nhánh</option>
                            <option value="remote">Làm từ xa</option>
                            <option value="hybrid">Kết hợp</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    {{-- salary --}}
                    <div class="formAddPost__item">
                        <label for="salary_type">Tùy chọn lương</label>
                        <select class="salary_type" name="salary_type" id="salary_type">
                            <option value="negotiable">Thỏa thuận</option>
                            <option value="range">Từ x.x đ đến y.y đ</option>
                            <option value="upto">Lên đến x.x đ</option>
                            <option value="fixed">Mức cố định x.x đ</option>
                            <option value="starting_from">Từ x.x đ trở lên</option>
                        </select>
                        <div class="salary">
                        </div>
                        {{-- <div class="salary_type_range">
                            <input type="number" name="salary_from" id="" placeholder="Nhập mức lương" required>
                            <input type="number" name="salary_to" id="" placeholder="Nhập mức lương" required>
                        </div>
                        <div class="salary_type_upto">
                            <input type="number" name="salary" id="" placeholder="Nhập mức lương" required>
                        </div> --}}
                    </div>       
                    {{-- desc --}}
                    <div class="formAddPost__item">
                        <label for="">Mô tả công việc</label>
                        <textarea name="description" id="ckeditorAddPost" cols="30" rows="10" placeholder="Nhập yêu cầu công việc" required></textarea>
                    </div>
                    {{-- skills --}}
                    <div class="formAddPost__item">
                        <label for="skills">Kỹ năng</label>
                        <input name="skills" placeholder="Nhập kỹ năng..." id="skill-input">
                    </div>
                    {{-- gender --}}
                    <div class="formAddPost__item">
                        <label for="gender">Giới tính</label>
                        <select name="gender" id="">
                            <option value="any">Tất cả</option>
                            <option value="male">Nam</option>
                            <option value="Female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    {{-- experience --}}
                    <div class="formAddPost__item">
                        <label for="experience">Kinh nghiệm</label>
                        <select name="experience" id="experience">
                            <option value="">Không yêu cầu</option>
                            <option value="no_experience">Chưa có kinh nghiệm</option>
                            <option value="less_than_1_year">Dưới 1 năm</option>
                            <option value="1_year">1 năm</option>
                            <option value="2_years">2 năm</option>
                            <option value="3_years">3 năm</option>
                            <option value="over_3_years">Trên 3 năm</option>
                            <option value="over_5_years">Trên 5 năm</option>
                        </select>                          
                    </div>
                    {{-- degree --}}
                    <div class="formAddPost__item">
                        <label for="degree">Bằng cấp</label>
                        <select name="degree" id="degree">
                            <option value="none">Không yêu cầu</option>
                            <option value="high_school">Tốt nghiệp THPT</option>
                            <option value="associate">Cao đẳng</option>
                            <option value="bachelor">Đại học</option>
                            <option value="master">Thạc sĩ</option>
                            <option value="doctor">Tiến sĩ</option>
                        </select>                                                   
                    </div>
                    {{-- quantity --}}
                    <div class="formAddPost__item">
                        <label for="quantity">Số lượng</label>
                        <input type="number" name="quantity" id="" placeholder="Không giới hạn">                                                
                    </div>
                    {{-- job category --}}
                    <div class="formAddPost__item">
                        <label for="jobCategory">Ngành nghề</label>
                        <select name="jobCategory" id="jobCategory">
                            <option value="9999">Khác</option>
                            @foreach ($jobCategories as $jobCategory)
                                <option value="{{ $jobCategory->id }}">{{ $jobCategory->name }}</option>
                            @endforeach
                        </select>                                                   
                    </div>
                    {{-- deadline --}}
                    <div class="formAddPost__item">
                        <label for="jobCategory">Thời hạn</label>
                        <input type="date" name="deadline" id="" min="{{ date('Y-m-d') }}">                                  
                    </div>
                    <button type="submit" class="btn btn--primary">Đăng</button>
                </form>
            </div>
        </div>
    </section>

    {{-- update post --}}
    <section class="formUpdatePost">
        <div class="formUpdatePost__container">
            <div class="formUpdatePost__top">
                <i class="fa-solid fa-circle-xmark formUpdatePost__close"></i>
            </div>
            <div class="formUpdatePost__body">
                <h1 class="formUpdatePost__title">Thêm tin tuyển dụng</h1>
                <form action="{{ route('employer.updatePost') }}" method="POST" enctype="multipart/form-data" id="formUpdatePost">
                    @csrf
                    {{-- id post --}}
                    <input class="id_post" type="hidden" name="id_post" value="" required>
                    {{-- title --}}
                    <div class="formUpdatePost__item">
                        <label for="">Tiêu đề công việc</label>
                        <input type="text" name="title" id="" placeholder="Nhập tên công việc" required>
                    </div>
                    {{-- employment type --}}
                    <div class="formUpdatePost__item">
                        <label for="employment_type">Loại hình công việc</label>
                        <select name="employment_type" id="employment_type">
                            <option value="fulltime">Toàn thời gian</option>
                            <option value="parttime">Bán thời gian</option>
                            <option value="internship">Thực tập</option>
                            <option value="freelance">Tự do</option>
                            <option value="contract">Hợp đồng ngắn hạn</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    {{-- work mode --}}
                    <div class="formUpdatePost__item">
                        <label for="work_mode">Địa điểm làm việc</label>
                        <select name="work_mode" id="work_mode">
                            <option value="onsite">Làm tại chi nhánh</option>
                            <option value="remote">Làm từ xa</option>
                            <option value="hybrid">Kết hợp</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    {{-- salary --}}
                    <div class="formUpdatePost__item">
                        <label for="salary_type">Tùy chọn lương</label>
                        <select class="salary_type-update" name="salary_type" id="salary_type-update">
                            <option value="negotiable">Thỏa thuận</option>
                            <option value="range">Từ x.x đ đến y.y đ</option>
                            <option value="upto">Lên đến x.x đ</option>
                            <option value="fixed">Mức cố định x.x đ</option>
                            <option value="starting_from">Từ x.x đ trở lên</option>
                        </select>
                        <div class="salary-update">
                        </div>
                        {{-- <div class="salary_type_range">
                            <input type="number" name="salary_from" id="" placeholder="Nhập mức lương" required>
                            <input type="number" name="salary_to" id="" placeholder="Nhập mức lương" required>
                        </div>
                        <div class="salary_type_upto">
                            <input type="number" name="salary" id="" placeholder="Nhập mức lương" required>
                        </div> --}}
                    </div>       
                    {{-- desc --}}
                    <div class="formUpdatePost__item">
                        <label for="">Mô tả công việc</label>
                        <textarea name="description" id="ckeditorUpdatePost" cols="30" rows="10" placeholder="Nhập yêu cầu công việc" required></textarea>
                    </div>
                    {{-- skills --}}
                    <div class="formUpdatePost__item">
                        <label for="skills">Kỹ năng</label>
                        <input name="skills" placeholder="Nhập kỹ năng..." id="skill-input-update">
                    </div>
                    {{-- gender --}}
                    <div class="formUpdatePost__item">
                        <label for="gender">Giới tính</label>
                        <select name="gender" id="">
                            <option value="any">Tất cả</option>
                            <option value="male">Nam</option>
                            <option value="Female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    {{-- experience --}}
                    <div class="formUpdatePost__item">
                        <label for="experience">Kinh nghiệm</label>
                        <select name="experience" id="experience">
                            <option value="">Không yêu cầu</option>
                            <option value="no_experience">Chưa có kinh nghiệm</option>
                            <option value="less_than_1_year">Dưới 1 năm</option>
                            <option value="1_year">1 năm</option>
                            <option value="2_years">2 năm</option>
                            <option value="3_years">3 năm</option>
                            <option value="over_3_years">Trên 3 năm</option>
                            <option value="over_5_years">Trên 5 năm</option>
                        </select>                          
                    </div>
                    {{-- degree --}}
                    <div class="formUpdatePost__item">
                        <label for="degree">Bằng cấp</label>
                        <select name="degree" id="degree">
                            <option value="none">Không yêu cầu</option>
                            <option value="high_school">Tốt nghiệp THPT</option>
                            <option value="associate">Cao đẳng</option>
                            <option value="bachelor">Đại học</option>
                            <option value="master">Thạc sĩ</option>
                            <option value="doctor">Tiến sĩ</option>
                        </select>                                                   
                    </div>
                    {{-- quantity --}}
                    <div class="formUpdatePost__item">
                        <label for="quantity">Số lượng</label>
                        <input type="number" name="quantity" id="" placeholder="Không giới hạn">                                                
                    </div>
                    {{-- job category --}}
                    <div class="formUpdatePost__item">
                        <label for="jobCategory">Ngành nghề</label>
                        <select name="jobCategory" id="jobCategory">
                            <option value="9999">Khác</option>
                            @foreach ($jobCategories as $jobCategory)
                                <option value="{{ $jobCategory->id }}">{{ $jobCategory->name }}</option>
                            @endforeach
                        </select>                                                   
                    </div>
                    {{-- deadline --}}
                    <div class="formUpdatePost__item">
                        <label for="jobCategory">Thời hạn</label>
                        <input type="date" name="deadline" id="" min="{{ date('Y-m-d') }}" required>                                  
                    </div>
                    <button type="submit" class="btn btn--primary">Đăng</button>
                </form>
            </div>
        </div>
    </section>

    {{-- code thong bao su kien --}}
    @if (session('error'))
    <script>
        window._alertErrorMsg = @json(session('error'));
    </script>
    @endif
    @if(session('info'))
        <script>
            window._alertInfoMsg = @json(session('info'));
        </script>
    @endif
    @if(session('success'))
        <script>
            window._alertSuccessMsg = @json(session('success'));
        </script>
    @endif

    @if (session('debug'))
        <script>console.log("Có vào được WITH INFO");</script>
    @endif
@endsection

@section('appendage')
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
      intent="WELCOME"
      chat-title="Chưa có data câu hỏi mô!"
      agent-id="020b6d32-f258-4915-93aa-7290ea1ae3c6"
      language-code="vi"
    ></df-messenger>
@endsection

@section('js')
    @vite(['resources/js/employer/post.js'])

    {{-- js ckEditor --}}
    <script>
        CKEDITOR.replace('ckeditorAddPost');
        CKEDITOR.replace('ckeditorUpdatePost');
    </script>

    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script>
        const input = document.querySelector('#skill-input');
        const tagify = new Tagify(input, {
          whitelist: ["HTML", "CSS", "JavaScript", "PHP", "Laravel", "React"],
          dropdown: {
            enabled: 1, // hiện dropdown khi gõ 1 ký tự
            maxItems: 10,
            classname: "skills-suggestions",
            highlightFirst: true
          }
        });

        const inputUpdate = document.querySelector('#skill-input-update');
        // Tạo Tagify cho input cập nhật
        const tagifyUpdate = new Tagify(inputUpdate, {
          whitelist: ["HTML", "CSS", "JavaScript", "PHP", "Laravel", "React"],
          dropdown: {
            enabled: 1, // hiện dropdown khi gõ 1 ký tự
            maxItems: 10,
            classname: "skills-suggestions",
            highlightFirst: true
          }
        });
    </script>

    {{-- show desc post --}}
    <script>
        document.querySelectorAll('.listJob__item').forEach(item => {
            const title = item.querySelector('.listJob__item--title');
            const desc = item.querySelector('.listJob__item--desc');
            let hoverTimer;

            title.addEventListener('mouseover', () => {
                hoverTimer = setTimeout(() => {
                    desc.style.display = 'block';
                }, 1000); // Delay 1 giây
            });

            desc.addEventListener('mouseleave', () => {
                clearTimeout(hoverTimer); // Hủy bỏ nếu chưa đủ 1 giây
                desc.style.display = 'none';
                // console.log(desc);
            });
        });
    </script>

{{-- script update post --}}
<script>
    const formAddPostOpen = document.querySelectorAll('.listJob__item--updateOpen');
    const formUpdatePost = document.querySelector('.formUpdatePost');
    const formUpdatePostClose = document.querySelector('.formUpdatePost__close');
    const salaryUpdateInput = document.querySelector('.salary-update');
    const idBranchUpdateInput = document.querySelector('.id_post');
    const posts = @json($posts);

    formAddPostOpen.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            formUpdatePost.style.display = 'flex';
            const idPost = e.target.getAttribute('id_post');
            idBranchUpdateInput.value = idPost;
            // document.querySelector('#formUpdatePost').setAttribute('action', `/employer/updatePost/${idPost}`);
            
            post = posts.find(post => post.id == idPost);
            
            // Điền dữ liệu vào form
            document.querySelector('#formUpdatePost input[name="title"]').value = post.title;
            document.querySelector('#formUpdatePost input[name="quantity"]').value = post.quantity;
            document.querySelector('#formUpdatePost input[name="deadline"]').value = post.deadline;
            document.querySelector('#formUpdatePost select[name="employment_type"]').value = post.employment_type;
            document.querySelector('#formUpdatePost select[name="work_mode"]').value = post.work_mode;
            document.querySelector('#formUpdatePost select[name="salary_type"]').value = post.salary_type;
            document.querySelector('#formUpdatePost select[name="gender"]').value = post.gender;
            document.querySelector('#formUpdatePost select[name="experience"]').value = post.experience;
            document.querySelector('#formUpdatePost select[name="degree"]').value = post.degree;
            document.querySelector('#formUpdatePost select[name="jobCategory"]').value = post.job_category;

            const skillsArr = JSON.parse(post.skills);
            tagifyUpdate.addTags(skillsArr.map(skill => skill.value));
            // const skills = skillsArr.map(item => item.value).join(",");
            // console.log(skills);
            // document.querySelector('#formUpdatePost select[name="skill"]').value = skills;
    
            // Hiển thị CKEditor với nội dung đã điền
            CKEDITOR.instances['ckeditorUpdatePost'].setData(post.description);

            showOrHiddenSalary(post.salary_type, post.salary);
        });
    })

    formUpdatePostClose.addEventListener('click', () => {
        formUpdatePost.style.display = 'none';
    });

    document.getElementById('salary_type-update').addEventListener('change', function () {
        showOrHiddenSalary(this.value);
    });

    function showOrHiddenSalary(type, salary = null) {
        if (type === 'negotiable') {
            salaryUpdateInput.innerHTML = '';
        }
        else if (type === 'range') {
            if(!salary) {
                salary = '0 - 0';
            }
            const [salary1, salary2] = salary.split("-");
            salaryUpdateInput.innerHTML = `<div class="salary_type_range">
                                                <input type="number" name="salary_from" id="" placeholder="Nhập mức lương" required value="${salary1 ? salary1.trim() : ''}">
                                                <input type="number" name="salary_to" id="" placeholder="Nhập mức lương" required value="${salary2 ? salary2.trim() : ''}">
                                            </div>`
        }
        else if (type === 'upto' || type === 'fixed' || type === 'starting_from') {
            salaryUpdateInput.innerHTML = `<div class="salary_type_upto">
                                                <input type="number" name="salary" id="" placeholder="Nhập mức lương" required value="${salary ? salary.trim() : ''}">
                                            </div>`
        }
        else {
            salaryUpdateInput.innerHTML = '';
        }
    }
</script>

@endsection
