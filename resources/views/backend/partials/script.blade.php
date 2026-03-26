    <!-- Link Of JS File -->
    <script src="{{ asset('backend/admin/assets') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/sidebar-menu.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/dragdrop.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/rangeslider.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/sweetalert.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/quill.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/data-table.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/prism.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/clipboard.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/feather.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/simplebar.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/apexcharts.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/echarts.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/fullcalendar.main.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/jsvectormap.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/world-merc.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/moment.min.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/lightpick.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/custom/apexcharts.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/custom/echarts.js"></script>
    <script src="{{ asset('backend/admin/assets') }}/js/custom/custom.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>

        {{-- new add --}}
        {{-- <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script> --}}
        <script src="{{ asset('backend') }}/admin/assets/datatables/data-tables.min.js"></script>
        <!--buttons dataTables-->
        <script src="{{ asset('backend') }}/admin/assets/datatables/datatables.buttons.min.js"></script>
        <script src="{{ asset('backend') }}/admin/assets/datatables/jszip.min.js"></script>
        <script src="{{ asset('backend') }}/admin/assets/datatables/pdfmake.min.js"></script>
        <script src="{{ asset('backend') }}/admin/assets/datatables/buttons.html5.min.js"></script>
        <script src="{{ asset('backend') }}/admin/assets/datatables/buttons.print.min.js"></script>
  
<!-- for live notification -->
<script>
    function deleteNotification(id) {
        $.ajax({
            type: "DELETE",
            url: "{{ route('notification.delete', ':id') }}".replace(':id', id),
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            success: function(resp) {
                flasher.success(resp.message);
                $("#notification_" + id).remove()
                let notificationCount = parseInt($("#notification-count").text());
                if (notificationCount > 0) {
                    $("#notification-count").text(notificationCount - 1);
                }
            },
            error: function(error) {
                flasher.error(error.responseJSON.message);
            }
        });
    }
    function deleteAllNotification() {
        $.ajax({
            type: "DELETE",
            url: "{{ route('notification.deleteall') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            success: function(resp) {
                flasher.success(resp.message);
                $('#notification-list').empty();
                let notificationCount = parseInt($("#notification-count").text());
                if (notificationCount > 0) {
                    $("#notification-count").text(0);
                }
            },
            error: function(error) {
                flasher.error(error.responseJSON.message);
            }
        });
    }
    

    function markAllRead() {
        $.ajax({
            type: "POST",
            url: "{{ route('notification.read') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            success: function(resp) {
                // flasher.success(resp.message);
                $('.notification-menu').removeClass('unseen');
                // $("#notification-indicator").remove()
                let notificationCount = parseInt($("#notification-count").text());
                if (notificationCount >= 0) {
                    $("#notification-count").text(0);
                }
            },
            error: function(error) {
                // flasher.error(error.responseJSON.message);
                console.log(error.responseJSON.message)
            }
        });
    }

    //realtime notification fetch
    document.addEventListener('DOMContentLoaded', function() {


        Echo.private('App.Models.User.' + {{ auth()->id() }})
            .notification((notification) => {
                // console.log(notification);
                let notificationCount = parseInt($("#notification-count").text());
                if (notificationCount >= 0) {
                    $("#notification-count").text(notificationCount + 1);
                }
                // $('#notification-list').empty();
                $('#notification-list').append(`
                    <div class="notification-menu unseen" id="notification_${notification.id}" style="background-color: #00fff3;">
                        <a href="${notification.url}" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded bg-light" style="width: 40px; height: 40px; overflow: hidden;">
                                            <img src="${notification.thumbnail ?? 'default-thumbnail.jpg'}" 
                                                alt="Notification Thumbnail" 
                                                class="img-fluid rounded">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p>${notification.title ?? 'Untitled Notification'}</p>
                                    <span style="background-color: red;border-radius: 50%;">New</span>
                                    <span class="fs-13">${moment(notification.created_at).fromNow()}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                `);
                flasher.info(notification.message, 'Notification');
            });
    })
</script>

    @stack('scripts')
