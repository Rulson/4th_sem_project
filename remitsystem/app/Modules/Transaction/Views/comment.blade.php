@foreach ($comments as $comments)


<li class="timeline-inverted">
    <div class="timeline-badge warning"><i class="fa fa-envelope"></i></div>
    <div class="timeline-panel">
        <div class="timeline-heading">
            <h4 class="timeline-title">{{get_user_name($comments->added_by)}}</h4>
            <small class="text-muted"><i class="fa fa-clock-o"></i>{{get_notification_format($comments['created_at'])}} | {{format_date($comments->created_at)}}  </small>
        </div>
        <div class="timeline-body">
            <p>{{$comments->comment}}</p>
        </div>
    </div>
</li>

@endforeach