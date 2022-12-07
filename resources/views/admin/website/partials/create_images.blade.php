<div class="row">
    <div class="col-sm-12">
        <h3 class="mb-3">โลโก้</h3>
        <div class="row">
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center">
                        <label class="form-label">โลโก้ เว็บไซต์</label><br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_logo" height="200"> <br/>
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
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_favicon" height="200"> <br/>
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
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_og" height="200"> <br/>
                        <span class="text-danger">**รูปภาพขนาด 150x150 px**</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_og" id="img_og" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <h3 class="mb-3">แบนเนอร์</h3>
        <div class="row">
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ เกี่ยวกับเรา</label><br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_aboutus" height="200">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_aboutus" id="img_aboutus" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ สินค้า</label><br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_product" height="200"> <br/>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_product" id="img_product" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ บริการของเรา</label> <br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_service" height="200"> <br/>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_service" id="img_service" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ โปรโมชั่น</label> <br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_promotion" height="200"> <br/>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_promotion" id="img_promotion" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ ข่าวสาร</label> <br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_news" height="200"> <br/>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_news" id="img_news" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ ถาม-ตอบ</label> <br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_faq" height="200"> <br/>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_faq" id="img_faq" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="mb-3">
                    <div class="text-center mb-3">
                        <label class="form-label">แบนเนอร์ ติดต่อเรา</label> <br/>
                        <img src="{{ asset('images/no-image.jpg') }}" id="showimg_contact" height="200"> <br/>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_contact" id="img_contact" accept="image/*" onchange="return fileValidation(this)">
                        <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
