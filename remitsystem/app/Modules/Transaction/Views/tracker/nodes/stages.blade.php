<div class="animated fadeIn">
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                @if(Auth::user()->level_id != 4)
                    <div class="stage {{ request()->segment(3) == 'unconfirmed' ? 'active' : '' }}">
                        <div class="stage_inner">
                            <a href="{{route('transactions.tracker.unconfirmed')}}" data-toggle="tooltip" title="Unconfirmed ({{$transaction_count['unconfirmed']}})" class="text-white">Unconfirmed ({{$transaction_count['unconfirmed']}})</a>
                        </div>
                    </div>

                    <div class="stage {{ request()->segment(3) == 'confirmed' ? 'active' : '' }}">
                        <div class="stage_inner">
                            <a href="{{route('transactions.tracker.confirmed')}}" data-toggle="tooltip" title="Confirmed ({{$transaction_count['confirmed']}})">Confirmed ({{$transaction_count['confirmed']}})</a>
                        </div>
                    </div>
                @endif
                <div class="stage {{ request()->segment(3) == 'send-for-collection' ? 'active' : '' }}">
                    <div class="stage_inner">
                        <a href="{{route('transactions.tracker.sendForCollection')}}" data-toggle="tooltip" title="Send for collection ({{$transaction_count['send_for_collection']}})">Send for collection ({{$transaction_count['send_for_collection']}})</a>
                    </div>
                </div>
                <div class="stage {{ request()->segment(3) == 'payment-in-progress' ? 'active' : '' }}">
                    <div class="stage_inner">
                        <a href="{{route('transactions.tracker.paymentInProgress')}}" data-toggle="tooltip" title="Payment in Progress({{$transaction_count['payment_in_progress']}})">Payment in Progress ({{$transaction_count['payment_in_progress']}})</a>
                    </div>
                </div>
                @if(Auth::user()->level_id != 4)
                    <div class="stage {{ request()->segment(3) == 'delivered' ? 'active' : '' }}">
                        <div class="stage_inner">
                            <a href="{{route('transactions.tracker.delivered')}}" data-toggle="tooltip" title="Delivered ({{$transaction_count['delivered']}})">Delivered ({{$transaction_count['delivered']}})</a>
                        </div>
                    </div>
                @endif
                <div class="stage {{ request()->segment(3) == 'cancelled' ? 'active' : '' }}">
                    <div class="stage_inner">
                        <a href="{{route('transactions.tracker.cancelled')}}" data-toggle="tooltip" title="Cancelled ({{$transaction_count['cancelled']}})"> Cancelled ({{$transaction_count['cancelled']}})</a>
                    </div>
                </div>
                <div class="stage {{ request()->segment(3) == 'on-hold' ? 'active' : '' }}">
                    <div class="stage_inner">
                        <a href="{{route('transactions.tracker.onHold')}}" data-toggle="tooltip" title="On Hold ({{$transaction_count['hold_on']}})">On hold ({{$transaction_count['hold_on']}})</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

    </div>
