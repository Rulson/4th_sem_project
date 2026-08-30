<style>
    .stage:hover .stage_inner,
    .stage.active .stage_inner {
        background: #f39c12;
    }

    @media (max-width: 575.98px) {
        .stage {
            position: relative;

        }

        .stage_inner {
            color: white;
            text-align: center;

            height: 25px;
            background: #3c8dbc;
            padding: 0 10px;
            margin-bottom: 10px;
            line-height: 25px;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

        }

        .stage:hover:after,
        .stage.active:after {
            border-top-color: #f39c12;
        }

        .stage:hover:before,
        .stage.active:before {
            border-left-color: #f39c12;
        }

        .stage:after {
            content: '';
            display: block;
            border-top: 8px solid #3c8dbc;
            border-left: 8px solid rgba(0, 0, 0, 0);

            position: absolute;
            bottom: -8px;
            left: 50%;
            margin-left: -8px;
            z-index: 99
        }

        .stage:before {
            content: '';
            display: block;
            border-bottom: 8px solid rgba(0, 0, 0, 0);
            border-left: 8px solid #3c8dbc;
            position: absolute;
            bottom: -8px;
            margin-right: -8px;
            right: 50%;
            z-index: 99
        }
    }

    .stage a {
        color: #fff;

    }

    .stage a:hover,
    .stage.active a {
        text-decoration: underline;

    }

    @media (min-width: 576px) {

        .stage {
            float: left;
            width: 14.28%;
            position: relative;

            display: block;
        }

        .stage_inner {
            color: white;

            height: 30px;
            background: #3c8dbc;
            padding-left: 10px;

            line-height: 30px;
            margin-right: 20px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stage:hover:after,
        .stage.active:after {
            border-left-color: #f39c12;
        }

        .stage:hover:before,
        .stage.active:before {
            border-left-color: #f39c12;
        }

        .stage:after {
            content: '';
            display: block;
            border-top: 15px solid rgba(0, 0, 0, 0);
            border-left: 15px solid #3c8dbc;

            position: absolute;
            top: 0;
            right: 5px;
            z-index: 99
        }

        .stage:before {
            content: '';
            display: block;
            border-bottom: 15px solid rgba(0, 0, 0, 0);
            border-left: 15px solid #3c8dbc;
            position: absolute;
            bottom: 0;
            right: 5px;
            z-index: 99
        }
    }

    .main-header {
        max-height: none;
    }

    .skin-blue .client-header .navbar .nav>.active>a {
        background: #1b9bff;
        color: #f6f6f6;
    }

    .skin-blue .client-header .navbar .nav li:hover>a {
        background: #1b9bff;
        color: #f6f6f6;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="stage {{ request()->segment(3) == 'unconfirmed' ? 'active' : '' }}">
            <div class="stage_inner">
                <a href="{{route('transactions.tracker.unconfirmed')}}" data-toggle="tooltip" title="Unconfirmed (4)" class="text-white">Unconfirmed (4)</a>
            </div>
        </div>

        <div class="stage {{ request()->segment(3) == 'confirmed' ? 'active' : '' }}">
            <div class="stage_inner">
                <a href="{{route('transactions.tracker.confirmed')}}" data-toggle="tooltip" title="Confirmed (4)">Confirmed (4)</a>
            </div>
        </div>
        <div class="stage {{ request()->segment(3) == 'send-for-collection' ? 'active' : '' }}">
            <div class="stage_inner">
                <a href="{{route('transactions.tracker.sendForCollection')}}" data-toggle="tooltip" title="Send for collection (4)">Send for collection (4)</a>
            </div>
        </div>
        <div class="stage {{ request()->segment(3) == 'payment-in-progress' ? 'active' : '' }}">
            <div class="stage_inner">
                <a href="{{route('transactions.tracker.paymentInProgress')}}" data-toggle="tooltip" title="Payment in Progress(4)">Payment in Progress (4)</a>
            </div>
        </div>
        <div class="stage {{ request()->segment(3) == 'onhold' ? 'active' : '' }}">
            <div class="stage_inner">
                <a href="{{route('transactions.tracker.onHold')}}" data-toggle="tooltip" title="On Hold (4)">On hold (4)</a>
            </div>
        </div>

    </div>
</div>