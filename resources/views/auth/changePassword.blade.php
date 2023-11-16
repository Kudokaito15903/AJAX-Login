@extends('layout.app')
@section('tittle','Forgot Password')
@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold secondary text-center ">Forgot Password</h2>
                </div>
                <div class="card-body p-5">
                    <div id="forgot_alert">
                    </div>
                    <form action="#" method="POST" id="forgot-form">
                        @csrf
                        <div class="mb-3 tex;t-secondary">
                            <p>Enter your-email address and we will send you a link to reset your password</p>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class='form-control rounded-0' placeholder="E-mail">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 d-grid">
                            <input type="submit" value="Reset Password" class="btn btn-dark rounded-0" id="forgot_btn">
                        </div>
                        <div class="text-center text-secondary">
                            <div>
                                Back to
                                <a href="/login" class="text-decoration-none">Login Here</a>
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
    $(function(){
        $('#forgot-form').submit(function(e){
            e.preventDefault();
            $('#forgot_btn').val('Please Wait...');
            $.ajax({
                url:'{{ route('auth.forgot') }}',
                data:$(this).serialize(),
                method:'post',
                success: function(res) {
                    if ( res.status==200) {
                        $('#forgot_alert').html (showMessage('success',res.messages));
                        $('#forgot_btn').val("Reset Password");
                        removeValditionClasses('#forgot_form');
                        $('#forgot_form')[0].reset();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    if (xhr.status ==400){
                        showError('email',xhr.responseJSON.messages.email),
                        $('#forgot_btn').val("Reset Password");
                    }
                    else if ( xhr.status==404) {
                        $('#forgot_btn').val("Reset Password");
                        $('#forgot_alert').html(showMessage('danger',xhr.responseJSON.messages));
                    }
                }
            });
        });
    });
</script>
@endsection
