<?php
$db = mysqli_connect("localhost", "root", "jithingeorgejose123", "todo_db");

if(isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $task_query = mysqli_query($db, "SELECT * FROM todo WHERE id='$task_id'");
    $task = mysqli_fetch_assoc($task_query);
}

if(isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status']; // Added status field

    mysqli_query($db, "UPDATE todo SET task='$task_name', due_date='$due_date', status='$status' WHERE id='$task_id'");
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center heading">Edit Task</h2>
        <form method="POST">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            <div class="mb-3">
                <label for="task" class="form-label heading fs-3">Task Name</label>
                <input type="text" class="form-control" id="task" name="task" value="<?php echo $task['task']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label heading fs-3">Due Date</label>
                <input type="datetime-local" class="form-control" id="due_date" name="due_date" value="<?php echo date('Y-m-d\TH:i', strtotime($task['due_date'])); ?>" required>
            </div>
            <!-- Dropdown menu for task status -->
            <div class="mb-3">
                <label for="status" class="form-label heading fs-3">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="TO DO" <?php if($task['status'] == 'TO DO') echo 'selected'; ?>>TO DO</option>
                    <option value="IN PROGRESS" <?php if($task['status'] == 'IN PROGRESS') echo 'selected'; ?>>IN PROGRESS</option>
                    <option value="DONE" <?php if($task['status'] == 'DONE') echo 'selected'; ?>>DONE</option>
                </select>
            </div>
            <!-- End of dropdown menu -->
            <button type="submit" class="btn btn-primary" name="update_task">Update Task</button>
        </form>
    </div>
</body>
</html>
