
<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

// Fetch all registered applications

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $conn->prepare(
        "SELECT * FROM applications
         WHERE full_name LIKE ? OR email LIKE ?
         ORDER BY id DESC"
    );

    $term = "%" . $search . "%";
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    $result = $conn->query(
        "SELECT * FROM applications ORDER BY id DESC"
    );
}

$totalApplications = $result->num_rows;


if (!$result) {
    die("Error fetching applications: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>


   
<style>
     /* Action links */
td a {
    display: inline-block;
    padding: 6px 12px;
    margin-right: 5px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    background-color: #2563eb;
}

td a:hover {
    opacity: 0.85;
}
        
        /* Search form styling */
form {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

form input[type="text"] {
    padding: 10px;
    width: 280px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

form button {
    padding: 10px 20px;
    background-color: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

form button:hover {
    background-color: #1d4ed8;
}
    body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px;
        }

        h1 {
            color: #1e293b;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px #ddd;
        }

        .number {
            font-size: 35px;
            color: #2563eb;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #2563eb;
            color: white;
        }

        tr:hover {
            background: #f1f5f9;
        }
.home-btn {
    display: inline-block;
    padding: 10px 18px;
    margin-bottom: 20px;
    background-color: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 15px;
}

.home-btn:hover {
    background-color: #1d4ed8;
}
.clear-btn {
    display: inline-block;
    padding: 10px 18px;
    margin-left: 5px;
    background-color: #64748b;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 15px;
}

.clear-btn:hover {
    background-color: #475569;
}
    </style>
</head>

<body>

    <h1>Admin Dashboard</h1>
    <a href="export.php" class="home-btn">Export Applications (CSV)</a>
    <a href="index.php" class="home-btn">Go to Registration Form</a>
    <p>Welcome to the Registration Management System.</p>

    <div class="card">
        <h2>Total Applications</h2>
        <div class="number">
            <?php echo $totalApplications; ?>
        </div>
    </div>

    <div class="card">
        <h2>Registered Students</h2>
        <a href="export.php" class="home-btn">Download Applications (CSV)</a>
        <form method="GET" action="admin.php">
    <input
    type="text"
    name="search"
    placeholder="Search by name or email..."
    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
>
    <button type="submit">Search</button>
    <a href="admin.php" class="clear-btn">Clear</a>
</form>

        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['course']); ?></td>
                <td><?php echo htmlspecialchars($row['dob'] ?? ''); ?></td>
<td><?php echo htmlspecialchars($row['gender'] ?? ''); ?></td>
<td><?php echo htmlspecialchars($row['address'] ?? ''); ?></td>
<td>
    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
    |
     



<form action="delete.php" method="POST" style="display:inline;"
      onsubmit="return confirm('Are you sure you want to delete this student?');">

    <input type="hidden" name="id"
           value="<?php echo (int)$row['id']; ?>">

    <button type="submit">Delete</button>
</form>
       
</td>
            </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows === 0): ?>
    <tr>
        <td colspan="9">No applications found.</td>
    </tr>
<?php endif; ?>

        </table>
    </div>

</body>
</html>
