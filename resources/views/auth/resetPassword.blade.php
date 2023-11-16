@extends('layout.app')
@section('tittle','Reset Password')
@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold secondary text-center">Reset Password</h2>
                </div>
                <div class="card-body p-5">
                    <form action="#" method="POST" id="reset-form">
                        @csrf
                        <input type='hidden' name='email' value="{{ $email }}">
                        <input type="hidden" name='token' value= {{ $token }}>
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class='form-control rounded-0' placeholder="E-mail" value={{ $email }} disabled>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="new_password" id="new_password" class='form-control rounded-0' placeholder="New Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="confirm_password" id="confirm_password" class='form-control rounded-0' placeholder="Confirm New Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3 d-grid">
                            <input type="submit" value="Update Password" class="btn btn-dark rounded-0" id="reset_btn">
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
   $(function() {
    $('#reset-form').submit(function(e) {
        e.preventDefault();
        $('#reset_btn').val('Please Wait...');
        $.ajax({
            url: '{{ route('auth.reset') }}',
            method: "PUT",
            data: $(this).serialize(),
            success: function(res) {
                console.log(res);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            },
            complete: function() {
                $('#reset_btn').val('Reset Password');
            }
        });
    });
});
</script>
@endsection
