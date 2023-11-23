@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important} 
</style>
@endsection

@section('content')


    <section id="PTO">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">PTO Summaries </h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                @foreach ($pto_summaries as $pto_summary)
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading center">{{$pto_summary['title']}}</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>PTO Remaining:</label>
                                </div>
                                <div class="col-xs-8 form-group">
                                    <label>{{$pto_summary['pto_remaining']}}</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>PTO Used:</label>
                                </div>
                                <div class="col-xs-8 form-group">
                                    @foreach ($pto_summary['pto_used'] as $pto_used)
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>{{date('m/d/y',strtotime($pto_used['date']))}}</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>{{$pto_used['hours']}} Hours</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    $('#detail_table').DataTable({"order": [1, 'desc']});
});

</script>

@endsection
