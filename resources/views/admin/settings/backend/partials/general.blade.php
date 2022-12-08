<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-3">ตั้งค่าทั่วไป</h3>

        <div class="mb-3">
            <label class="form-label">ไตเติ้ลเว็บไซต์</label>
            <input type="text" class="form-control form-control-sm" name="title" value="{{ setting('title') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">รายละเอียด</label> <small class="text-danger">(OG Description)</small>
            <textarea type="text" class="form-control form-control-sm" name="detail">{{ setting('detail') }}</textarea>
        </div>

    </div>
    <div class="col-sm-12">
        <h3 class="mb-3">รูปภาพ</h3>
        <div class="row">
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">โลโก้</label> <br/>
                        <img src="@if( setting('img_logo') ) {{ asset(setting('img_logo')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_logo" style="cursor: pointer; max-width: 100%; object-fit: contain;" height="150"> <br/>
                        <span class="form-label text-danger">**รูปภาพขนาด 500x500 px** </span>
                    </div>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_logo" id="img_logo" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">Favicon</label><br/>
                        <img src="@if( setting('img_favicon') ) {{ asset(setting('img_favicon')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_favicon" style="cursor: pointer; max-width: 100%; object-fit: contain;" height="150"> <br/>
                        <span class="text-danger">**รูปภาพขนาด 100x100 px**</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_favicon" id="img_favicon" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">OG Image</label> <br/>
                        <img src="@if( setting('img_og') ) {{ asset(setting('img_og')) }} @else {{ asset('images/no-image.jpg') }} @endif" id="showimg_og" style="cursor: pointer; max-width: 100%; object-fit: contain;" height="150"> <br/>
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

    $('#showimg_logo').click(function () {
            $('#img_logo').trigger('click');
    });

    $('#showimg_og').click(function () {
            $('#img_og').trigger('click');
    });

    function previewImg(id) {
            const [file] = id.files
            if (file) {
                if(id.id === "img_favicon"){
                    showimg_favicon.src = URL.createObjectURL(file);
                }else if(id.id === "img_logo"){
                    showimg_logo.src = URL.createObjectURL(file);
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
