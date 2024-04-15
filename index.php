<?php
$db = mysqli_connect("localhost", "root", "jithingeorgejose123", "todo_db");

// Handle task creation
if(isset($_POST['submit'])){
    $task = $_POST['task'];
    $dueDate = $_POST['due_date']; // Add due date input

    // Add due_date field to the SQL query
    mysqli_query($db, "INSERT INTO todo (task, due_date, status) VALUES ('$task', '$dueDate', 'To Do')");
    header('location: index.php');
}

if(isset($_GET['del_task'])){
  $id = $_GET['del_task'];
  mysqli_query($db, "DELETE FROM todo WHERE id ='$id'");
  header('location: index.php');
}

// Handle status update
if(isset($_POST['update_status'])){
    $task_id = $_POST['task_id'];
    $new_status = $_POST['new_status'];

    // Update the status of the task in the database
    mysqli_query($db, "UPDATE todo SET status='$new_status' WHERE id=$task_id");
    header('location: index.php');
}

// Fetch tasks from the database
$tasks = mysqli_query($db, "SELECT * FROM todo");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="heading">
        <h1 class="d-flex justify-content-center align-items-center">To-Do List Web Application</h1>
    </div>

    <!-- Task creation form -->
<!-- Task creation form -->
<div class="container">
    <form class="form-floating" method="POST" action="index.php">
        <div class="row mb-3">
            <div class="col">
                <label class="heading fs-3" for="task">Task Name</label>
                <input type="text" id="task" name="task" class="task_input form-control fs-4" placeholder="Task Name" required>
            </div>
            <div class="col">
                <label class="heading fs-3" for="due_date">Due Date</label> 
                <input type="datetime-local" name="due_date" class="form-control fs-4" required> <!-- Input for due date -->
            </div> <br>
        </div>
        <div class="col-auto">
                <button type="submit" class="add_btn btn btn-primary d-flex w-25 container" name="submit">Add Task</button>
            </div>
    </form>
</div>



    <!-- Task management table -->
   <!-- Task management table -->
<div class="table_form w-75 container">
    <table class="table table-success fs-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Task</th>
                <th scope="col">Due Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($tasks)) { ?>
                <tr <?php echo isDueDateClose($row['due_date'], $row['status']) ? 'class="table-danger"' : ''; ?>>
                    <td><?php echo $row['id'] ?></td>
                    <td>
                        <span class="task-text"><?php echo $row['task'] ?></span>
                        <input type="text" class="form-control task-input" value="<?php echo $row['task'] ?>" style="display: none;">
                    </td>
                    <td>
                        <span class="due-date-text"><?php echo $row['due_date'] ?></span>
                        <input type="datetime-local" class="form-control due-date-input" value="<?php echo date('Y-m-d\TH:i', strtotime($row['due_date'])) ?>" style="display: none;">
                    </td>
                    <td>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status" class="form-control" onchange="this.form.submit()">
                                <option value="To Do" <?php if($row['status'] == 'To Do') echo 'selected'; ?>>To Do</option>
                                <option value="In Progress" <?php if($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                <option value="Done" <?php if($row['status'] == 'Done') echo 'selected'; ?>>Done</option>
                            </select>
                            <input type="hidden" name="update_status">
                        </form>
                    </td>
                    <td>
                        <div class="d-flex">
                            <button class="btn btn-primary w-50 me-2 edit-btn">Edit</button>
                            <button class="btn btn-success w-50 me-2 save-btn" style="display: none;">Save</button>
                            <button class="btn btn-danger w-50"><a href="index.php?del_task=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">Delete</a></button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    // Add event listeners to edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const taskText = row.querySelector('.task-text');
            const dueDateText = row.querySelector('.due-date-text');
            const taskInput = row.querySelector('.task-input');
            const dueDateInput = row.querySelector('.due-date-input');
            const saveBtn = row.querySelector('.save-btn');

            // Toggle display of input fields and text spans
            if (taskText.style.display === 'none') {
                taskText.style.display = 'inline';
                dueDateText.style.display = 'inline';
                taskInput.style.display = 'none';
                dueDateInput.style.display = 'none';
                btn.innerText = 'Edit';
                saveBtn.style.display = 'none';
            } else {
                taskText.style.display = 'none';
                dueDateText.style.display = 'none';
                taskInput.style.display = 'inline';
                dueDateInput.style.display = 'inline';
                btn.innerText = 'Cancel';
                saveBtn.style.display = 'inline';
            }
        });
    });

    // Add event listeners to save buttons
    document.querySelectorAll('.save-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const taskId = row.querySelector('[name="task_id"]').value;
            const taskInput = row.querySelector('.task-input');
            const dueDateInput = row.querySelector('.due-date-input');

            // Submit form with updated task name and due date
            row.querySelector('.task-text').innerText = taskInput.value;
            row.querySelector('.due-date-text').innerText = dueDateInput.value;
            row.querySelector('.edit-btn').innerText = 'Edit';
            row.querySelector('.edit-btn').click(); // Click the edit button to toggle back to plain text view
        });
    });
</script>

</body>
</html>

<?php
// Function to check if due date is close
function isDueDateClose($due_date, $status) {
    // Check if the status is "Done"
    if ($status === 'Done') {
        return false; // Turn off the indication
    }

    // Check if due_date is not empty
    if ($due_date !== null) {
        $threshold = 24 * 60 * 60; // 24 hours in seconds
        $due_timestamp = strtotime($due_date);
        $current_timestamp = time();

        // Calculate the difference in seconds
        $difference = $due_timestamp - $current_timestamp;

        // Return true if the difference is less than the threshold
        return $difference <= $threshold && $difference > 0;
    } else {
        // If due_date is empty, return false (not close)
        return false;
    }
}
?>

