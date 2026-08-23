$(document).ready(function () {

    const waiterChannel = pusher.subscribe('call-waiter-notify-channel-' + window.userBranchId);
    waiterChannel.bind('Modules\\Branch\\Events\\CallWaiterNotify', function (data) {
        if (typeof notificationSound !== 'undefined' && notificationSound) {
            notificationSound.play();
        }
        // Create and show dynamic modal
        if ($('#callWaiterModal-' + data.id).length === 0) {
            const modalHtml = `
                <div class="modal fade text-start call-waiter-dynamic-modal" id="callWaiterModal-${data.id}" tabindex="-1" data-bs-backdrop="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 style="text-align: center; width: 100%;" class="modal-title">طلب نادل جديد</h3>
                            </div>
                            <div class="modal-body">
                                <h2 style="display: block;color:#ea5455;text-align:center;">
                                    طاولة رقم ${data.table}
                                </h2>
                            </div>
                            <div class="modal-footer" style="justify-content: center;">
                                <button type="button" class="btn btn-success waves-effect waves-float waves-light btn-resolve-waiter" data-id="${data.id}">
                                    <span>✔ إتمام الطلب</span>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-float waves-light" data-bs-dismiss="modal">
                                    إغلاق
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            if ($('.disabled-backdrop-ex').length) {
                $('.disabled-backdrop-ex').append(modalHtml);
            } else {
                $('body').append(modalHtml);
            }

            const $modal = $('#callWaiterModal-' + data.id);
            $modal.modal('show');
            $modal.on('hidden.bs.modal', function () {
                $(this).remove();
            });
        }

        if ($('.call-waiter-item-' + data.id).length === 0) {
            const newItem = `
                <div class="list-item d-flex align-items-start call-waiter-item-${data.id}">
                    <div class="me-1">
                        <div class="avatar bg-light-info">
                            <div class="avatar-content"><i class="avatar-icon" data-feather="user"></i></div>
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading"><span class="fw-bolder">طلب نادل لطاولة ${data.table}</span></p>
                    </div>
                    <div class="ms-1">
                        <button class="btn btn-sm btn-icon btn-success btn-resolve-waiter" data-id="${data.id}">
                            ✔
                        </button>
                    </div>
                </div>
            `;
            $('#call-waiter-list').append(newItem);
            let count = parseInt($('.call-waiter-count').first().text()) || 0;
            count++;
            $('.call-waiter-count').text(count).addClass('pulse-point');
        }
    });


    function handleResolveClick(e) {
        e.preventDefault();
        // Prevent dropdown from closing if inside one
        e.stopPropagation();

        const resolveButton = $(this);
        const id = resolveButton.data('id');
        resolveButton.prop('disabled', true);

        const url = window.callWaiterResolveRoute.replace(':id', id);

        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                // Remove from dropdown list
                $('.call-waiter-item-' + id).remove();
                
                // Close and clean up the modal if it exists
                $('#callWaiterModal-' + id).modal('hide');

                // Update counter
                let count = parseInt($('.call-waiter-count').first().text()) || 0;
                count = count > 0 ? count - 1 : 0;
                $('.call-waiter-count').text(count);
                if (count === 0) {
                    $('.call-waiter-count').removeClass('pulse-point');
                }
            },
            error: function (xhr) {
                resolveButton.prop('disabled', false);
                if (typeof toastr !== 'undefined') {
                    toastr.error('حدث خطأ أثناء حل الطلب');
                }
            }
        });
    }

    $(document).on('click', '.btn-resolve-waiter', handleResolveClick);
    $('#call-waiter-list').on('click', '.btn-resolve-waiter', handleResolveClick);

});
