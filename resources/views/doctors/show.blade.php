
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $title }}</h2>
                    <p>{{ $pageHeader }}</p>
                </div>
                <div class="card-body">
                    <div class="doctor-info">
                        <h3>{{ $doctor->name }} ({{ $doctor->specialty }})</h3>
                        <p>График работы:</p>
                        <ul>
                            @foreach($doctor->working_hours as $day => $hours)
                                <li>{{ ucfirst($day) }}: {{ $hours['start'] ?? 'выходной' }} - {{ $hours['end'] ?? 'выходной' }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @auth
                    <div class="add-comment">
                        <h4>Оставить комментарий</h4>
                        <form action="{{ route('doctors.comments.store', $doctor->id) }}"
                              method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea name="content"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Ваш комментарий"
                                          required></textarea>
                            </div>
                            <button type="submit"
                                    class="btn btn-primary">
                                Отправить
                            </button>
                        </form>
                    </div>
                    @endauth

                    <div class="comments-list">
                        <h4>Комментарии ({{ $comments->total() }})</h4>
                        @foreach($comments as $comment)
                            <div class="comment-item">
                                <div class="comment-header">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <span class="comment-date">
                                        {{ $comment->created_at->format('d.m.Y H:i') }}
                                    </span>
                                </div>
                                <div class="comment-content">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @endforeach
                        <div class="pagination-links">
                            {{ $comments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
