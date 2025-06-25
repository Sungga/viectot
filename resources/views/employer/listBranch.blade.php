@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>
    @vite(['resources/css/employer/listBranch.css'])
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endsection

@section('content')
    {{-- top --}}
    <section class="post-top">
        <div class="grid-container">
            <div class="post-top__container">
                <p class="post-top__addCompany">Thêm công ty quản lý</p>
            </div>
        </div>
    </section>
    {{-- your company --}}
    <section class="post-yourCompany">
        <div class="grid-container">
            <div class="post-yourCompany__conatiner">
                <h2 class="post-yourCompany__title">Công ty bạn quản lý</h2>
                @if (count($yourCompanies) == 0)
                    <p style="width: 100%; text-align: center; font-style: italic; font-size: 1.4rem;">Bạn chưa có công ty nào</p>
                @endif
                {{-- begin your --}}
                @foreach ($yourCompanies as $yourCompany)    
                    <div class="post-yourCompany__company">
                        <div class="post-yourCompany__top">
                            <h3 class="post-yourCompany__name">
                                <img src="{{ asset('storage/uploads/'.$yourCompany['logo']) }}" alt="">
                                <p>{{ $yourCompany['name'] }} 
                                    :
                                    <span idCompany="{{ $yourCompany->id }}" class="post-yourCompany__update" style="color: var(--color-base); margin-left: 8px; cursor:pointer;"><i class="fa-solid fa-pen-to-square"></i></span>
                                    <a href="{{ route('employer.deleteCompany', ['id_company' => $yourCompany->id]) }}" style="color: var(--color-red); margin-left: 8px;"><i class="fa-solid fa-trash"></i></a>
                                </p>
                            </h3>
                            <span class="post-yourCompany__addBranch" id_company="{{ $yourCompany['id'] }}">Thêm chi nhánh</span>
                        </div>
                        <div class="post-yourCompany__branches">
                            <div class="post-listBranch">
                                @php
                                    $companyBranches = $ownedBranches->where('id_company', $yourCompany['id']);
                                @endphp

                                @if ($companyBranches->isEmpty())
                                    <p style="width: 100%; text-align: center; font-style: italic; font-size: 1.4rem;">Công ty này hiện chưa có chi nhánh nào</p>
                                @else
                                    @foreach ($companyBranches as $branch)
                                    {{-- @foreach ($ownedBranches->where('id_company', $yourCompany['id']) as $branch) --}}
                                        <div class="post-listBranch__item">
                                            @if ($branch->images->isNotEmpty())
                                                <div class="post-listBranch__img">
                                                    <img src="{{ asset($branch->images[0]->img) }}" alt="">
                                                </div>
                                            @else
                                                <div class="post-listBranch__img">
                                                    <img src="{{ asset('storage/uploads/company.avif') }}" alt="">
                                                </div>
                                            @endif
                                            <div class="post-listBranch__name">{{ $branch['name'] }}</div>
                                            <div class="post-listBranch__address">
                                                <span class="post-listBranch__district">{{ $branch->branchDistrict->name ?? 'Không có dữ liệu' }}</span>
                                                <span class="post-listBranch__province">{{ $branch->branchProvince->name ?? 'Không có dữ liệu' }}</span>
                                                <span class="post-listBranch__province" style="background: var(--color-base); color: var(--color-white);">Số bài tuyển dụng: {{ $countPosts[$branch->id] }}</span>
                                                <span class="post-listBranch__province post-listBranch__manager" style="background: var(--color-base); color: var(--color-white);">Người hỗ trợ tuyển dụng: 
                                                    <br>
                                                    {{ $managedBranches[$branch->id]->name ?? 'Không có' }}
                                                    {{ optional($managedBranches[$branch->id] ?? null)->id_user ? '#' . optional($managedBranches[$branch->id] ?? null)->id_user : '' }}
                                                </span>
                                            </div>
                                            <div class="post-listBranch__action">
                                                <a class="post-listBranch__edit" idBranch="{{ $branch->id }}">Chỉnh sửa</a>
                                                <a href="{{ route('employer.deleteBranch', ['id_branch' => $branch->id]) }}" class="post-listBranch__delete">Xóa</a>
                                            </div>
                                            <input type="hidden" name="id_branch" class="post-listBranch__idBranch" id_branch="{{ $branch['id'] }}">
                                            <a href="{{ route('employer.listPost', ['id_branch' => $branch['id']]) }}" class="post-listBranch__showPost">Danh sách bài đăng</a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- end your --}}
                {{-- start branch --}}
                <h2 style="width:100%; font-size:3.2rem;margin-top:16px;color:var(--color-base);">Chi nhánh bạn hỗ trợ quản lý</h2>
                @if (count($branchManagers) == 0)
                    <p style="width: 100%; text-align: center; font-style: italic; font-size: 1.4rem;">Bạn chưa hỗ trợ quản lý công ty nào</p>
                @endif
                <div class="post-listBranch">
                    @foreach ($branchManagers as $branchManager)
                        <div class="post-listBranch__item">
                            <div class="post-listBranch__img">
                                <img src="{{ asset($branchManager->images[0]->img) }}" alt="">
                            </div>
                            <div class="post-listBranch__name">{{ $branchManager->name }}</div>
                            <div class="post-listBranch__address">
                                <span class="post-listBranch__district">{{ $branchManager->branchDistrict->name ?? 'Không có dữ liệu' }}</span>
                                <span class="post-listBranch__province">{{ $branchManager->branchProvince->name ?? 'Không có dữ liệu' }}</span>
                                <span class="post-listBranch__province" style="background: var(--color-base); color: var(--color-white);">Số bài tuyển dụng: {{ $countPosts[$branchManager->id] }}</span>
                            </div>
                            <a href="{{ route('employer.listPost', ['id_branch' => $branchManager['id']]) }}" class="post-listBranch__showPost">Danh sách bài đăng</a>
                        </div>
                    @endforeach
                </div>
                {{-- end branch --}}
            </div>
        </div>
    </section>
    {{-- your branch --}}
    {{-- <section class="post-yourBranch">
        <div class="grid-container">
            <div class="post-yourBranch__container">
                <h2 class="post-yourCompany__title">Chi nhánh công ty bạn quản lý</h2>
                <div class="post-listBranch">
                    <div class="post-listBranch__item">
                        <div class="post-listBranch__img">
                            <img src="{{ asset('storage/uploads/company.avif') }}" alt="">
                        </div>
                        <div class="post-listBranch__name">Samsung chi nhánh Yên Phong 1</div>
                        <div class="post-listBranch__address">
                            <span class="post-listBranch__district">Hiệp Hòa</span>
                            <span class="post-listBranch__province">Bắc Giang</span>
                        </div>
                        <div class="post-listBranch__action">
                            <a href="#" class="post-listBranch__edit">Chỉnh sửa</a>
                            <a href="#" class="post-listBranch__delete">Xóa</a>
                        </div>
                    </div>
                    <div class="post-listBranch__item">
                        <div class="post-listBranch__img">
                            <img src="{{ asset('storage/uploads/company.avif') }}" alt="">
                        </div>
                        <div class="post-listBranch__name">Samsung chi nhánh Yên Phong 1</div>
                        <div class="post-listBranch__address">
                            <span class="post-listBranch__district">Hiệp Hòa</span>
                            <span class="post-listBranch__province">Bắc Giang</span>
                        </div>
                        <div class="post-listBranch__action">
                            <a href="#" class="post-listBranch__edit">Chỉnh sửa</a>
                            <a href="#" class="post-listBranch__delete">Xóa</a>
                        </div>
                    </div>
                    <div class="post-listBranch__item">
                        <div class="post-listBranch__img">
                            <img src="{{ asset('storage/uploads/company.avif') }}" alt="">
                        </div>
                        <div class="post-listBranch__name">Samsung chi nhánh Yên Phong 1</div>
                        <div class="post-listBranch__address">
                            <span class="post-listBranch__district">Hiệp Hòa</span>
                            <span class="post-listBranch__province">Bắc Giang</span>
                        </div>
                        <div class="post-listBranch__action">
                            <a href="#" class="post-listBranch__edit">Chỉnh sửa</a>
                            <a href="#" class="post-listBranch__delete">Xóa</a>
                        </div>
                    </div>
                    <div class="post-listBranch__item">
                        <div class="post-listBranch__img">
                            <img src="{{ asset('storage/uploads/company.avif') }}" alt="">
                        </div>
                        <div class="post-listBranch__name">Samsung chi nhánh Yên Phong 1</div>
                        <div class="post-listBranch__address">
                            <span class="post-listBranch__district">Hiệp Hòa</span>
                            <span class="post-listBranch__province">Bắc Giang</span>
                        </div>
                        <div class="post-listBranch__action">
                            <a href="#" class="post-listBranch__edit">Chỉnh sửa</a>
                            <a href="#" class="post-listBranch__delete">Xóa</a>
                        </div>
                    </div>
                    <div class="post-listBranch__item">
                        <div class="post-listBranch__img">
                            <img src="{{ asset('storage/uploads/company.avif') }}" alt="">
                        </div>
                        <div class="post-listBranch__name">Samsung chi nhánh Yên Phong 1</div>
                        <div class="post-listBranch__address">
                            <span class="post-listBranch__district">Hiệp Hòa</span>
                            <span class="post-listBranch__province">Bắc Giang</span>
                        </div>
                        <div class="post-listBranch__action">
                            <a href="#" class="post-listBranch__edit">Chỉnh sửa</a>
                            <a href="#" class="post-listBranch__delete">Xóa</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    {{-- add company --}}
    <section class="post-addCompany">
        <div class="post-addCompany__cont">
            <form action="{{ route('employer.addCompany') }}" method="post" enctype="multipart/form-data">
                <div class="post-addCompany__close"><i class="fa-solid fa-left-long"></i></div>
                <button class="post-addCompany__save"><i class="fa-solid fa-floppy-disk"></i></button>
                <div class="post-addCompany__container">
                    <h3 class="post--addCompany__title">Thêm công ty của bạn</h3>
                    {{-- token --}}
                    @csrf
                    {{-- logo --}}
                    <label for="avatar-input" class="account__avtLabel">
                        <img id="avatar-preview" src="{{ asset('storage/uploads/company.avif') }}" alt="Avatar" class="account__avt">
                        <div class="account__avatar--overlay">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                    </label>
                    <input type="file" id="avatar-input" accept="image/*" hidden name="logo" required>
                    {{-- address --}}
                    
                    {{-- name --}}
                    <input type="text" class="post-addCompany__name" placeholder="Nhập tên công ty của bạn" name="name" id="name" required>
                    
                    {{-- desc --}}
                    <p class="post-addCompany__miniTitle">Mô tả công ty</p>
                    <textarea class="post-addCompany__desc" placeholder="Nhập mô tả công ty của bạn" name="desc" id="ckeditorAddCompany" required></textarea>
                    
                    {{-- documents --}}
                    <p class="post-addCompany__miniTitle post-addCompany__miniTitle--document">Tài liệu để xác thực công ty <span style="color: red;">(Chúng tôi đảm bảo rằng sẽ chỉ có Việc Tốt nhìn thấy tài liệu này của bạn)</span></p>
                    <label class="post-addCompany__document" for="post-addCompany__document"><i class="fa-solid fa-cloud-arrow-up"></i></label>
                    <input type="file" name="document[]" accept=".pdf,.jpg,.jpeg,.png" required multiple hidden id="post-addCompany__document">
                </div>
            </form>    
        </div>
    </section>
    {{-- update company --}}
    <section class="post-updateCompany">
        <div class="post-updateCompany__cont">
            <form action="{{ route('employer.updateCompany') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="post-updateCompany__close"><i class="fa-solid fa-left-long"></i></div>
                <button class="post-updateCompany__save"><i class="fa-solid fa-floppy-disk"></i></button>
                <div class="post-updateCompany__container">
                    <h3 class="post--updateCompany__title">Sửa công ty của bạn</h3>
                    {{-- token --}}
                    @csrf
                    {{-- logo --}}
                    <label for="avatar-input" class="account__avtLabel">
                        <img id="avatar-preview" src="{{ asset('storage/uploads/company.avif') }}" alt="Avatar" class="account__avt">
                        <div class="account__avatar--overlay">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                    </label>
                    <input type="file" id="avatar-input" accept="image/*" hidden name="logo">

                    {{-- id company --}}
                    <input type="text" class="post-updateCompany__idCompany" name="id_company" id="" hidden value="" required>
                    
                    {{-- name --}}
                    <input type="text" class="post-updateCompany__name" placeholder="Nhập tên công ty của bạn" name="name" id="name" required>
                    
                    {{-- desc --}}
                    <p class="post-updateCompany__miniTitle">Mô tả công ty</p>
                    <textarea class="post-updateCompany__desc" placeholder="Nhập mô tả công ty của bạn" name="desc" id="ckeditorUpdateCompany" required></textarea>
                </div>
            </form>    
        </div>
    </section>
    {{-- add branch --}}
    <section class="post-addBranch">
        <div class="post-addCompany__cont">
            <div class="post-addCompany__container">
                <form action="{{ route('employer.addBranch') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <input class="post-addBranch__idCompany" type="text" name="id_company" id="" hidden value="">
                    <h3 class="post--addCompany__title">Thêm chi nhánh công ty của bạn</h3>
                    <div class="post-addBranch__close"><i class="fa-solid fa-left-long"></i></div>
                    <button class="post-addBranch__save"><i class="fa-solid fa-floppy-disk"></i></button>
                    {{-- name --}}
                    <input type="text" class="post-addCompany__name" placeholder="Nhập tên chi nhánh của bạn" name="name" id="name" required>
                    
                    {{-- address --}}
                    <p class="post-addCompany__miniTitle">Địa chỉ chi nhánh</p>
                    <div class="post-addCompany__address">
                        <select name="province" id="" class="post-addCompany__province"></select>
                        <select name="district" id="" class="post-addCompany__district"></select>
                    </div>
                    
                    {{-- desc --}}
                    <p class="post-addCompany__miniTitle">Mô tả chi nhánh</p>
                    <textarea class="post-addCompany__desc" placeholder="Nhập mô tả chi nhánh của bạn" name="desc" id="ckeditorAddBranch" required></textarea>
                    
                    {{-- image --}}
                    <p class="post-addCompany__miniTitle post-addCompany__miniTitle--document">Hình ảnh thực tế của chi nhánh</p>
                    <label class="post-addBranch__img" for="post-addBranch__img"><i class="fa-solid fa-cloud-arrow-up"></i></label>
                    <input type="file" name="img[]" accept=".jpg,.jpeg,.png" required multiple hidden id="post-addBranch__img" style="opacity: 0; position: absolute; z-index: -1;">  
                    
                    {{-- map --}}
                    {{-- <p class="post-addCompany__miniTitle post-addCompany__miniTitle--document">Vị trí thực tế của chi nhánh</p>
                    <div id="map"></div>
                    <input type="text" id="latitude" name="latitude" hidden value="10.762622">
                    <input type="text" id="longitude" name="longitude" hidden value="106.660172"> --}}
                </form>
                {{-- <button id="locate-btn" style="width: 100%; height: 50px; background-color: var(--color-base); color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1.6rem; display: flex; align-items: center; justify-content: center;">
                    📍 Vị trí của tôi
                </button>                 --}}
            </div>
        </div>
    </section>
    {{-- update branch --}}
    <section class="post-updateBranch">
        <div class="post-updateCompany__cont">
            <div class="post-updateCompany__container">
                <form action="{{ route('employer.updateBranch') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <input class="post-updateBranch__idBranch" type="text" name="id_branch" id="" hidden value="">
                    <h3 class="post--updateCompany__title">Thêm chi nhánh công ty của bạn</h3>
                    <div class="post-updateBranch__close"><i class="fa-solid fa-left-long"></i></div>
                    <button class="post-updateBranch__save"><i class="fa-solid fa-floppy-disk"></i></button>
                    {{-- name --}}
                    <input type="text" class="post-updateBranch__name" placeholder="Nhập tên chi nhánh của bạn" name="name" id="name" required>
                    
                    {{-- updateress --}}
                    <p class="post-updateCompany__miniTitle">Địa chỉ chi nhánh</p>
                    {{-- <div class="post-updateCompany__updateress">
                        <select name="province" id="" class="post-updateBranch__province">
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}" {{ $province->name }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="district" id="" class="post-updateBranch__district">
                        </select>
                    </div> --}}
                    <div class="post-updateCompany__updateress" style="display:flex;align-item:center;justify-content:space-between;">
                        <select name="province" id="province" class="post-updateBranch__province">
                            {{-- <option value="">Chọn tỉnh/thành</option> --}}
                            @foreach ($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        
                        <select name="district" id="district" class="post-updateBranch__district">
                            {{-- <option value="">Chọn quận/huyện</option> --}}
                        </select>
                    </div>    
                    
                    {{-- desc --}}
                    <p class="post-updateCompany__miniTitle">Mô tả chi nhánh</p>
                    <textarea class="post-updateCompany__desc" placeholder="Nhập mô tả chi nhánh của bạn" name="desc" id="ckeditorUpdateBranch" required></textarea>
                    
                    {{-- image --}}
                    <p class="post-updateCompany__miniTitle post-updateCompany__miniTitle--document">Hình ảnh thực tế của chi nhánh</p>
                    <label class="post-updateBranch__img" for="post-updateBranch__img"><i class="fa-solid fa-cloud-arrow-up"></i></label>
                    <input type="file" name="img[]" accept=".jpg,.jpeg,.png" multiple hidden id="post-updateBranch__img" style="opacity: 0; position: absolute; z-index: -1;">  
                    <div>
                        <p style="font-size: 1.4rem; margin-top:16px;">Các hình ảnh đã lưu</p>
                        <div class="post-updateBranch__imgOld">
                            {{-- <div class="post-updateBranch__imgOld--item">
                                <input type="checkbox" name="image" id="" value="1">
                                <img src="{{ asset('storage/images/avt.jpg') }}" alt="">
                            </div> --}}
                        </div>
                    </div>
                    
                    {{-- map --}}
                    {{-- <p class="post-updateCompany__miniTitle post-updateCompany__miniTitle--document">Vị trí thực tế của chi nhánh</p>
                    <div id="map"></div>
                    <input type="text" id="latitude" name="latitude" hidden value="10.762622">
                    <input type="text" id="longitude" name="longitude" hidden value="106.660172"> --}}
                </form>
                {{-- <button id="locate-btn" style="width: 100%; height: 50px; background-color: var(--color-base); color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1.6rem; display: flex; align-items: center; justify-content: center;">
                    📍 Vị trí của tôi
                </button>                 --}}
            </div>
        </div>
    </section>
    {{-- add manager branch --}}
    <section class="post-addManager">
        <div class="post-addManager__container">
            <i class="fa-solid fa-circle-xmark post-addManager__exit"></i>
            <h3 class="post--addCompany__title">Thêm người quản lý chi nhánh</h3>
            <div class="post-addManager__search">
                <input class="post-addManager__search--name"  type="text" name="name" id="" placeholder="Nhập tên người quản lý">
                <input class="post-addManager__search--id"  type="text" name="id_user" id="" placeholder="ID">
            </div>
            <button class="post-addManager__search--search">Tìm kiếm</button>
            <form action="{{ route('employer.addBranchManager') }}" method="post" class="post-addManager__search--form">
                @csrf
                <input type="hidden" name="id_branch" class="post-addManager__idBranch" value="" required>
                <input type="hidden" name="id_user" class="post-addManager__idUser" value="" required>
                <input type="hidden" name="name" class="post-addManager__name" value="" required>
                <p class="post-addManager__search--alerNotFound" style="display: none">Không thể tìm thấy nhà tuyển dụng phù hợp</p>
                <div class="post-addManager__search--select">
                    {{-- <button>
                        <img src="{{ asset('storage/images/avt.jpg') }}" alt="">
                        <span class="post-addManager__search--selectName">Trương Anh Vũ #1</span>
                    </button> --}}
                </div>
            </form>
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
    @vite(['resources/js/employer/listBranch.js'])

    {{-- js ckEditor --}}
    <script>
        CKEDITOR.replace('ckeditorAddCompany');
        CKEDITOR.replace('ckeditorUpdateCompany');
        CKEDITOR.replace('ckeditorAddBranch');
        CKEDITOR.replace('ckeditorUpdateBranch');
    </script>

    {{-- js address --}}
    <script>
        let cachedData = null;
    
        const candidate = {
            province: 1, // Mặc định giá trị tỉnh (nếu có sẵn)
            district: 101 // Mặc định giá trị quận/huyện (nếu có sẵn)
        };
    
        async function getDataAPI() {
            if (cachedData) return cachedData;
            const url = "https://provinces.open-api.vn/api/?depth=2";
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error(`Lỗi: ${response.status}`);
                cachedData = await response.json();
                return cachedData;
            } catch (error) {
                console.error("Lỗi khi gọi API:", error);
                return [];
            }
        }
    
        async function createSelectBox() {
            const data = await getDataAPI();
            let provincesSelectBoxHtml = '';
    
            for (let i = 0; i < data.length; i++) {
                provincesSelectBoxHtml += `
                    <option value="${data[i].code}" ${candidate.province == data[i].code ? 'selected' : ''}>
                        ${data[i].name}
                    </option>
                `;
            }
    
            const provinceSelect = document.querySelector('.post-addCompany__province');
            provinceSelect.innerHTML = provincesSelectBoxHtml;
    
            // Tạo select box quận/huyện tương ứng sau khi tạo xong tỉnh
            createDistrictSelectBox(provinceSelect.value);
        }
    
        async function createDistrictSelectBox(idProvince) {
            const data = await getDataAPI();
            let districtSelectBoxHtml = `
                <option value="9999">Chọn quận/huyện</option>
            `;
    
            const province = data.find(item => item.code == idProvince);
            if (province && province.districts) {
                for (let i = 0; i < province.districts.length; i++) {
                    const district = province.districts[i];
                    districtSelectBoxHtml += `
                        <option value="${district.code}" ${candidate.district == district.code ? 'selected' : ''}>
                            ${district.name}
                        </option>
                    `;
                }
            }
    
            const districtSelect = document.querySelector('.post-addCompany__district');
            districtSelect.innerHTML = districtSelectBoxHtml;
        }
    
        // Gọi hàm tạo danh sách tỉnh/thành phố ban đầu
        createSelectBox();
    
        // Khi người dùng chọn tỉnh thì cập nhật lại danh sách quận/huyện
        document.querySelector('.post-addCompany__province').addEventListener('change', function () {
            createDistrictSelectBox(this.value);
        });
    </script>
    

    {{-- js map --}}
    {{-- <script>
        const defaultLat = 10.762622;
        const defaultLng = 106.660172;
        const map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        let marker;
        function onMapClick(e) {
            const { lat, lng } = e.latlng;
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }
        map.on('click', onMapClick);

        document.getElementById('locate-btn').addEventListener('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 13);
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                });
            } else {
                alert('Trình duyệt không hỗ trợ định vị.');
            }
        });
    </script> --}}


    {{-- js add employer --}}
    <script>
        const showFormAddManager = document.querySelectorAll('.post-listBranch__manager');
        const formAddManager = document.querySelector('.post-addManager');
        const inputIdBranch = document.querySelector('.post-addManager__idBranch');
        const exitFormAddManager = document.querySelector('.post-addManager__exit');

        showFormAddManager.forEach((item, index) => {
            item.addEventListener('click', function () {
                formAddManager.style.display = 'flex';
                inputIdBranch.value = this.parentElement.parentElement.querySelector('.post-listBranch__idBranch').getAttribute('id_branch');
            });
        });

        exitFormAddManager.addEventListener('click', function () {
            formAddManager.style.display = 'none';
        });
    </script>

    {{-- jquery ajax search employer for add manager --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.post-addManager__search--search').click(function () {
            const name = $('.post-addManager__search--name').val();
            const id = $('.post-addManager__search--id').val();
            
            if (!name || !id) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }

            $.ajax({
                url: '/employer/searchEmployer',
                method: 'POST',
                data: {
                    name: name,
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    user = response.user || [];

                    if (!user || Object.keys(user).length === 0) {
                        $('.post-addManager__search--alerNotFound').show();
                        $('.post-addManager__search--select').html('');
                        return;
                    }

                    const baseAssetUrl = '{{ asset('storage/uploads') }}';
                    $('.post-addManager__search--select').html(`
                        <button>
                            <img src="${baseAssetUrl}/${user.avatar}" alt="">
                            <span class="post-addManager__search--selectName">${user.name} #${user.id_user}</span>
                        </button>
                    `);
                    $('.post-addManager__idUser').val(user.id_user);
                    $('.post-addManager__name').val(user.name);
                    $('.post-addManager__search--alerNotFound').hide();
                },
                error: function (xhr) {
                    // console.error('Lỗi khi tìm kiếm nhà tuyển dụng:', xhr.responseText);
                    $('.post-addManager__search--alerNotFound').show();
                    $('.post-addManager__search--select').html('');
                }
            });
        });
    </script>

    {{-- js update company --}}
    <script>
        const updateCompanyShowForm = document.querySelectorAll('.post-yourCompany__update');
        const updateCompanyForm = document.querySelector('.post-updateCompany');
        const closeUpdateCompanyForm = document.querySelector('.post-updateCompany__close');

        const idInput = document.querySelector('.post-updateCompany .post-updateCompany__idCompany');
        const avtInput = document.querySelector('.post-updateCompany .account__avt');
        const nameInput = document.querySelector('.post-updateCompany .post-updateCompany__name');
        const descInput = document.querySelector('.post-updateCompany .post-updateCompany__miniTitle');

        const yourCompanies = @json($yourCompanies);
        let companyInfo = null;

        updateCompanyShowForm.forEach((item) => {
            item.addEventListener('click', function () {
                updateCompanyForm.style.display = 'flex';
                // avt.value =
                // console.log(item.getAttribute("idCompany"));
                let id = item.getAttribute("idCompany");
                companyInfo = yourCompanies.find(company => company.id == id);
                if (companyInfo) {
                    idInput.value = companyInfo.id;
                    avtInput.src = "{{ asset('storage/uploads') }}/" + companyInfo.logo;
                    nameInput.value = companyInfo.name;
                    // descInput.value = companyInfo.desc;
                    CKEDITOR.instances['ckeditorUpdateCompany'].setData(companyInfo.desc);
                } else {
                    console.error("Không tìm thấy thông tin công ty với ID:", id);
                }
            });
        });

        closeUpdateCompanyForm.addEventListener('click', function () {
            updateCompanyForm.style.display = 'none';
        });
    </script>

    {{-- js update branch --}}
    <script>
        const updateBranchShowForm = document.querySelectorAll('.post-listBranch__edit');
        const updateBranchForm = document.querySelector('.post-updateBranch');
        const closeUpdateBranchForm = document.querySelector('.post-updateBranch__close');

        const idBranchInput = document.querySelector('.post-updateBranch .post-updateBranch__idBranch');
        const nameBranchInput = document.querySelector('.post-updateBranch .post-updateBranch__name');
        const provinceBranchInput = document.querySelector('.post-updateBranch .post-updateBranch__province');
        const districtBranchInput = document.querySelector('.post-updateBranch .post-updateBranch__district');
        const imgBranchInput = document.querySelector('.post-updateBranch .post-updateBranch__imgOld');

        const ownedBranches = @json($ownedBranches);
        let branchInfo = null;
        const districts = @json($districts);

        updateBranchShowForm.forEach((item) => {
            item.addEventListener('click', function () {
                updateBranchForm.style.display = 'flex';
                let id = item.getAttribute("idBranch");
                branchInfo = ownedBranches.find(branch => branch.id == id);
                if (branchInfo) {
                    idBranchInput.value = branchInfo.id;
                    nameBranchInput.value = branchInfo.name;
                    CKEDITOR.instances['ckeditorUpdateBranch'].setData(branchInfo.desc);

                    let imgs = branchInfo.images || [];
                    imgs.forEach(img => {
                        let imgItem = document.createElement('div');
                        imgItem.classList.add('post-updateBranch__imgOld--item');

                        // Tạo URL cho ảnh
                        let imageUrl = `/${img.img}`; // img.img = "storage/branches/xxx.jpg"

                        imgItem.innerHTML = `
                            <span style="font-size:1.4rem;">Chọn xóa</span>
                            <input type="checkbox" name="imgDel[]" value="${img.id}">
                            <img src="${imageUrl}" alt="" style="max-width: 100%; margin-bottom: 10px; border-radius: 5px;">
                        `;
                        imgBranchInput.appendChild(imgItem);
                    });


                    provinceBranchInput.value = branchInfo.province;

                    // Xóa các option hiện có
                    districtBranchInput.innerHTML = '';

                    // Lọc các huyện theo province_id
                    let filteredDistricts = districts.filter(d => d.province_id == branchInfo.province);

                    // Tạo các option mới
                    filteredDistricts.forEach(district => {
                        let option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        if (district.id == branchInfo.district) {
                            option.selected = true; // Chọn huyện tương ứng
                        }
                        districtBranchInput.appendChild(option);
                    });
                } else {
                    console.error("Không tìm thấy thông tin công ty với ID:", id);
                }
            });
        });

        closeUpdateBranchForm.addEventListener('click', function () {
            updateBranchForm.style.display = 'none';
        });


        // {{-- js select provice so show districts --}}
        // <!-- Truyền dữ liệu huyện xuống JS -->
        document.getElementById('province').addEventListener('change', function () {
            const provinceBranchSetId = this.value;
            const districtBranchSetSelect = document.getElementById('district');

            // Xóa các option hiện có
            districtBranchSetSelect.innerHTML = '';

            // Lọc các huyện theo province_id
            let filteredDistricts = districts.filter(d => d.province_id == provinceBranchSetId);

            // Tạo các option mới
            filteredDistricts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                districtBranchSetSelect.appendChild(option);
            });
        });
    </script>
@endsection
