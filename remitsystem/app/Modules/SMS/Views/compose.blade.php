@extends('layouts.main')
@section('title', 'SMS')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">SMS</li>
    <li  class="breadcrumb-item active">Compose Message</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Compose SMS Message

            </div>
            <!-- /.box-header -->
            {!!Form::open( array('route' => ['sms.send'],'method'=>'POST','class' => 'form-horizontal form-left','id'=>'sms-submit'))!!}

            @include('SMS::form')

                <div class="card-footer clearfix">
                <input type="submit" id="submit" class="btn btn-primary pull-right" value="Send SMS"/>
            </div>

            {!!Form::close()!!}   </div>
    </div>
@endsection

@section('page-script')
    <script>

        $("#sms").keyup(function(){
            value=$('#sms').val().length;
            $('#message').text(value);

            if (value <= 150) {
                $('#cost').text(1);
            }
            if (value > 150 && value <=300) {
                $('#cost').text(2);
            }

            if(value>300){
                $(".sms_div").addClass('has-error');
                $('#error-message').html('You can only enter up to 300 character.');
                $('#submit').attr('disabled','disabled');
            }
            else{
                $(".sms_div").removeClass('has-error');
                $('#error-message').html('');
                $('#submit').removeAttr('disabled','disabled');
            }
        });
        $(document).ready(function(){
           $("#receiverSelect").change(function(){
               var selectedValue = $("#receiverSelect").val();
               console.log(selectedValue);
               if(selectedValue === "Agents"){
                   $("#agentList").css('display','block');
                   $("#senderList").css('display','none');
               }
               if(selectedValue === "Senders"){
                   $("#senderList").css('display','block');
                   $("#agentList").css('display','none');
               }
           });
        });
    </script>
@stop