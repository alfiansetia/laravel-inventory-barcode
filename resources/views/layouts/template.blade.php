<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title }}</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('kai/assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('kai/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ['{{ asset('kai/assets/css/fonts.min.css') }}']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('kai/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kai/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kai/assets/css/kaiadmin.min.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    @stack('css')
</head>

<body>
    <div class="wrapper">
        @include('components.sidebar')

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ asset('kai/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                                class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                @include('components.navbar')
            </div>

            <div class="container">
                <div class="page-inner">
                    @include('components.breadcumb')

                    @yield('contents')
                </div>
            </div>

            @include('components.footer')
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('kai/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kai/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kai/assets/js/core/bootstrap.min.js') }}"></script>

    <script src="{{ asset('kai/lib/bs-custom-file-input-1.3.4/dist/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ asset('kai/lib/blockui-2.70/jquery.blockUI.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('kai/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    {{-- <script src="{{ asset('kai/assets/js/plugin/chart.js/chart.min.js') }}"></script> --}}

    <!-- jQuery Sparkline -->
    {{-- <script src="{{ asset('kai/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script> --}}

    <!-- Chart Circle -->
    {{-- <script src="{{ asset('kai/assets/js/plugin/chart-circle/circles.min.js') }}"></script> --}}

    <!-- Datatables -->
    {{-- <script src="{{ asset('kai/assets/js/plugin/datatables/datatables.min.js') }}"></script> --}}

    <!-- Bootstrap Notify -->
    <script src="{{ asset('kai/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    {{-- <script src="{{ asset('kai/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('kai/assets/js/plugin/jsvectormap/world.js') }}"></script> --}}

    <!-- Google Maps Plugin -->
    {{-- <script src="{{ asset('kai/assets/js/plugin/gmaps/gmaps.js') }}"></script> --}}

    <!-- Sweet Alert -->
    <script src="{{ asset('kai/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('kai/assets/js/kaiadmin.min.js') }}"></script>
    <script>
        function ajax_setup() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    // 'Content-Type': 'application/x-www-form-urlencoded'
                }
            });
        }

        function logout_() {

            swal({
                    title: 'Are you sure?',
                    text: 'Logout?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // $('#form_logout').submit();
                        window.location.href = "{{ url('logout') }}"
                    }
                });
        }

        function show_toast(type = 'success', message = '') {
            if (type == 'success') {
                $.notify({
                    icon: 'fa fa-bell',
                    message: message
                }, {
                    type: 'success',
                    z_index: 9999
                });
            } else if (type == 'warning') {
                $.notify({
                    icon: 'fa fa-bell',
                    message: message
                }, {
                    type: 'warning',
                    z_index: 9999
                });
            } else {
                $.notify({
                    icon: 'fa fa-bell',
                    message: message
                }, {
                    type: 'danger',
                    z_index: 9999
                });
            }
        }

        function send_ajax(formID, method) {
            ajax_setup()
            let data = new FormData($('#' + formID)[0])
            data.append('_method', method)
            $.ajax({
                url: $('#' + formID).attr('action'),
                method: 'POST',
                processData: false,
                contentType: false,
                data: data,
                // data: $('#' + formID).serialize(),
                success: function(result) {
                    show_toast('success', result.message || 'Success!')
                    table.ajax.reload()
                    $('#modal_form').modal('hide')
                },
                error: function(xhr, status, error) {
                    handleResponseForm(xhr, 'form')
                }
            })
        }

        function send_delete(url) {
            swal({
                    title: 'Are you sure?',
                    text: 'Delete Data?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        ajax_setup()
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(result) {
                                show_toast('success', result.message || 'Success!')
                                table.ajax.reload()
                            },
                            error: function(xhr, status, error) {
                                show_toast('error', xhr.responseJSON.message || 'Server Error!')
                            }
                        })
                    }
                });
        }

        function handleResponseForm(jqXHR, formID) {
            let message = jqXHR.responseJSON.message
            if (jqXHR.status != 422) {
                show_toast('error', message)
            } else {
                let errors = jqXHR.responseJSON.errors || {};
                let errorKeys = Object.keys(errors);

                for (let i = 0; i < errorKeys.length; i++) {
                    let fieldName = errorKeys[i];
                    let errorMessage = errors[fieldName][0];
                    $('#' + formID + ' [name="' + fieldName + '"]').addClass('is-invalid');
                    $('#' + formID + ' [name="' + fieldName + '"]').removeClass('is-valid');
                    $('#' + formID + ' .err_' + fieldName).text(errorMessage).show();
                }
            }
        }

        function clear_validate(formID) {
            let form = $('#' + formID);
            form.find('.error.invalid-feedback').each(function() {
                $(this).hide().text('');
            });
            form.find('input.is-invalid, textarea.is-invalid, select.is-invalid').each(function() {
                $(this).removeClass('is-invalid');
                $(this).removeClass('is-valid');
            });
        }

        $(document).ready(function() {
            $(document).ajaxStart(function() {
                $('button').prop('disabled', true)
                $.blockUI({
                    message: '<img src="{{ asset('kai/assets/img/loading.gif') }}" width="20px" height="20px" /> Just a moment...',
                    baseZ: 2000,
                });
            }).ajaxStop(function() {
                $('button').prop('disabled', false)
                $.unblockUI()
            });

            bsCustomFileInput.init()

        })
    </script>

    @stack('js')
</body>

</html>
