<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Assignment</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fafbfc;
            margin: 0;
        }
        .modal {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.10);
            padding: 32px 32px 24px 32px;
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #888;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 6px;
        }
        select, input, textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: #fafbfc;
            margin-bottom: 2px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 70px;
            resize: vertical;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }
        .btn {
            padding: 8px 24px;
            border-radius: 6px;
            border: none;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-cancel {
            background: #e0e0e0;
            color: #444;
        }
        .btn-create {
            background: #1976d2;
            color: #fff;
        }
        .btn-create:hover {
            background: #1565c0;
        }
        .btn-cancel:hover {
            background: #bdbdbd;
        }
        .date-group {
            position: relative;
        }
        .date-group input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
        }
    </style>
</head>
<body>
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">+ Create Assignment</span>
            <button class="close-btn" onclick="window.close && window.close()">&times;</button>
        </div>
        <form id="assignment-form">
            <div class="form-group">
                <label for="course">Course</label>
                <select id="course" name="course" required>
                    <option value="">Select course</option>
                    <option value="ai-genesis">AI Genesis</option>
                    <option value="web-dev">Web Development</option>
                    <!-- Thêm các khóa học khác nếu cần -->
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required />
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group date-group">
                <label for="due_date">Due date</label>
                <input type="datetime-local" id="due_date" name="due_date" placeholder="dd/mm/yyyy --:--" />
            </div>
            <div class="form-group">
                <label for="max_points">Max points</label>
                <input type="number" id="max_points" name="max_points" value="100" min="0" required />
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="window.close && window.close()">Cancel</button>
                <button type="submit" class="btn btn-create">Create</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('assignment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Assignment created!');
            // Xử lý lưu assignment ở đây
        });
    </script>
</body>
</html>
