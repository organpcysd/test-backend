<div class="row">

    <div class="col-sm-6">
        <h3 class="mb-3">ข้อมูลทั่วไป</h3>

        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">ชื่อเว็บไซต์</label>
                    <input type="text" class="form-control form-control-sm" name="name" value="{{ $website->name }}" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">ชื่อโดเมน</label>
                    <input type="text" class="form-control form-control-sm" name="domain_name" value="{{ $website->domain_name }}" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">ชื่อไตเติ้ลเว็บไซต์</label>
            <input type="text" class="form-control form-control-sm" name="title" value="{{ $website->title }}">
        </div>

        <div class="mb-3">
            <label class="form-label">ที่อยู่</label>
            <textarea type="text" class="form-control form-control-sm" name="address">{{ $website->address }}</textarea>
        </div>

        <hr/>
        <h3 class="mb-3">ข้อมูลติดต่อ</h3>

        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">เบอร์โทรศัพท์ 1</label>
                    <input type="tel" class="form-control form-control-sm" name="phone1" value="{{ $website->phone1 }}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">เบอร์โทรศัพท์ 2</label>
                    <input type="tel" class="form-control form-control-sm" name="phone2" value="{{ $website->phone2 }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">เบอร์บริษัท</label>
                    <input type="tel" class="form-control form-control-sm" name="company_number" value="{{ $website->company_number }}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">แฟกซ์</label>
                    <input type="tel" class="form-control form-control-sm" name="fax" value="{{ $website->fax }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">อีเมล 1</label>
                    <input type="text" class="form-control form-control-sm" name="email1" value="{{ $website->email1 }}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">อีเมล 2</label>
                    <input type="text" class="form-control form-control-sm" name="email2" value="{{ $website->email2 }}">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Goole Map</label>
            <input type="text" class="form-control form-control-sm" name="google_map" value="{{ $website->google_map }}">
        </div>

    </div>
    <div class="col-sm-6">
        <h3 class="mb-3">โซเชียลมีเดีย</h3>
        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">Line</label>
                    <input type="text" class="form-control form-control-sm" name="line" value="{{ $website->line }}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">Line Token</label> <small class="text-danger">(สำหรับ line notify)</small>
                    <input type="text" class="form-control form-control-sm" name="line_token" value="{{ $website->line_token }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">Facebook</label> <small class="text-danger">(แฟนเพจ)</small>
                    <input type="text" class="form-control form-control-sm" name="facebook" value="{{ $website->facebook }}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">Messenger</label> <small class="text-danger">(facebook messenger)</small>
                    <input type="text" class="form-control form-control-sm" name="messenger" value="{{ $website->messenger }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">Youtube</label>
                    <input type="text" class="form-control form-control-sm" name="youtube" value="{{ $website->youtube }}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label">Youtube Embed</label>
                    <input type="text" class="form-control form-control-sm" name="youtube_embed" value="{{ $website->youtube_embed }}">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Instagram</label>
            <input type="text" class="form-control form-control-sm" name="instagram" value="{{ $website->instagram }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Twitter</label>
            <input type="text" class="form-control form-control-sm" name="twitter" value="{{ $website->twitter }}">
        </div>

        <div class="mb-3">
            <label class="form-label">LinkedIn</label>
            <input type="text" class="form-control form-control-sm" name="linkedin" value="{{ $website->linkedin }}">
        </div>

        <div class="mb-3">
            <label class="form-label">WhatsApp</label>
            <input type="text" class="form-control form-control-sm" name="whatsapp" value="{{ $website->whatsapp }}">
        </div>
    </div>
</div>
