{{--
    Notice component: the single trigger for session-flash toasts (sweetalert2 +
    the global alertJs() helper). The Livewire cart listeners in layout/block_menu
    reuse the same alertJs().

    sweetalert2 is loaded from a package-owned path (public/vendor/gp247-front/js),
    not from the template, so no template has to carry its own copy.
--}}
<script src="{{ gp247_file('GP247/Core/js/sweetalert2.all.min.js') }}"></script>
<script>
    function alertJs(type = 'error', msg = '') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-soft',
            },
        });
        // WHY 'icon' not 'type': SweetAlert2 renamed this option years ago
        // (removed in v10+); the bundled v11.17.2 silently ignores 'type',
        // so the toast rendered with no icon at all until this was fixed.
        Toast.fire({
            icon: type,
            title: msg
        })
    }
</script>

@if (session('success'))
<script type="text/javascript">
    alertJs('success', @js(e((string) session('success'), false)));
</script>
@endif

@if (session('error'))
<script type="text/javascript">
    alertJs('error', @js(e((string) session('error'), false)));
</script>
@endif

@if (session('warning'))
<script type="text/javascript">
    alertJs('warning', @js(e((string) session('warning'), false)));
</script>
@endif
