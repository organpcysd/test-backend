<div class="mb-3">
    <label class="form-label">รายละเอียดย่อย</label>
    <textarea type="text" class="form-control form-control-sm" name="aboutus_short" id="about_us_short" style="height: 100px;">{{setting('aboutus_short')}}</textarea>
</div>

<label class="form-label">รายละเอียดหลัก</label>
<textarea type="text" class="form-control form-control-sm" name="aboutus" id="about_us" style="height: 100px;">
    {{setting('aboutus')}}
</textarea>

@push('js')
    <script>
        // text-editor
        tinymce.init({
            selector: '#about_us',
            plugins: 'responsivefilemanager print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons',
                menubar: 'file edit view insert format tools table help',
                toolbar: 'responsivefilemanager | undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
                height: 350,
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                external_filemanager_path: "{{asset('vendor/responsive_filemanager/filemanager')}}/",
                filemanager_title: "File manger",
                external_plugins: {
                    "responsivefilemanager": "{{asset('vendor/responsive_filemanager/tinymce/plugins/responsivefilemanager/plugin.min.js')}}",
                    "filemanager": "{{asset('vendor/responsive_filemanager/filemanager/plugin.min.js')}}"
                },
        });
    </script>
@endpush
