@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/suggestJobs.css'])

    <!-- Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <style>
        .tagify {
            max-width: 400px;
            width: 100%;
            padding: 12px 20px;
            margin-bottom: 12px;
            border: 1px solid var(--color-base);
            outline: none;
            font-size: 1.4rem;
            border-radius: 12px;
        }
    </style>
@endsection

@section('content')
    <div class="suggestJobs">
        <div class="grid-container">
            <div class="suggestJobs__container">
                <h1 style="margin-bottom: 12px; color: var(--color-base); font-size: 2rem;">Gợi ý công việc theo kỹ năng của bạn</h1>
                <input type="text" name="skill" id="skill">
                <button id="btnSuggest">Gợi ý</button>
            </div>
            <div class="value">
                <div class="value__container">
                    <button id="btnNext">Tiếp</button>
                    <i class="fa-solid fa-circle-xmark value__exit"></i>
                    <h3 class="value__notFound" style="display: none;">Không tìm thấy công việc phù hợp</h3>
                    <div class="value__box">
                        <div class="value__content">
                            {{-- <div class="loader"></div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('appendage')
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>

    <!-- chat bot -->
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
      intent="WELCOME"
      chat-title="Chưa có data câu hỏi mô!"
      agent-id="020b6d32-f258-4915-93aa-7290ea1ae3c6"
      language-code="vi"
    ></df-messenger>
@endsection
@section('js')
    <!-- Load JS -->
    @vite(['resources/js/pages/suggestJobs.js'])

    {{-- make skills --}}
    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.querySelector('#skill');

            const tagify = new Tagify(input, {
                dropdown: {
                    enabled: 1,
                    maxItems: 10,
                    classname: "skills-suggestions",
                    highlightFirst: true
                }
            });

            const skillsArr = @json($skills); // [{ id:..., skill: "php", ... }]
            const formattedSkills = skillsArr.map(skill => ({ value: skill.skill }));

            tagify.addTags(formattedSkills); // Giờ thì hoạt động đúng
        });
    </script>


    {{-- xu ly goi y cong viec --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let searchedJobs = [];
        let jobList = [];
        let jobIndex = 0;

        function renderJob(job) {
            // Gọi đến backend để lấy nội dung chi tiết công việc đầu tiên
            $.ajax({
                url: '/api/job-detail/' + job.id + '/' + job.score, // route bạn phải tạo
                method: 'GET',
                success: function (response) {
                    // Giả sử response trả về { html: 'nội dung HTML' }
                    const html = response.html;
                    $('.value__content').html(html);
                },
                error: function (xhr) {
                    console.error('Lỗi khi lấy chi tiết công việc:', xhr.responseText);
                    // $('.value__content').html('<p>Không thể tải chi tiết công việc.</p>');
                    $('.value__notFound').hide();
                }
            });
        }

        $('#btnSuggest').click(function () {
            $('.value__content').html('<div class="loader"></div>');
            const skill = $('#skill').val();
            // console.log(skill);
            
            if (!skill) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }
            
            $('.value').css('display', 'flex');

            $.ajax({
                url: '/api/suggest-job',
                method: 'POST',
                data: {
                    skill: skill,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // console.log('Gợi ý công việc thành công:', response.skills);
                    jobList = response.jobs || [];
                    jobIndex = 0;
                    console.log(jobList);

                    if (jobList.length > 0) {
                        renderJob(jobList[jobIndex]);
                        $('.value__notFound').hide();
                    } else {
                        $('.value__notFound').show();
                        $('.value__content').html('');
                        renderJob(null);
                    }
                },
                error: function (xhr) {
                    console.error('Lỗi:', xhr.responseText);
                    alert('Đã xảy ra lỗi khi gợi ý: ' + xhr.responseText);
                }
            });
        });

        $('#btnNext').click(function () {   
            $('.value__content').html('<div class="loader"></div>');
            // console.log(jobList);
            jobIndex++;
            if (jobIndex < jobList.length) {
                renderJob(jobList[jobIndex]);
                // $('.value__content').html('haiz');
            } else {
                // alert('Không còn công việc nào khác phù hợp.');
                $('.value__notFound').show();
                $('.value__content').html('');
            }
            console.log(jobList[jobIndex]);
        });

        $('.value__exit').click(function () {
            $('.value').hide();
        });
    </script>
@endsection