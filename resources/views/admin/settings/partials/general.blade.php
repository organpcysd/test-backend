<div class="row">
    <div class="col-sm-12">
        <div class="mb-3">
            <label class="form-label">ชื่อเว็บไซต์</label>
            <input type="text" class="form-control form-control-sm" name="title" value="{{setting('title')}}">
        </div>

        <div class="mb-3">
            <label class="form-label">เบอร์โทรศัพท์</label>
            <input type="text" class="form-control form-control-sm" name="phone" value="{{setting('phone')}}">
        </div>

        <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="text" class="form-control form-control-sm" name="email" value="{{setting('email')}}">
        </div>

        <div class="mb-3">
            <label class="form-label">ที่อยู่</label>
            <textarea type="text" class="form-control form-control-sm" name="address" >{{setting('address')}}</textarea>
        </div>

    </div>
</div>
