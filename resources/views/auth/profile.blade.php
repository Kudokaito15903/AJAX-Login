@extends('layout.app')
@section('title', 'Profile')
@section('content')
<div class="container">
    <div class="row my-5">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="text-secondary fw-bold">
                        User Profile
                    </h2>
                    <a href="{{ route('auth.logout') }}" class="btn btn-dark">Logout</a>
                </div>
                <div class="card-body p-5">
                    <div id="profile_alert">

                    </div>
                    <div class="row">
                        <div class="col-lg-4 px-5 text-center" style="border-right: 1px solid #999;">
                            @if ($userInfo->picture)
                            <img src="storage/img/{{ $userInfo->picture }}" id="image_preview" class="img-fluid rounded-circle img-thumbnail" width="200">
                            @else
                            <img src="{{ asset('img/5856.jpg') }}" id="image_preview" class="img-fluid rounded-circle img-thumbnail" width="200">
                            @endif
                            <div>
                                <label for="picture">Change Profile Picture</label>
                                <input type="file" name="picture" id="picture" class="form-control rounded-pill">
                            </div>
                        </div>
                        <input type="hidden" name="user_id" id="user_id" value="{{ $userInfo->id }}">
                        <div class="col-lg-8 px-5">
                            <form id="profile_form">
                                @csrf
                                <div class="my-2">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control rounded-0" value="{{ $userInfo->name }}">
                                </div>

                                <div class="my-2">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control rounded-0" value="{{ $userInfo->email }}">
                                </div>

                                <div class="row">
                                    <div class="col-lg">
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-select rounded-0">
                                            <option value="" selected disabled>- Select -</option>
                                            <option value="Male" {{ $userInfo->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $userInfo->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>

                                    <div class="col-lg">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" name="dob" id="dob" class="form-control rounded-0" value="{{ $userInfo->dob }}">
                                    </div>
                                </div>

                                <div class="my-2">
                                    <label for="phone">Phone</label>
                                    <input type="tel" name="phone" id="phone" class="form-control rounded-0" value="{{ $userInfo->phone }}">
                                </div>
                                <div class="my-2">
                                    <input type="submit" value="Update Profile" class="btn btn-primary rounded-0 float-end" id="profile_btn">
                                </div>

                                <div class="my-2">
                                    <button type="button" class="btn btn-danger rounded-0 float-start" id="delete_btn">Delete Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $("#picture").change(function(e) {
        const file = e.target.files[0];
        let url = window.URL.createObjectURL(file);
        $("#image_preview").attr('src', url);
        let fd = new FormData();
        fd.append('picture', file);
        fd.append('user_id', $('#user_id').val());
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: '{{ route('profile.image') }}',
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    $('#profile_alert').html(showMessage('success', response.message));
                   $('#picture').val('');
                }
            }
        });
    });

    $('#profile_form').submit(function(e) {
        e.preventDefault();
        let id = $('#user_id').val();
        $('#profile_btn').val('Updating...');
        $.ajax({
            url: '{{ route('profile.update') }}',
            method: 'put',
            data: $(this).serialize() + '&id=' + id,
            dataType: 'json',
            success: function(res) {
                if (res.status == 200) {
                    $('#profile_alert').html(showMessage('success', res.message));
                    $('#profile_btn').val('Update Profile');
                }
            }
        });
    });

    $("#delete_btn").click(function() {
    let id = $('#user_id').val();
    if (confirm('Are you sure you want to delete this profile?')) {
        $.ajax({
            url: '{{ route('auth.delete') }}',
            method: 'post',
            data: {
                _method: 'DELETE',
                id: id,
                _token: '{{ csrf_token() }}' // Thêm trường _token với giá trị CSRF token
            },
            dataType: 'json',
            success: function(res) {

            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }
    });
});
function showMessage(type, message) {
    return '<div class="alert alert-' + type + '">' + message + '</div>';
}
</script>
@endsection
