<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-3">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">โลโก้ เว็บไซต์</label><br/>
                        <img src="@if( setting('img_login') ) {{ asset(setting('img_login')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_login" width="30%" height="30%"> <br/>
                        <span class="form-label text-danger">**รูปภาพขนาด 500x500 px** </span> 
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_login" id="img_login" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">โลโก้ (Sidebar)</label><br/>
                        <img src="@if( setting('img_sidebar') ) {{ asset(setting('img_sidebar')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_sidebar" width="30%" height="30%"> <br/>
                        <span class="text-danger">**รูปภาพขนาด 150x150 px** </span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_sidebar" id="img_sidebar" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">Favicon</label><br/>
                        <img src="@if( setting('img_favicon') ) {{ asset(setting('img_favicon')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_favicon" width="30%" height="30%"> <br/>
                        <span class="text-danger">**รูปภาพขนาด 100x100 px**</span> 
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_favicon" id="img_favicon" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">OG Image</label> <br/>
                        <img src="@if( setting('img_og') ) {{ asset(setting('img_og')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_og" width="30%" height="30%"> <br/>
                        <span class="text-danger">**รูปภาพขนาด 150x150 px**</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_og" id="img_og" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
         $('#showimg_favicon').click(function () {
            $('#img_favicon').trigger('click');
        });

        $('#showimg_login').click(function () {
                $('#img_login').trigger('click');
        });

        $('#showimg_sidebar').click(function () {
                $('#img_sidebar').trigger('click');
        });

        $('#showimg_og').click(function () {
                $('#img_og').trigger('click');
        });

    function previewImg(id) {
            const [file] = id.files
            if (file) {
                if(id.id === "img_favicon"){
                    showimg_favicon.src = URL.createObjectURL(file);
                }else if(id.id === "img_login"){
                    showimg_login.src = URL.createObjectURL(file);
                }else if(id.id === "img_sidebar"){
                    showimg_sidebar.src = URL.createObjectURL(file);
                }else if(id.id === "img_og"){
                    showimg_og.src = URL.createObjectURL(file);
                }
            }
    }

    function fileValidation(ele) {
        var fileInput = ele;
        var filePath = fileInput.value;

        // Allowing file type
        var allowedExtensions = /(\.gif|\.png|\.jpeg|\.jpg)$/i;

        if (!allowedExtensions.exec(filePath)) {
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: 'ไฟล์ที่นำเข้าต้องเป็นไฟล์รูปภาพเท่านั้น',
                timer: 2000,
            })
            fileInput.value = '';
            return false;
        } else {
            previewImg(fileInput);
        }
    }
    </script>
@endpush
