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
    $tasks_todo = mysqli_query($db, "SELECT * FROM todo WHERE status='To Do'");
    $tasks_in_progress = mysqli_query($db, "SELECT * FROM todo WHERE status='In Progress'");
    $tasks_done = mysqli_query($db, "SELECT * FROM todo WHERE status='Done'");
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>To-Do List Application</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    </head>
    <body>
        <div class="heading">
            <h1 class="d-flex justify-content-center align-items-center">To-Do List Web Application</h1>
        </div>

        <!-- Task creation form -->
    <div class="container mb-4">
        <form class="form-floating" method="POST" action="index.php">
            <div class="row mb-3">
                <div class="col">
                    <label class="heading fs-3" for="task">Task Name</label>
                    <input type="text" id="task" name="task" class="task_input form-control fs-4" placeholder="Task Name" required>
                </div>
                <div class="col">
                    <label class="heading fs-3" for="due_date">Due Date</label> 
                    <input type="datetime-local" name="due_date" class="form-control fs-4" required> <!-- Input for due date -->
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="add_btn btn btn-primary d-flex w-25 container" name="submit">Add Task</button>
            </div>
        </form>
    </div>

    <div class="container">
<div class="row">
    <div class="col-lg-4">
        <h2 class="heading">To Do</h2>
        <table class="table table-success fs-6">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Task</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($tasks_todo)) { ?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['task'] ?></td>
                        <td <?php echo ($row['due_date'] < date('Y-m-d H:i:s', strtotime('+1 day'))) ? 'style="color: red;"' : ''; ?>><?php echo $row['due_date'] ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="dropbtn px-4">↓</button>
                                <div class="dropdown-content">
                                    <a href="edit.php?task_id=<?php echo $row['id']; ?>">Edit</a>
                                    <a href="index.php?del_task=<?php echo $row['id']; ?>">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
        <style>
.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 10px;
  font-size: 16px;
  border: none;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
</style>
            <div class="col-lg-4">
                <h2 class="heading">In Progress</h2>
                <table class="table table-success fs-6">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Task</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($tasks_in_progress)) { ?>
                            <tr>
                                <td><?php echo $row['id'] ?></td>
                                <td><?php echo $row['task'] ?></td>
                                <td <?php echo ($row['due_date'] < date('Y-m-d H:i:s', strtotime('+1 day'))) ? 'style="color: red;"' : ''; ?>><?php echo $row['due_date'] ?></td>
                                <td>
                                <div class="dropdown">
                                    <button class="dropbtn px-4">↓</button>
                                    <div class="dropdown-content">
                                        <a href="edit.php?task_id=<?php echo $row['id']; ?>">Edit</a>
                                        <a href="index.php?del_task=<?php echo $row['id']; ?>">Delete</a>
                                    </div>
                                </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <h2 class="heading">Done</h2>
                <table class="table table-success fs-6">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Task</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($tasks_done)) { ?>
                            <tr>
                                <td><?php echo $row['id'] ?></td>
                                <td><?php echo $row['task'] ?></td>
                                <td><?php echo $row['due_date'] ?></td>
                                <td>
                                <div class="dropdown">
                                    <button class="dropbtn px-4">↓</button>
                                    <div class="dropdown-content">
                                        <a href="edit.php?task_id=<?php echo $row['id']; ?>">Edit</a>
                                        <a href="index.php?del_task=<?php echo $row['id']; ?>">Delete</a>
                                    </div>
                                </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </body>
    </html>

