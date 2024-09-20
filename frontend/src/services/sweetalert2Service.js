import Swal from "sweetalert2";

// Create a default configuration for SweetAlert2
const toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 1500,
});

const popup = Swal.mixin({
    buttonsStyling: false,
    customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-danger",
    },
});

// Toasts
const showSuccessToast = (message) => {
    toast.fire({
        icon: "success",
        title: "Success!",
        text: message,
    });
};

const showErrorToast = (message) => {
    toast.fire({
        icon: "error",
        title: "Error!",
        text: message,
    });
};

const showWarningToast = (message) => {
    toast.fire({
        icon: "warning",
        title: "Warning!",
        text: message,
    });
};

const showInfoToast = (message) => {
    toast.fire({
        icon: "info",
        title: "Info!",
        text: message,
    });
};

// Popups
const showSuccess = (message) => {
    popup.fire({
        icon: "success",
        title: "Success!",
        text: message,
    });
};

const showConfirm = async (title, text) => {
    const result = await popup.fire({
        title,
        text,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        customClass: {
            confirmButton: "btn btn-primary mr-1",
            cancelButton: "btn btn-danger ml-1",
        },
    });
    return result.isConfirmed;
};

export default {
    showSuccessToast,
    showErrorToast,
    showWarningToast,
    showInfoToast,
    showSuccess,
    showConfirm,
};
