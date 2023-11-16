function showError(field, message) {
    if (!message) {
        $(`#${field}`).addClass("is-valid");
        $(`#${field}`).removeClass("is-invalid");
        $(`#${field}`).siblings(".invalid-feedback").text("");
    } else {
        $(`#${field}`).addClass("is-invalid");
        $(`#${field}`).removeClass("is-valid");
        $(`#${field}`).siblings(".invalid-feedback").text(message);
    }
}

function removeValidationClasses(form) {
    $(form).each(function() {
        $(this).find(":input").removeClass("is-valid is-invalid");
    });
}

function showMessage(type, message) {
    return `<div class="alert  alert-${type}  alert-dismissible fade show" role="alert">
        <strong>${message}</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
}
