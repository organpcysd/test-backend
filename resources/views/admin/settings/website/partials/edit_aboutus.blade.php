<div class="mb-3">
    <label class="form-label">รายละเอียดย่อย</label>
    <textarea type="text" class="form-control form-control-sm" name="short_about_us" id="about_us_short" style="height: 100px;">{{ $website->short_about_us }}</textarea>
</div>

<label class="form-label">รายละเอียดหลัก</label>
<textarea type="text" class="form-control form-control-sm summernote" name="about_us" style="height: 100px;">
{{ $website->about_us }}
</textarea>
