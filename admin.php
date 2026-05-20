<?php
include 'db.php';



if(isset($_GET['approve'])){

    $id = $_GET['approve'];

    mysqli_query(
        $conn,
        "UPDATE requests
        SET status='Approved'
        WHERE id='$id'"
    );
}



if(isset($_GET['reject'])){

    $id = $_GET['reject'];

    mysqli_query(
        $conn,
        "UPDATE requests
        SET status='Rejected'
        WHERE id='$id'"
    );
}



$result = mysqli_query(
    $conn,

    "SELECT * FROM requests

    ORDER BY
    priority_score DESC,
    id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<style>

body{
    font-family:Arial;
    background:#f4f4f4;
}

table{

    width:100%;
    border-collapse:collapse;
    background:white;
}

th, td{

    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

th{

    background:#007bff;
    color:white;
}

a{

    text-decoration:none;
    color:white;
    padding:5px 10px;
}

.approve{
    background:green;
}

.reject{
    background:red;
}

.view{
    background:#007bff;
}

</style>

</head>

<body>

<h2 align="center">



</h2>

<table>

<tr>

<th>ID</th>

<th>Full Name</th>

<th>Request Type</th>

<th>Priority Rank</th>

<th>File</th>

<th>Status</th>

<th>Action</th>

</tr>

<?php while($row =
mysqli_fetch_assoc($result)){ ?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php echo $row['fullname']; ?>

</td>

<td>

<?php echo $row['request_type']; ?>

</td>

<td>

<?php echo $row['priority_score']; ?>

</td>

<td>

<a class="view"
href="<?php echo $row['document_path']; ?>"
target="_blank">



</a>

</td>

<td>

<?php echo $row['status']; ?>

</td>

<td>

<a class="approve"
href="?approve=<?php echo $row['id']; ?>">

Approve

</a>

<a class="reject"
href="?reject=<?php echo $row['id']; ?>">

Reject

</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>