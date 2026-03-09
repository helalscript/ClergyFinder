// Status Change Confirm Alert
function showStatusChangeAlert(id, url, event) {
    // Ensure the event is passed and prevent default behavior
    if (event) {
        event.preventDefault();
    }

    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to update the status?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            statusChange(id, url);
        }
    });
}


// Status Change
function statusChange(id, routeUrl) {
    let url = routeUrl.replace(':id', id);
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(resp) {
            $('#basic_tables').DataTable().ajax.reload();  // Reload DataTable
            if (resp.success === true) {
                flasher.success(resp.message);
            } else if (resp.errors) {
                flasher.error(resp.errors[0]); 
            } else {
                flasher.error(resp.message); 
            }
        },
        error: function(error) {
            flasher.error(error.responseJSON.message);
        }
    });
}

// Delete Confirm Alert
function showDeleteConfirm(id, url, event) {
    if (event) {
        event.preventDefault();
    }
    Swal.fire({
        title: 'Are you sure you want to delete this record?',
        text: 'If you delete this, it will be gone forever.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            deleteItem(id, url);
        }
    });
}

// Delete Item
function deleteItem(id, routeUrl) {
    let url = routeUrl.replace(':id', id);
    $.ajax({
        type: "DELETE",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(resp) {
            $('#basic_tables').DataTable().ajax.reload();  // Reload DataTable
            if (resp.success === true) {
                flasher.success(resp.message);
            } else if (resp.errors) {
                flasher.error(resp.errors[0]);
            } else {
                flasher.error(resp.message);
            }
        },
        error: function(error) {
            flasher.error(error.responseJSON.message);
        }
    });
}
