<div class="card card-info card-outline">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link text-cyan active" id="th-tab" data-toggle="tab" href="#th" role="tab" aria-controls="th" aria-selected="true">ภาษาไทย</a>
            </li>
            @if(Auth::user()->hasPermissionTo('english-language'))
            <li class="nav-item">
                <a class="nav-link text-cyan" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">ภาษาอังกฤษ</a>
            </li>
            @endif
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="th" role="tabpanel" aria-labelledby="th-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">รายละเอียดย่อย</label>
                            <textarea type="text" class="form-control form-control-sm" name="short_about_us_th" id="about_us_short" style="height: 100px;"></textarea>
                        </div>

                        <label class="form-label">รายละเอียดหลัก</label>
                        <textarea type="text" class="form-control form-control-sm summernote" name="about_us_th" style="height: 100px;">
                        </textarea>

                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">รายละเอียดย่อย</label>
                            <textarea type="text" class="form-control form-control-sm" name="short_about_us_en" id="about_us_short" style="height: 100px;"></textarea>
                        </div>

                        <label class="form-label">รายละเอียดหลัก</label>
                        <textarea type="text" class="form-control form-control-sm summernote" name="about_us_en" style="height: 100px;">
                        </textarea>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
