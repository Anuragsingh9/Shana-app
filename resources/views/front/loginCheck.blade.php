@extends('layouts.front')

@section('content')
@push('script')
<script>
	  @if(Auth::check()==false)
	  jQuery(document).ready(function($) {
    $('#loginFormModal').modal({
            backdrop:'static', show: true
        })
        $('.close').on('click',function(e){
            window.location.href="{{ url('') }}";
        });
       $('#loginFormModal').modal('show');  
    });
    @endif 
</script>

@endsection