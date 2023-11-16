@extends('layout.app')
@section('tittle','Login')
@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold secondary text-center">Login</h2>
                </div>
                <div class="card-body p-5">
                    <div id="login_alert"></div>
                    <form action="#" method="POST" id="login-form">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class='form-control rounded-0' placeholder="E-mail">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" id="password" class='form-control rounded-0' placeholder="Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <a href="/forgot" class="text-decoration-none">Forgot Password?</a>
                        </div>
                        <div class="mb-3 d-grid">
                            <input type="submit" value="Login" class="btn btn-dark rounded-0" id="login_btn">

                        </div>
                        <div class="text-center text-secondary">
                            <div>
                                Don't have an acccount
                                <a href="/register" class="text-decoration-none">Register Here</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function() {
    $("#login-form").submit(function(e) {
        e.preventDefault();
        $("#login_btn").val("Please Wait..."); // Thay đổi nội dung của nút submit
        var formData = $(this).serialize(); // Lấy dữ liệu form dưới dạng chuỗi query string
        $.ajax({
            url: "{{ route('auth.login') }}",
            method: 'post',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.status==200 && res.messages=="Login success") {
                    window.location = '{{ route('profile') }}';
                }
            },
            error: function(xhr, status, error) {
                if( xhr.status == 400) {
                    showError("password", xhr.responseJSON.messages.password);
                    showError("email", xhr.responseJSON.messages.email);
                    $('#login_btn').val('Login')
                }
                else if ( xhr.status==401){
                    $("#login_alert").html(showMessage('danger',xhr.responseJSON.messages));
                    $('#login_btn').val('Login')
                }
            }
        });
    });
});
</script>
@endsection
