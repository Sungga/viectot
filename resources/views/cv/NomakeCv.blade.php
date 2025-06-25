<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
    <!-- font google -->    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @vite(['resources/css/base/base.css'])
    @vite(['resources/css/base/reset.css'])

    <style>
        /* cant edit */
        .cantEdit {
            width: 100%;
            height: 100vh;
            z-index: 99999;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, .5);
            display: none;
        }

        .cantEdit__alert {
            margin: auto;
            width: 120px;
            background-color: var(--color-white);
            padding: 12px;
            tool-box-sizing: content-box;
        }

        .cantEdit__alert p {
            font-size: 1.4rem;
            text-align: center;
        }

        .cantEdit__alert a {
            font-size: 1.4rem;
            border: 1px solid var(--color-base);
            padding: 6px 12px;
            background-color: var(--color-base);
            color: var(--color-white);
        }

        /* make cv */
        .makeCv {
            margin-top: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .editor {
            width: 900px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 20px 0;
            flex-wrap: wrap;
            border: 1px solid var(--color-black);
            margin-bottom: 20px;
            background: url({{ asset('storage/images/bgr_wood.jpg') }}) no-repeat center center / cover;
        }

        .editor__paper {
            width: 794px;
            height: 1123px;
            border: 1px solid var(--color-black);
            margin: 10px 0;
            tool-box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.25);
        }

        /* toolbox */
        .toolbox {
            width: 100%;
            height: 100px;
            position: fixed;
            top: 0;
            left: 0;
            border-bottom: 1px solid var(--color-black);
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            z-index: 999;
            background-color: var(--color-base-super-pale);
            tool-box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        }

        .toolbox__logo {
            width: 100px;
            height: 100px;
            border-right: 1px solid var(--color-black);
            display: flex;
            background-color: var(--color-white);
            border-bottom: 1px solid var(--color-black);
        }

        .toolbox__logo a {
            margin: auto;
        }

        .toolbox__logo img {
            width: 90px;
            height: 90px;
        }

        .toolbox__nav {
            width: 200px;
            height: 100%;
            display: flex;
            flex-wrap: wrap;
            tool-box-shadow: 2px 0px 2px rgba(0, 0, 0, 0.25);
        }

        .toolbox__nav p {
            width: 100%;
            text-align: center;
            padding: 2px 0;
            border: 1px solid var(--color-base);
            background-color: var(--color-base);
            color: var(--color-white);
            margin: 2px 10px 2px 20px;
            cursor: pointer;
            font-size: 1.4rem;
            border-radius: 12px;
        }

        .toolbox__list,
        .toolBgr__list,
        .setBox__list {
            width: calc(100% - 100px - 200px);
            height: 100%;
            overflow-x: auto;
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
        }

        .toolbox__item,
        .toolBgr__item,
        .setBox__item {
            min-width: 140px;
            height: 90px;
            border: 1px solid var(--color-black);
            margin: 0 10px;
            flex-shrink: 0;
            cursor: pointer;
            background-color: var(--color-white);
            user-select: none;
            transition: all ease .3s;
        }

        .toolbox__item {
            width: 140px;
        }

        .toolBgr__item {
            width: 80px;
        }

        .toolbox__item:hover,
        .toolBgr__item:hover,
        .setBox__item:hover {
            /* tool-box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); */
            tool-box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .toolbox__item--desc,
        .setBox__item--desc {
            width: 100%;
            height: 76%;
            border-bottom: 1px solid var(--color-black);
            display: flex;
            align-items: center;
        }

        /* box 1 */
        .tool-box-1 .toolbox__item--avt {
            width: 40%;
        }

        .tool-box-1 .toolbox__item--boxText {
            width: 60%;
            font-size: 1.2rem;
            text-align: center;
        }

        /* box 2 */
        .tool-box-2 {
            flex-wrap: wrap;
            justify-content: center;
        }

        .tool-box-2 .toolbox__item--avt {
            width: 30%;
        }

        .tool-box-2 .toolbox__item--boxText {
            width: 100%;
            font-size: 1.2rem;
            text-align: center;
        }

        /* box 3 */
        .tool-box-3 {
            flex-direction: column;
            padding: 8px;
        }

        .tool-box-3 .toolbox__item--title {
            text-align: left;
            width: 100%;
        }

        .tool-box-3 .toolbox__item--boxText {
            text-wrap: wrap;
            font-size: 1.2rem;
        }

        /* box 4 */
        .tool-box-4 {
            flex-direction: column;
            padding: 8px;
        }

        .tool-box-4 .toolbox__item--title {
            text-align: center;
            width: 100%;
        }

        .tool-box-4 .toolbox__item--boxText {
            text-wrap: wrap;
            font-size: 1.2rem;
        }

        /* box 5 */
        .tool-box-5 {
            padding: 8px;
        }

        .tool-box-5 .toolbox__item--left,
        .tool-box-5 .toolbox__item--right {
            width: 30%;
        }

        /* box 6 */
        /* box 5 */
        .tool-box-6 {
            padding: 8px;
        }

        .tool-box-6 .toolbox__item--title {
            text-align: center;
        }

        .tool-box-6 .toolbox__item--left,
        .tool-box-6 .toolbox__item--right {
            width: 30%;
        }

        .toolbox__item--name,
        .setBox__item--name {
            text-align: center;
            font-size: 1.4rem;
            padding: 0 8px;
            background-color: var(--color-base);
            color: var(--color-white);
        }

        .setBox__item--desc {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
        }

        .setbox__input--padding {
            border: 1px solid var(--color-black);
            padding: 4px 8px;
            font-size: 1.4rem;
            width: 100px;
            margin-right: 4px;
            outline: none;
        }   
        
        .setbox__input--unit {
            font-size: 1.4rem;
            padding: 6px 4px;
            outline: none;
        }

        .setBox__input--color {
            width: 70%;
            height: 40px;
        }

        .setBox__input--fontSize,
        .setBox__input--fontWeight {
            border: 1px solid var(--color-black);
            width: 60px;
            padding: 6px 8px;
        }

        /* paper */
        /*  
            editor-box là css tổng 
            editor-box-1 là css được lưu vào dtb
            box-1 là css được người dùng chỉnh sửa trực tiếp 
        */
        .editor-box-1 {
            display: flex;
            align-items: center;
        }

        .editor-box-2 {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            flex-direction: column
        }

        .editor__paper--boxText {
            flex: 1 1 auto; /* chiếm phần còn lại */
            width: 100%; /* không fix cứng 100% */
            max-width: 100%;
            text-align: center;
            font-size: 4rem;
            padding: 8px 16px;
            font-weight: 1000;
        }

        .editor__paper--avtLabel {
            width: 200px;
            height: 200px;
            display: block;
            cursor: pointer;
            position: relative;
            flex: 0 0 auto; /* KHÔNG co giãn, KHÔNG phình ra */
            /* border-radius: 50%; */
            overflow: hidden;
        }

        .editor__paper--avt {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        .editor__paper--avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0); /* lớp mờ */
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s ease;
            color: white;
            font-size: 24px;
        }

        .editor__paper--avtLabel:hover .editor__paper--avatar-overlay {
            background-color: rgba(0, 0, 0, 0.1); /* lớp mờ */
        }

        .editor__paper--avatar-overlay i {
            font-size: 2rem;
            opacity: 0;
            color: var(--color-base);
        }

        .editor__paper--avtLabel:hover .editor__paper--avatar-overlay i {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- TOOLBOX --}}
        <div class="toolbox">
            <div class="toolbox__logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/images/VIECTOT.png') }}" alt="">
                </a>
            </div>
            <div class="toolbox__nav">
                <p class="toolbox__nav--toolbox">Danh sách khối</p>
                <p class="toolbox__nav--toolBgr">Danh sách nền</p>
                <p class="toolbox__nav--setBox">Cài đặt  khối</p>
            </div>
            <!-- toolbox -->
            <div class="toolbox__list" style="display: flex;">
                <!-- box 1 -->
                <div class="toolbox__item">
                    <div class="toolbox__item--desc tool-box-1">
                        <img src="{{ asset('storage/images/avt.jpg') }}" alt="" class="toolbox__item--avt">
                        <p class="toolbox__item--boxText">Nguyễn C</p>
                    </div>
                    <div class="toolbox__item--name">Khối mở đầu 1</div>
                </div>

                <!-- box 2 -->
                <div class="toolbox__item">
                    <div class="toolbox__item--desc tool-box-2">
                        <img src="{{ asset('storage/images/avt.jpg') }}" alt="" class="toolbox__item--avt">
                        <p class="toolbox__item--boxText">Nguyễn C</p>
                    </div>
                    <div class="toolbox__item--name">Khối mở đầu 2</div>
                </div>

                <!-- box 3 -->
                <div class="toolbox__item">
                    <div class="toolbox__item--desc tool-box-3">
                        <h3 class="toolbox__item--title">A B C</h3>
                        <p class="toolbox__item--boxText">d e f g h i g k l m n o p q r s t u v w x y z ...</p>
                    </div>
                    <div class="toolbox__item--name">Khối văn bản 1</div>
                </div>

                <!-- box 4 -->
                <div class="toolbox__item">
                    <div class="toolbox__item--desc tool-box-4">
                        <h3 class="toolbox__item--title">A B C</h3>
                        <p class="toolbox__item--boxText">d e f g h i g k l m n o p q r s t u v w x y z ...</p>
                    </div>
                    <div class="toolbox__item--name">Khối văn bản 2</div>
                </div>

                <!-- box 5 -->
                <div class="toolbox__item">
                    <div class="toolbox__item--desc tool-box-5">
                        <div class="toolBox__item--left">
                            <h3 class="toolbox__item--title">A B</h3>
                            <p class="toolbox__item--boxText">d e f g h i g k l m n o ...</p>
                        </div>
                        <div class="toolBox__item--right">
                            <h3 class="toolbox__item--title">A B</h3>
                            <p class="toolbox__item--boxText">d e f g h i g k l m n o ...</p>
                        </div>
                    </div>
                    <div class="toolbox__item--name">Khối văn bản 3</div>
                </div>

                <!-- box 6 -->
                <div class="toolbox__item">
                    <div class="toolbox__item--desc tool-box-6">
                        <div class="toolBox__item--left">
                            <h3 class="toolbox__item--title">A B</h3>
                            <p class="toolbox__item--boxText">d e f g h i g k l m n o ...</p>
                        </div>
                        <div class="toolBox__item--right">
                            <h3 class="toolbox__item--title">A B</h3>
                            <p class="toolbox__item--boxText">d e f g h i g k l m n o ...</p>
                        </div>
                    </div>
                    <div class="toolbox__item--name">Khối văn bản 4</div>
                </div>
            </div>
            <!-- toolbrg -->
            <div class="toolBgr__list" style="display: none;">
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_1.jpg') no-repeat center center / cover;"></div>
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_2.jpg') no-repeat center center / cover;"></div>
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_3.jpg') no-repeat center center / cover;"></div>
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_4.jpg') no-repeat center center / cover;"></div>
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_5.jpg') no-repeat center center / cover;"></div>
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_6.jpg') no-repeat center center / cover;"></div>
                <div class="toolBgr__item" style="background: url('/storage/images/bgr_cv_7.jpg') no-repeat center center / cover;"></div>
            </div>
            <!-- setbox -->
             <div class="setBox__list" style="display: none;">
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="number" name="" id="" class="setbox__input--padding" min="0" max="50">
                        <select name="" id="" class="setbox__input--unit">
                            <option value="cm">cm</option>
                            <option value="%">%</option>
                            <option value="px">px</option>
                            <option value="in">in</option>
                        </select>
                    </div>
                    <div class="setBox__item--name">Khoảng cách bên trên</div>
                </div>
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="number" name="" id="" class="setbox__input--padding" min="0" max="50">
                        <select name="" id="" class="setbox__input--unit">
                            <option value="cm">cm</option>
                            <option value="%">%</option>
                            <option value="px">px</option>
                            <option value="in">in</option>
                        </select>
                    </div>
                    <div class="setBox__item--name">Khoảng cách bên dưới</div>
                </div>
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="number" name="" id="" class="setbox__input--padding" min="0" max="50">
                        <select name="" id="" class="setbox__input--unit">
                            <option value="cm">cm</option>
                            <option value="%">%</option>
                            <option value="px">px</option>
                            <option value="in">in</option>
                        </select>
                    </div>
                    <div class="setBox__item--name">Khoảng cách bên trái</div>
                </div>
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="number" name="" id="" class="setbox__input--padding" min="0" max="50">
                        <select name="" id="" class="setbox__input--unit">
                            <option value="cm">cm</option>
                            <option value="%">%</option>
                            <option value="px">px</option>
                            <option value="in">in</option>
                        </select>
                    </div>
                    <div class="setBox__item--name">Khoảng cách bên phải</div>
                </div>
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="color" name="" id="" class="setBox__input--color">
                    </div>
                    <div class="setBox__item--name">Màu chữ</div>
                </div>
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="number" name="" id="" class="setBox__input--fontSize">
                    </div>
                    <div class="setBox__item--name">Cỡ chữ</div>
                </div>
                <div class="setBox__item">
                    <div class="setBox__item--desc">
                        <input type="number" name="" id="" class="setBox__input--fontWeight" min="1" max="1000">
                    </div>
                    <div class="setBox__item--name">Độ dày</div>
                </div>
             </div>
        </div>

        <section class="makeCv">
            {{-- EDITOR --}}
            <div class="editor">
                <div class="editor__paper" style="background: url('/storage/images/bgr_cv_7.jpg') no-repeat center center / cover; padding: 100px;">
                    {{-- box 1 --}}
                    {{-- <div class="editor-box editor-box-1 box-1">
                        <label for="avatar-input" class="editor__paper--avtLabel">
                            <img id="avatar-preview" src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="editor__paper--avt">
                            <div class="editor__paper--avatar-overlay">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </label>
                        <input type="file" id="avatar-input" accept="image/*" hidden name="avatar">
                        <textarea id="w3review" name="w3review" class="editor__paper--boxText" rows="1">Nguyễn Văn C</textarea>
                    </div> --}}

                    {{-- box 2 --}}
                    <div class="editor-box editor-box-2 box-2">
                        <label for="avatar-input" class="editor__paper--avtLabel">
                            <img id="avatar-preview" src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="editor__paper--avt">
                            <div class="editor__paper--avatar-overlay">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </label>
                        <input type="file" id="avatar-input" accept="image/*" hidden name="avatar">
                        <textarea class="editor__paper--boxText" rows="1">Nguyễn Văn C</textarea>
                    </div>

                    <!-- box 3 -->
                    <div class="editor-box editor-box-3 box-3">
                        <input class="toolbox__item--title" style="width: 100%;">
                        {{-- <textarea name="" id="" cols="30" rows="10"></textarea> --}}
                        <textarea name="" id="ckeditor1"></textarea>
                    </div>

                    
                </div>
            </div>

        </section>
    </div>

    {{-- css for ckeditor --}}
    <style>
        /* .ck-editor {
            position: relative;
        }

        .ck.ck-toolbar {
            position: absolute;
            top: -40px;
            display: none;
        } */
        .cke_top {
            user-select: none;
            position: fixed;
            top: 0;
            z-index: 999;
            left: 100px;
            width: 100%;
            display: none
        }
        .cke_inner {
            position: relative;
            /* margin-bottom: 80px; Để không bị đè */
        }

        .cke_bottom {
            display: none;
            position: absolute;
            width: 100%;
            top: 100%
            left: 0;
            border: 1px solid var(--color-black);
        }
    </style>

    <!-- Alert can't edit -->
    <section class="cantEdit">
        <div class="cantEdit__alert">
            <p style="margin-bottom: 12px;">Màn hình quá nhỏ</p>
            <p>
                <a href="{{ route('home') }}">Về trang chủ</a>
            </p>
        </div>
    </section>
    <script>
        // Nếu chiều ngang màn hình nhỏ hơn 900px thì không cho phép tạo cv
        if (window.innerWidth < 900) {
            const cantEdit = document.querySelector('.cantEdit');
            cantEdit.style.display = 'flex';
        }
    </script>

    <!-- js change background -->
    <script>
        const toolBgrItem = document.querySelectorAll('.toolBgr__item');
        const paper = document.querySelectorAll('.editor__paper');

        toolBgrItem.forEach((item, index) => {
            item.addEventListener('click', () => {
                for(i = 0; i < paper.length; i++) {
                    paper[i].style.background = `url('/storage/images/bgr_cv_${index + 1}.jpg') no-repeat center center / cover`;
                    // paper[i].style.background = "background: url({{ asset('storage/images/bgr_cv_{$index}.jpg') }}) no-repeat center center / cover;";
                }
            })
        });
    </script>

    <!-- js tool menu -->
     <script>
        const toolboxList = document.querySelector('.toolbox__list');
        const toolBgrList = document.querySelector('.toolBgr__list');
        const setBoxList = document.querySelector('.setBox__list');

        const toolboxBtn = document.querySelector('.toolbox__nav--toolbox');
        const toolBgrBtn = document.querySelector('.toolbox__nav--toolBgr');
        const setBoxBtn = document.querySelector('.toolbox__nav--setBox');

        function toggleListMenu(itemList) {
            if(itemList == toolboxList) {
                toolboxList.style.display = 'flex';
                toolBgrList.style.display = 'none';
                setBoxList.style.display = 'none';
            }
            if(itemList == toolBgrList) {
                toolboxList.style.display = 'none';
                toolBgrList.style.display = 'flex';
                setBoxList.style.display = 'none';
            }
            if(itemList == setBoxList) {
                toolboxList.style.display = 'none';
                toolBgrList.style.display = 'none';
                setBoxList.style.display = 'flex';
            }
        }

        toolboxBtn.addEventListener('click', function() {toggleListMenu(toolboxList)});
        toolBgrBtn.addEventListener('click', function() {toggleListMenu(toolBgrList)});
        setBoxBtn.addEventListener('click', function() {toggleListMenu(setBoxList)});
     </script>

    {{-- js change avt --}}
    <script>
        const input = document.getElementById('avatar-input');
        const preview = document.getElementById('avatar-preview');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            }
        });
    </script>

    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> --}}
    <script src="//cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>

    {{-- <script>
        const editors = [];
        const toolbars = [];

        function initEditor(id, index) {
            ClassicEditor
                .create(document.querySelector(`#${id}`))
                .then(editor => {
                    editors[index] = editor;

                    const toolbar = editor.ui.view.toolbar.element;
                    const editable = editor.ui.view.editable.element;

                    // Set toolbar style
                    toolbar.style.position = 'absolute';
                    toolbar.style.top = '-40px';
                    toolbar.style.display = 'none';
                    toolbar.style.zIndex = 100;

                    toolbars[index] = toolbar;

                    // Gắn sự kiện focus để hiện toolbar
                    editable.addEventListener('focus', () => {
                        // Ẩn tất cả toolbar khác
                        toolbars.forEach(tb => {
                            if (tb) tb.style.display = 'none';
                        });

                        // Hiện toolbar hiện tại
                        toolbar.style.display = 'block';
                    });

                    // (Tuỳ chọn) Ẩn toolbar nếu click ra ngoài
                    document.addEventListener('click', function (e) {
                        const isClickInsideEditor = editable.contains(e.target) || toolbar.contains(e.target);
                        if (!isClickInsideEditor) {
                            toolbar.style.display = 'none';
                        }
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }

        initEditor('ckeditor1', 0);
        // initEditor('editor2', 1);
    </script> --}}
    <script>
        const editorInstances = {};
        const editorBottom = {};
        CKEDITOR.replace('ckeditor1', {
            height: 30,
            // removePlugins: 'elementspath',
            // resize_enabled: false,
            // extraPlugins: 'autogrow',
            // autoGrow_minHeight: 30,
            // autoGrow_maxHeight: 300,
            // autoGrow_onStartup: true
            on: {
                instanceReady: function(evt) {
                    const editor = evt.editor;
                    const container = editor.container.$;
                    const toolbar = container.querySelector('.cke_top');
                    const ckBottom = container.querySelector('.cke_bottom');

                    editorInstances['ckeditor1'] = toolbar;
                    editorBottom['ckeditor1'] = ckBottom;

                    editor.on('focus', function () {
                        // Ẩn tất cả toolbars khác
                        for (const key in editorInstances) {
                            if (editorInstances[key]) {
                                editorInstances[key].style.display = 'none';
                            }
                        }
                        toolbar.style.display = 'block';
                        ckBottom.style.display = 'block';
                    });

                    // Ẩn khi click ra ngoài
                    document.addEventListener('click', function(e) {
                        const isInside = container.contains(e.target);
                        if (!isInside) {
                            toolbar.style.display = 'none';
                            ckBottom.style.display = 'none';
                        }
                    });
                }
            }
        });
    </script>

</body>
</html>