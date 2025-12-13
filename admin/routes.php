<?php
include '../includes/db.php';
include 'includes/header.php';

$success = "";
$error = "";

// Handle Add Route
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_route'])) {
    $bus_id = $_POST['bus_id'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $price = $_POST['price'];

    $sql = "INSERT INTO routes (bus_id, source, destination, departure_date, departure_time, price) VALUES ('$bus_id', '$source', '$destination', '$departure_date', '$departure_time', '$price')";
    if ($conn->query($sql) === TRUE) {
        $success = "Route added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM routes WHERE id=$id");
    header("Location: routes.php");
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Routes</h2>
</div>

<?php if($success) echo "<p style='color: #6AECE1; margin-bottom: 10px;'>$success</p>"; ?>
<?php if(isset($_GET['msg'])) echo "<p style='color: #6AECE1; margin-bottom: 10px;'>{$_GET['msg']}</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <h3>Add New Route</h3>
    <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div style="grid-column: span 1;">
            <label>Bus</label>
            <select name="bus_id" required>
                <?php
                $buses = $conn->query("SELECT * FROM buses");
                while ($bus = $buses->fetch_assoc()) {
                    echo "<option value='{$bus['id']}'>{$bus['bus_name']} ({$bus['bus_number']})</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label>Source</label>
            <input type="text" name="source" required>
        </div>
        <div>
            <label>Destination</label>
            <input type="text" name="destination" required>
        </div>
        <div>
            <label>Date</label>
            <input type="date" name="departure_date" required>
        </div>
        <div>
            <label>Time</label>
            <input type="time" name="departure_time" required>
        </div>
        <div>
            <label>Price (NPR)</label>
            <input type="number" name="price" required>
        </div>
        <button type="submit" name="add_route" class="btn btn-primary" style="height: 42px;">Add Route</button>
    </form>
</div>

<div class="card">
    <h3>Active Routes</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Bus</th>
                <th>Route</th>
                <th>Date/Time</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT r.*, b.bus_name, b.bus_number FROM routes r JOIN buses b ON r.bus_id = b.id ORDER BY r.departure_date DESC";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['bus_name']} ({$row['bus_number']})</td>
                    <td>{$row['source']} - {$row['destination']}</td>
                    <td>{$row['departure_date']} {$row['departure_time']}</td>
                    <td>Rs. {$row['price']}</td>
                    <td><a href='edit_route.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a> <a href='routes.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
