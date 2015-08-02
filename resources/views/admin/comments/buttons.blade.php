<div class="btn-group comment-{{ $comment->id }}-status">
    @foreach ([
        'ham' => ['OK', 'success'],
        'spam' => ['Spam', 'danger'],
        'unvalidated' => ['Unvalidiert', 'warning']
    ] as $possible_comment_status => $comment_status_title)
        @if ($comment->status === $possible_comment_status)
            <a onclick="updateCommentStatus({{ $comment->id }}, '{{ $comment->status }}')"
               class="btn btn-sm btn-{{ $comment_status_title[1] }} active {{ $comment->status }}"
               aria-pressed="true">
                {{ $comment_status_title[0] }}
            </a>
        @else
            <a onclick="updateCommentStatus({{ $comment->id }}, '{{ $comment->status }}')"
               class="btn btn-sm btn-{{ $comment_status_title[1] }} {{ $comment->status }}">
                {{ $comment_status_title[0] }}
            </a>
        @endif
    @endforeach
</div>
