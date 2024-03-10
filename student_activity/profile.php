<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}
require 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Student Activity</title>
    <style>
      html,
      body {
        height: 100%;
      }

      .form-signin {
        max-width: 330px;
        padding: 1rem;
      }

      .form-signin .form-floating:focus-within {
        z-index: 2;
      }

      .form-signin input, .form-signin select {
        margin-bottom: -1px;
        border-radius: 0;
      }

      .form-signin input[name='id'] {
        border-top-right-radius: 6px;
        border-top-left-radius: 6px;
      }

      #floatingDepartment{
        margin-bottom: 10px;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
      }
    </style>
    <script>
      function confirmPassword() {
        let p1 = document.getElementById('floatingPassword').value;
        let p2 = document.getElementById('floatingPassword2').value;
        if (p1 != p2) {
          alert('Password not match.');
          event.preventDefault();
        }
      }
      function changeFaculty() {
        let id = document.getElementById('floatingFaculty').value;
        fetch('department.php?id='+id)
        .then(resp=>resp.json())
        .then(date=>{
          let departments =document.getElementById('floatingDepartment');
          let option = '';
          for (let dep of date ) {
            option += `<option value ="${dep.id}">${dep.name}</option>`;
          }
          departments.innerHTML = option;
        });
      }
    </script>
</head>
<body>
<div class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
      <form method="post" onsubmit="confirmPassword()">
        <!-- <img class="mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
        <h1 class="h3 mb-3 fw-normal">Please register</h1>
        <?php
if (isset($error)) {
  echo "<div class='alert alert-danger'>$error</div>";
}
?>    
        <div class="form-floating">
          <input name="id" type="text" class="form-control" 
          id="floatingID" placeholder="Student ID" required>
          <label for="floatingID">Student ID</label>
        </div>
        <div class="form-floating">
          <input name="fullname" type="text" class="form-control" 
          id="floatingName" placeholder="Fullname" required>
          <label for="floatingName">Fullname</label>
        </div>
        <div class="form-floating">
          <input name="email" type="email" class="form-control" 
          id="floatingEmail" placeholder="Email address" required>
          <label for="floatingEmail">Email address</label>
        </div>
        <div class="form-floating">
          <select class="form-select" 
          id="floatingFaculty" placeholder="Faculty" onchange="changeFaculty()">
          <?php
$sql = 'select * from faculties order by id ';
$result = $conn -> query($sql);
while ($row = $result -> fetch_assoc()) {
    echo "<option value =' {$row['id']}'>{$row['name']}</option>";
}
?>
            </select>
          <label for="floatingFaculty">Faculty</label>
        </div>
        <div class="form-floating">
          <select name="dep_id" class="form-select" 
          id="floatingDepartment" placeholder="Department">
<?php
$sql = 'select id,name from departments where fac_id=(
        select min(id) from faculties
        )';
        $result = $conn-> query($sql);
        while ($row = $result->fetch_assoc()) {
          echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
$conn->close();
?>
          </select>
          <label for="floatingDepartment">Department</label>
        </div>
        <div class="form-floating">
          <input name="password" type="password" class="form-control" 
          id="floatingPassword" placeholder="Password" required>
          <label for="floatingPassword">Password</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" 
          id="floatingPassword2" placeholder="Confirm Password" required>
          <label for="floatingPassword2">Confirm Password</label>
        </div>
    
        <button name="submit" class="btn btn-primary w-100 py-2" type="submit">Sign up</button>
        <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account? <a href="index.php" class="link-danger">Sign in</a></p>
        <!-- <p class="mt-5 mb-3 text-body-secondary">© 2017–2023</p> -->
      </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<div class="container">
<?php
$sql = "select S.id, fullname, email, pic, dep_id, fac_id from  
join departments D on S.dep_id=D.id 
where id='{$_SESSION['user']['id']}'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
</div>
</body>
</html>
