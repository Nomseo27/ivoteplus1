@extends('master')
@section('title')
    {{"Add Member"}}
@stop   
@section('style')
<link href="{{ URL::asset('assets/css/style.css') }}" type="text/css" rel="stylesheet">
@stop
@section('content')
<!-- START CONTENT -->
    @section('title-page')
        {{"Add Member"}}
    @stop  
    <!--start container-->
    <div class="col-md-12"> 
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
            {!! implode('', $errors->all(
                '<li>:message</li>'
            )) !!}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            {{ Session::get('message') }}
        </div>
        @endif
    </div>
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{$formTitle}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php echo "$form"; ?>
            </div>
            
        </div>
    </div>
@stop 

@section('script')

@stop