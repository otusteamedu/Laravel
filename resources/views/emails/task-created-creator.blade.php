<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задача создана</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 15px; text-align: center; }
        .content { padding: 20px; }
        .task-info { background: #f8f9fa; padding: 15px; margin: 15px 0; border: 1px solid #dee2e6; }
        .footer { text-align: center; color: #666; margin-top: 20px; font-size: 12px; }
        .btn { display: inline-block; padding: 8px 16px; background: #28a745; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Задача успешно создана</h1>
        </div>
        
        <div class="content">
            <p>Здравствуйте, <strong>{{ $creator->name }}</strong>!</p>
            
            <p>Ваша задача была успешно создана и назначена исполнителю <strong>{{ $executor->name }}</strong>.</p>
            
            <div class="task-info">
                <h3>{{ $task->title }}</h3>
                <p><strong>Описание:</strong> {{ $task->description }}</p>
                <p><strong>Исполнитель:</strong> {{ $executor->name }} ({{ $executor->email }})</p>
                <p><strong>Категория:</strong> {{ $category->name }}</p>
                <p><strong>Приоритет:</strong> {{ $priority->name }}</p>
                <p><strong>Статус:</strong> {{ $task->status }}</p>
                @if($task->due_date)
                    <p><strong>Срок выполнения:</strong> {{ $task->due_date }}</p>
                @endif
                <p><strong>Дата создания:</strong> {{ $task->created_at->format('d.m.Y H:i') }}</p>
            </div>
            
            <p>Исполнитель получил уведомление о назначенной задаче.</p>
            
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('tasks.show', $task->id) }}" class="btn">Просмотреть задачу</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Это автоматическое уведомление из системы управления задачами.</p>
            <p>Не отвечайте на это письмо.</p>
        </div>
    </div>
</body>
</html> 