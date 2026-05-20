<?php
include 'db.php';

if(isset($_POST['submit'])){

    $fullname = $_POST['fullname'];

    $request_type = $_POST['request_type'];

    

    $command = "python ml/predict.py \"$request_type\"";

    $priority = shell_exec($command);

    $priority = floatval($priority);

    

    if(!file_exists('uploads')){

        mkdir('uploads');
    }

    // FILE INFO

    $filename = $_FILES['document']['name'];

    $tempname = $_FILES['document']['tmp_name'];

    

    $newname = time() . "_" . $filename;

    // DESTINATION

    $folder = "uploads/" . $newname;

    // MOVE FILE

    if(move_uploaded_file($tempname,$folder)){

      

        $sql = "INSERT INTO requests
        (
            fullname,
            request_type,
            priority_score,
            document_path
        )

        VALUES
        (
            '$fullname',
            '$request_type',
            '$priority',
            '$folder'
        )";

        mysqli_query($conn,$sql);

        echo "

        <div class='success'>

            <h2>
                Request Submitted Successfully
            </h2>

            <p>
                ML Priority Score:
                <b>$priority</b>
            </p>

            <p>
                Status:
                <b>Pending</b>
            </p>

        </div>

        ";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Barangay Connect</title>

<style>

body{
    font-family:Arial;
    background:#f4f4f4;
}

.container{
    width:400px;
    margin:auto;
    margin-top:50px;
    background:white;
    padding:30px;
    border-radius:10px;
}

input, select{

    width:100%;
    padding:10px;
    margin-top:10px;
}

button{

    width:100%;
    padding:12px;
    margin-top:20px;
    background:#007bff;
    color:white;
    border:none;
    cursor:pointer;
}

.success{

    width:400px;
    margin:auto;
    margin-top:20px;
    background:#d4edda;
    padding:20px;
    border-radius:10px;
    text-align:center;
}

.table-container{

    width:90%;
    margin:auto;
    margin-top:40px;
    background:white;
    padding:20px;
    border-radius:10px;
}

table{

    width:100%;
    border-collapse:collapse;
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

.approved{

    color:green;
    font-weight:bold;
}

.rejected{

    color:red;
    font-weight:bold;
}

.pending{

    color:orange;
    font-weight:bold;
}

.view-btn{

    background:#007bff;
    color:white;
    padding:5px 10px;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h2>Barangay Connect</h2>

<form method="POST"
enctype="multipart/form-data">

    <input type="text"
    name="fullname"
    placeholder="Full Name"
    required>

    <select name="request_type">

        <option value="Emergency">
            Emergency
        </option>

        <option value="Medical">
            Medical
        </option>

        <option value="Senior">
            Senior
        </option>

        <option value="Clearance">
            Clearance
        </option>

    </select>

    <input type="file"
    name="document"
    required>

    <button type="submit"
    name="submit">

        Submit Request

    </button>

</form>

</div>

<?php



$result = mysqli_query(
    $conn,
    "SELECT * FROM requests
    ORDER BY id DESC"
);

?>

<div class="table-container">

<h2 align="center">
My Requests
</h2>

<table>

<tr>

<th>ID</th>

<th>Full Name</th>

<th>Request Type</th>

<th>Priority</th>

<th>File</th>

<th>Status</th>

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

<a class="view-btn"
href="<?php echo $row['document_path']; ?>"
target="_blank">



</a>

</td>

<td>

<?php

$status = $row['status'];

if($status == "Approved"){

    echo "<span class='approved'>
    Approved
    </span>";

}
elseif($status == "Rejected"){

    echo "<span class='rejected'>
    Rejected
    </span>";

}
else{

    echo "<span class='pending'>
    Pending
    </span>";
}

?>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>