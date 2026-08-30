
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Add Comment</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

 {!!Form::open(array('route' => ['transaction.comment.store',$transaction_id], 'id' => 'comment-form','method' => 'post'))!!}
<div class="modal-body">
    <div class="col-md-12">
        <div class="row form-group">
            {{ Form::text('comment',null,['class' => 'form-control','id'=>'comment-value', 'placeholder' => 'Add Comment','required']) }}
        </div>  <div class="row">
            <label>Notify &nbsp;</label>

           @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)

                    <div id="agent-checkbox" class="checkbox">
                        <label>
                            <input type="checkbox" name="agent_user_id" id="agent_id_value" value="">
                            <Strong id="agentname">Agent</Strong>
                        </label>
                    </div>&nbsp;
                <div id="client-checkbox" class="checkbox">
                        <label>
                            <input type="checkbox" name="client_user_id" id="client_id_value" value="">
                            <Strong id="clientname">Client</Strong>
                        </label>
                    </div> &nbsp;


            @endif
           <div  class="checkbox">
                <label>
                    <input type="checkbox" name="admin" value="admin" checked>
                    <Strong >Admin</Strong>
                </label>
            </div>

        </div>

    </div>
    <button type="submit" id="save-button" class="btn btn-primary pull-right">Comment</button>
    <div >
        <div class="container">
            <ul class="timeline" id="comments">
            </ul>
        </div>
    </div>


</div>
{!!Form::close()!!}

