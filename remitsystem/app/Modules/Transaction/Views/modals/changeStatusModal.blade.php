<div class="modal-header">
                <h4 class="modal-title">Change Status</h4>
            </div>
            <form method="post"
                  action="{{route('sender.changeStatus', [$sender->sender_id])}}"
                  id="change-status-sender">
                {{ csrf_field() }}
                <div class="modal-body">
                    <select class="form-control" name="status_id">
                        @foreach($sender_status as $status)
                            <option value="{{$status->id}}"{{($sender->sender_status_id==$status->id)? 'selected':''}} >{{$status->name}}</option>
                        @endforeach

                    </select>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="change-status-popup">Apply</button>
                </div>
                <input type="hidden" name="type" value="modal">
            </form>

