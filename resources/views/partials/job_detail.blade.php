{{-- <div class="job-detail">
    <h3>{{ $job->title }}</h3>
    <p><strong>Mô tả:</strong> {{ $job->description }}</p>
    <p><strong>Chi nhánh:</strong> {{ $job->branch->name ?? 'Không có' }}</p>
    <p><strong>Kỹ năng:</strong> {{ $job->skills }}</p>
    <p><strong>Hạn nộp:</strong> {{ $job->deadline }}</p>
</div> --}}

<section class="post">
        <div class="grid-container">
            <div class="post__container">
                <p style="font-size: 1.4rem; padding: 12px 20px; border-radius: 12px; background:rgb(193, 217, 248); margin: 12px 0 20px; width: 100%;">
                    @if ((float)$score >= 0.8)
                        <i class="fa-solid fa-circle" style="color: #2ecc71;"></i>
                        <span style="margin-left: 6px;">Rất phù hợp</span>
                    @elseif ((float)$score >= 0.6 && (float)$score < 0.8)
                        <i class="fa-solid fa-circle" style="color: #58d68d;"></i>
                        <span style="margin-left: 6px;">Phù hợp</span>
                    @elseif ((float)$score >= 0.4 && (float)$score < 0.6)
                        <i class="fa-solid fa-circle" style="color: #f4d03f;"></i>
                        <span style="margin-left: 6px;">Tạm ổn</span>
                    @elseif ((float)$score >= 0.3 && (float)$score < 0.4)
                        <i class="fa-solid fa-circle" style="color: #f5b041;"></i>
                        <span style="margin-left: 6px;">Ít phù hợp</span>
                    @elseif ((float)$score < 0.3)
                        <i class="fa-solid fa-circle" style="color: #e74c3c;"></i>
                        <span style="margin-left: 6px;">Không phù hợp</span>
                    @endif
                </p>
                <div class="post__left">
                    <div class="post__leftTop">
                        <h2 class="post__leftTop--title">{{ $post->title }}</h2>
                        <div class="post__leftTop--about">
                            <div>
                                <div><i class="fa-solid fa-dollar-sign"></i></div>
                                <p><span class="post__leftTop--aboutTitle">Thu nhập</span><span class="post__leftTop--aboutValue">{{ $post->salary_show }}</span></p>
                            </div>
                            <div>
                                <div><i class="fa-solid fa-location-dot"></i></div>
                                <p><span class="post__leftTop--aboutTitle">Địa điểm</span><span class="post__leftTop--aboutValue">{{ $post->branch->branchProvince->name }}</span></p>
                            </div>
                            <div>
                                <div><i class="fa-regular fa-hourglass-half"></i></div>
                                <p><span class="post__leftTop--aboutTitle">Kinh nghiệm</span><span class="post__leftTop--aboutValue">{{ $post->experience_show }}</span></p>
                            </div>
                        </div>
                        <div class="post__leftTop--deadline">
                            <p><i class="fa-solid fa-clock"></i> Hạn nộp hồ sơ: {{ \Carbon\Carbon::parse($post->deadline)->format('d/m/Y') }}</p>
                        </div>
                        <div class="post__leftTop--quantity">
                            <p><i class="fa-solid fa-user"></i> Số lượng tuyển: {{ $post->quantity }}</p>
                        </div>
                        <div class="post__topLeft--bottom">
                            <a href="{{ route('apply.form', ['id_post' => $post->id]) }}" class="post__topLeft--apply">Ứng tuyển</a>
                            <a href="#" class="post__topLeft--save">Lưu tin</a>
                        </div>
                    </div>
                    <div class="top__rightCenter">
                        <h3 class="top__rightCenter--title">Thông tin chung</h3>
                        <div class="top__rightCenter--item">
                            <div class="top__rightCenter--icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <p><span class="post__rightCenter--title">Học vấn</span><span class="post__rightCenter--value">{{ $post->degree_show }}</span></p>
                        </div>
                        <div class="top__rightCenter--item">
                            <div class="top__rightCenter--icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <p><span class="post__rightCenter--title">Số lượng</span><span class="post__rightCenter--value">{{ $post->quantity }}</span></p>
                        </div>
                        <div class="top__rightCenter--item">
                            <div class="top__rightCenter--icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <p><span class="post__rightCenter--title">Hình thức làm việc</span><span class="post__rightCenter--value">{{ $post->work_mode_show }}</span></p>
                        </div>
                    </div>
                    <div class="post__leftBottom">
                        <h2 class="post__leftBottom--title">Chi tiết tin tuyển dụng</h2>
                        <p><a href="#" class="post__leftBottom--category">Chuyên ngành {{ $categoryName }}</a></p>
                        <p class="post__leftBottom--skillTitle">Các kỹ năng yêu cầu: 
                            @foreach(json_decode($post->skills, true) as $item)
                                <a href="#" class="post__leftBottom--skill">{{ $item['value'] }}</a>
                            @endforeach
                        </p>
                        <h3 class="post__leftBottom--descTitle">Mô tả công việc</h3>
                        <div class="post__leftBottom--desc">
                            {!! $post->description !!}
                        </div>
                        <div>
                            <a href="{{ route('apply.form', ['id_post' => $post->id]) }}" class="post__leftBottom--apply">Ứng tuyển ngay</a>
                            <a href="#" class="post__leftBottom--save">Lưu tin</a>
                        </div>
                        <p class="post__leftBottom--bottom"><i class="fa-solid fa-circle-info"></i> Báo cáo tin tuyển dụng: Nếu bạn thấy rằng tin tuyển dụng này không đúng hoặc có dấu hiệu lừa đảo, hãy <a href="{{ route('report.form', ['id_post' => $post->id]) }}">phản ánh với chúng tôi.</a></p>
                    </div>
                </div>
                <div class="post__right">
                    <div class="post__rightTop">
                        <div class="post__rightTop--top">
                            <div class="post__rightTop--logo">
                                <img style="width: 100px; height: 100px;" src="{{ asset('storage/uploads/'.$post->branch->company->logo) }}" alt="">
                            </div>
                            <a href="{{ route('branch.show', ['id_branch' => $post->branch->id]) }}" class="post__rightTop--branch">{{ $post->branch->name }}</a>
                        </div>
                        <a href="#" class="post__rightTop--company">{{ $post->branch->company->name }}</a>
                        <p class="post__rightTop--address">Địa chỉ: {{ $address }}</p>
                        <p class="post__rightTop--seeCompany"><a href="{{ route('branch.show', ['id_branch' => $post->branch->id]) }}">Xem trang công ty <i class="fa-solid fa-arrow-right-to-city"></i></a></p>
                    </div>
                    <div class="post__rightBottom">
                        <p><i class="fa-solid fa-circle-info"></i> Báo cáo tin tuyển dụng: Nếu bạn thấy rằng tin tuyển dụng này không đúng hoặc có dấu hiệu lừa đảo, hãy <a href="{{ route('report.form', ['id_post' => $post->id]) }}">phản ánh với chúng tôi.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>