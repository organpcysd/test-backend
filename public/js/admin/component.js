function confirmdelete(url){
    Swal.fire({
        title: 'ต้องการลบใช่หรือไม่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    _token: CSRF_TOKEN
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true) {
                        Swal.fire({
                            title: response.msg,
                            icon: 'success',
                            timeout: 2000,
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            title: response.msg,
                            icon: 'error',
                            timeout: 2000,
                        });
                    }
                }
            });

        }
    });
}

function publish(url) {
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if (response.status === true) {
                    Swal.fire({
                        position: 'top-right',
                        title: response.msg,
                        icon: 'success',
                        timer: 1000,
                        toast: true,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
            } else {
                Swal.fire({
                    position: 'top-right',
                    title: response.msg,
                    icon: 'error',
                    timer: 1000,
                    toast: true,
                    showCancelButton: false,
                    showConfirmButton: false
                });
            }
        }
    });
}

function sort(ele,url){
    var frmdata = {
        'data': ele.value
    };
    $.ajax({
        type: 'get',
        url: url,
        data: frmdata,
        success: function (response){
            if (response.status === true) {
                Swal.fire({
                    position: 'top-right',
                    icon: 'success',
                    title: response.message,
                    toast: true,
                    timer: 1000,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            } else {
                Swal.fire({
                    position: 'top-right',
                    icon: 'error',
                    title: response.message,
                    toast: true,
                    timer: 1000,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            }
        }
    })
}

function promotion(url) {
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if (response.status === true) {
                    Swal.fire({
                        position: 'top-right',
                        title: response.msg,
                        icon: 'success',
                        timer: 1000,
                        toast: true,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
            } else {
                Swal.fire({
                    position: 'top-right',
                    title: response.msg,
                    icon: 'error',
                    timer: 1000,
                    toast: true,
                    showCancelButton: false,
                    showConfirmButton: false
                });
            }
        }
    });
}

function bestsell(url) {
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if (response.status === true) {
                    Swal.fire({
                        position: 'top-right',
                        title: response.msg,
                        icon: 'success',
                        timer: 1000,
                        toast: true,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
            } else {
                Swal.fire({
                    position: 'top-right',
                    title: response.msg,
                    icon: 'error',
                    timer: 1000,
                    toast: true,
                    showCancelButton: false,
                    showConfirmButton: false
                });
            }
        }
    });
}

function recommended(url) {
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if (response.status === true) {
                    Swal.fire({
                        position: 'top-right',
                        title: response.msg,
                        icon: 'success',
                        timer: 1000,
                        toast: true,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
            } else {
                Swal.fire({
                    position: 'top-right',
                    title: response.msg,
                    icon: 'error',
                    timer: 1000,
                    toast: true,
                    showCancelButton: false,
                    showConfirmButton: false
                });
            }
        }
    });
}
