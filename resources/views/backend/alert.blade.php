@if(session('error'))
<script>
    showToast('error', {!! json_encode(session('error')) !!});
</script>
@endif

@if(session('success'))
<script>
    showToast('success',{!! json_encode(session('success')) !!})
</script>
@endif
