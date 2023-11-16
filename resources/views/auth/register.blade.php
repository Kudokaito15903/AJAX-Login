@extends('layout.app')
@section('title', 'Register')
@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold secondary text-center">Register</h2>
                </div>
                <div class="card-body p-5">
                    <div id="show_success_alert"></div>
                    <form action="#" method="POST" id="register-form">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" id="name" class="form-control rounded-0" placeholder="Fullname">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control rounded-0" placeholder="E-mail">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" id="password" class="form-control rounded-0" placeholder="Password">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="c_password" id="c_password" class="form-control rounded-0" placeholder="Confirm Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3 d-grid">
                            <input type="submit" value="Register" class="btn btn-dark rounded-0" id="register_btn">
                        </div>


                        <div class="text-center text-secondary">
                            <div>
                                Already have an account? <a href="/login" class="text-decoration-none">Login Here</a>
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
        $("#register-form").submit(function(e) {
            e.preventDefault();
            $("#register_btn").val("Please Wait..."); // Thay đổi nội dung của nút submit
            var formData = $(this).serialize(); // Lấy dữ liệu form dưới dạng chuỗi query string
            $.ajax({
                url: "{{ route('auth.register') }}",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $("#show_success_alert").html(showMessage("success", response.messages));
                        $("#register-form")[0].reset();
                        removeValidationClasses("#register-form");
                        $("#register_btn").val("Register");
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 400) {
                    showError("name", xhr.responseJSON.messages.name);
                    showError("email", xhr.responseJSON.messages.email);
                    showError("password", xhr.responseJSON.messages.password);
                    showError("c_password", xhr.responseJSON.messages.c_password);
                    $("#register_btn").val("Register");
                    }
                }
            });
        });
    });
</script>
@endsection
