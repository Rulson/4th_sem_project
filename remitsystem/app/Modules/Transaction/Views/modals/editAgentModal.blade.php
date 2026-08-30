
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Edit Agent</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="{{route('transaction.agent.saveEdit',[$agentTransaction->id,$agentTransaction->agentId])}}" method="post">
    {{csrf_field()}}


    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Order Details


                    </div>

                    <div class="card-body">
                        <div class="info-box no-box-shadow b-0 ">
                            <div class="info-box-content" style="margin-left: 0">
                                <ul class="list-group" style="margin: 0; border: 0">
                                    <li class="list-group-item b-0">
                                        Sending Amount to Nepal: <span class="pull-right"
                                                                       id="sendingamount">AUD {{$agentTransaction->sendingAmount}}</span>
                                    </li>
                                    <li class="list-group-item  b-0">
                                        Service Charge: <span style="margin-left: 540px;">AUD</span> <input style="width: 80px;" type="number" min="0" name="service_charge" value="{{$agentTransaction->serviceCharge}}" class="pull-right" id="serviceCharg">
                                    </li>

                                    <li class="list-group-item b-0">
                                        <h4><b>Total Payment: </b> <span class="pull-right"
                                                                         id="totalpayment"><b>AUD </b></span>
                                        </h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                       Agent Commissions
                    </div>
                    <!-- /.box-header -->
                    <div class="card-body">
                        <div class="col-md-12">

                            <fieldset class="form-group ">
                                <label>Agent Service Charge (*)</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">AUD</span>
                        </span>
                                    <input type="number" min="0" step="0.001" name="service_charge_agent"
                                           class="form-control" id="serviceCharge"
                                           placeholder="Enter Agent Service Charge">
                                </div>
                            </fieldset>
                            <div class="form-group" id="agent_rate_div">
                                <label>Agent Rate (*)</label>
                                <input type="number" min="0" step="0.001" name="rate_agent" value="{{$agentTransaction->agentRate}}" class="form-control"
                                       id="agentRate"
                                       placeholder="Enter Agent Rate">
                            </div>
                            <div class="form-group" id="agent_service_charge_commission_div">
                                <label>Agent Service Charge Commission</label>
                                <input type="text" name="agent_service_charge_commission" class="form-control"
                                       id="agentServiceChargeCommission"
                                       placeholder="" readonly>
                            </div>
                            <div class="form-group" id="agent_rate_commission_div">
                                <label>Agent Rate Commission</label>
                                <input type="text" name="agent_rate_commission" class="form-control"
                                       id="agentRateCommission"
                                       placeholder="" readonly>
                            </div>
                            <fieldset class="form-group ">
                                <label>Agent Total Commission (*)</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">AUD</span>
                        </span>
                                    <input type="number" min="0" step="0.001" name="commission_agent_total"
                                           class="form-control" id="totalCommission"
                                           placeholder="Enter Total Commission" readonly>
                                </div>
                            </fieldset>


                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@php

    $commission = App\Modules\Agent\Models\Agent::leftJoin('agent_exchange_rate','agent_exchange_rate.id','=','agents.agent_exchange_rate_id')
    ->where('agents.id',$agentTransaction->agentId)->first();
    $minCommission = $commission->less_than_service_charge;
    $maxCommission= $commission->more_than_service_charge;
    $threshold =$commission->sending_amount_threshold;
@endphp

<script>
    var sendingAmount = parseFloat("{{$agentTransaction->sendingAmount}}");
    var serviceCharge = parseFloat("{{$agentTransaction->serviceCharge}}");
    var paymentAmt = parseFloat("{{$agentTransaction->paymentAmount}}");
    var threshold = parseFloat("{{$threshold}}");
    var minCommission = parseFloat("{{$minCommission}}");
    var maxCommission = parseFloat("{{$maxCommission}}");
    var agentServiceCommission = 0;

    var totalPayment = (sendingAmount + serviceCharge).toFixed(2);
    $(document).ready(function(){

        $("#totalpayment").html('AUD'+' '+totalPayment);
        agentRateCalculations();
        $("input[name='service_charge'],input[name='rate_agent']").on('keyup', function () {
            servicecharge1=  $("input[name='service_charge']").val();

            totalPayment = sendingAmount + parseFloat(servicecharge1);
            $("#totalpayment").text('AUD' + ' ' + totalPayment);
            agentRateCalculations();
        });
        $("input[name='service_charge_agent']").on('keyup', function () {
            servicecharge1=  $("input[name='service_charge']").val();
            servicechargeagent=  $("input[name='service_charge_agent']").val();
            commission = (servicecharge1 - parseFloat(servicechargeagent)).toFixed(2);
            $("#agentServiceChargeCommission").val(commission);
commissionagenttotal= (parseFloat($("#agentServiceChargeCommission").val())+parseFloat($("input[name='agent_rate_commission']").val())).toFixed(2);

$("input[name='commission_agent_total']").val(commissionagenttotal);
        });

    });
    function agentRateCalculations(){

        var agentRate =paymentAmt / $("input[name='rate_agent']").val();

        if (agentRate == '0' || agentRate == 'NaN' || agentRate == 'Infinity') {
            var commission = 0;
        }
        else {
            var commissionAmount = parseFloat(sendingAmount) - agentRate;
            commission = parseFloat(commissionAmount).toFixed(2);

        }
        $("input[name='agent_rate_commission']").val(commission);
        var servicecharge = $("input[name='service_charge']").val();
        if (sendingAmount < threshold ) {
            var agentServiceCharge =  minCommission;
            agentServiceCommission = servicecharge - agentServiceCharge;
            $("input[name='service_charge_agent']").val(agentServiceCharge);
        }
        if (sendingAmount >= threshold ) {
            agentServiceCharge =   maxCommission ;
            agentServiceCommission =servicecharge - agentServiceCharge;
            $("input[name='service_charge_agent']").val(agentServiceCharge);
        }
        totalCommission = (parseFloat(agentServiceCommission) + parseFloat(commission)).toFixed(2);
        $("input[name='agent_service_charge_commission']").val(agentServiceCommission);
        $("input[name='commission_agent_total']").val(totalCommission);

    }
</script>
