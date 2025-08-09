<div class="wp-block property list">
                <div class="wp-block-body">
                    <div class="wp-block-img">
                        <a href="#">
                            <img src="{{ $photo }}" alt="">
                        </a>
                    </div>
                    <div class="wp-block-content">
                        <small>
                            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>{{ $date }}</small>
                        <h4 class="content-title">{{ $name }}</h4>
                        <p class="description">{{ $body }}</p>
                        <span class="pull-left">
                            <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>  
                            <a class="text-blue-500 hover:underline" href="{{ route('news.edit', ['news' => $news]) }}">Редактировать</a>
                        </span>
                        <span class="pull-right">
              <span class="capacity">
                <i class="fa fa-user"></i> {{ $author }}
              </span>
            </span>
                    </div>
                </div>
                <div class="wp-block-footer">
                    <ul class="aux-info">
                        <li>
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> 
                            <a href="{{ route('news.show', ['news' => $newsId]) }}">Просмотреть</a>
                        </li>
                        <li><span class=" glyphicon glyphicon-comment" aria-hidden="true"></span> 5</li>
                        <li><span class="glyphicon glyphicon-star" aria-hidden="true"></span> 2</li>
                        <li><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> +5 <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></li>
                        <li><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> <a href="{{ route('news.show', ['news' => $newsId]) }}">{{ $name }}</a></li>
                    </ul>
                </div>
            </div>

