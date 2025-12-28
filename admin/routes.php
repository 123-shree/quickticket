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

    // Check for Daily Routine
    if (isset($_POST['is_daily']) && !empty($_POST['end_date'])) {
        $end_date = $_POST['end_date'];
        
        // If daily routine is checked, allow departure_date to be optional (Default to Today)
        if (empty($departure_date)) {
            $departure_date = date('Y-m-d');
        }
        
        $current_date = strtotime($departure_date);
        $final_date = strtotime($end_date);

        $count = 0;
        $error_count = 0;

        while ($current_date <= $final_date) {
            $date_str = date('Y-m-d', $current_date);
            $sql = "INSERT INTO routes (bus_id, source, destination, departure_date, departure_time, price) VALUES ('$bus_id', '$source', '$destination', '$date_str', '$departure_time', '$price')";
            
            if ($conn->query($sql) === TRUE) {
                $count++;
            } else {
                $error_count++;
            }
            // Add 1 day
            $current_date = strtotime('+1 day', $current_date);
        }

        if ($count > 0) {
            $success = "$count routes added as daily routine successfully!";
            if ($error_count > 0) $error .= " ($error_count failed)";
        } else {
            $error = "Failed to add daily routes: " . $conn->error;
        }

    } else {
        // Single Route - Date is Mandatory here
        if (empty($departure_date)) {
            $error = "Departure date is required.";
        } else {
            $sql = "INSERT INTO routes (bus_id, source, destination, departure_date, departure_time, price) VALUES ('$bus_id', '$source', '$destination', '$departure_date', '$departure_time', '$price')";
            if ($conn->query($sql) === TRUE) {
                $success = "Route added successfully!";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
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
            <label id="date_label">Date</label>
            <input type="date" name="departure_date" id="departure_date" required>
        </div>
        <div style="grid-column: span 2; display: flex; align-items: center; gap: 10px; background: #f8f9fa; padding: 10px; border-radius: 6px;">
            <input type="checkbox" name="is_daily" id="is_daily" onclick="toggleEndDate()">
            <label for="is_daily" style="margin: 0; cursor: pointer;">Daily Routine (Repeat)</label>
            
            <div id="end_date_div" style="display: none; margin-left: 15px; align-items: center; gap: 10px;">
                <label style="margin: 0;">Until:</label>
                <input type="date" name="end_date" id="end_date">
            </div>
        </div>
        <script>
            function toggleEndDate() {
                var checkBox = document.getElementById("is_daily");
                var endDiv = document.getElementById("end_date_div");
                var endDateInput = document.getElementById("end_date");
                var startDateInput = document.getElementById("departure_date");
                var dateLabel = document.getElementById("date_label");

                if (checkBox.checked == true){
                    endDiv.style.display = "flex";
                    endDateInput.required = true;     // Make End Date mandatory
                    startDateInput.required = false;  // Make Start Date optional
                    dateLabel.innerHTML = "Start Date (Optional)";
                } else {
                    endDiv.style.display = "none";
                    endDateInput.required = false;
                    startDateInput.required = true;   // Make Start Date mandatory again
                    dateLabel.innerHTML = "Date";
                }
            }
        </script>
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
    
    <!-- Search / Filter -->
    <form method="GET" style="display: flex; gap: 10px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" list="bus_suggestions" placeholder="Search Bus ID, Name, Source..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 250px;">
        <datalist id="bus_suggestions">
            <?php
            // Fetch unique bus names, sources, and destinations for suggestions
            $suggestion_sql = "SELECT DISTINCT bus_name FROM buses UNION SELECT DISTINCT source FROM routes UNION SELECT DISTINCT destination FROM routes";
            $suggestion_result = $conn->query($suggestion_sql);
            while($s_row = $suggestion_result->fetch_array()) {
                echo "<option value='{$s_row[0]}'>";
            }
            ?>
        </datalist>
        
        <input type="date" name="date_filter" value="<?php echo isset($_GET['date_filter']) ? $_GET['date_filter'] : ''; ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        
        <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">Filter</button>
        
        <!-- Quick Filters -->
        <div style="display: flex; gap: 5px; margin-left: 10px;">
            <button type="submit" name="view" value="daily" title="Daily View (Today)" style="background: #e3f2fd; border: 1px solid #90caf9; color: #1976d2; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-calendar-day"></i> Daily
            </button>
            <button type="submit" name="view" value="weekly" title="Weekly View (Next 7 Days)" style="background: #f3e5f5; border: 1px solid #ce93d8; color: #7b1fa2; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-calendar-week"></i> Weekly
            </button>
        </div>

        <?php if(isset($_GET['search']) || isset($_GET['date_filter']) || isset($_GET['view'])): ?>
            <a href="routes.php" style="color: red; margin-left: 10px; text-decoration: none;">Clear</a>
        <?php endif; ?>
    </form>

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
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
            $view_filter = isset($_GET['view']) ? $_GET['view'] : '';

            $sql = "SELECT r.*, b.bus_name, b.bus_number FROM routes r JOIN buses b ON r.bus_id = b.id WHERE 1=1";
            
            if ($search) {
                $sql .= " AND (b.bus_name LIKE '%$search%' OR r.source LIKE '%$search%' OR r.destination LIKE '%$search%')";
                // User Request: If searching by name, show ALL dates (ignore filters) so admin sees the full schedule.
            } else {
                // Only apply date filters if NOT searching
                if ($view_filter == 'daily') {
                    $today = date('Y-m-d');
                    $sql .= " AND r.departure_date = '$today'";
                } elseif ($view_filter == 'weekly') {
                    $today = date('Y-m-d');
                    $next_week = date('Y-m-d', strtotime('+7 days'));
                    $sql .= " AND r.departure_date BETWEEN '$today' AND '$next_week'";
                } elseif ($date_filter) {
                    $sql .= " AND r.departure_date = '$date_filter'";
                }
            }
            
            $sql .= " ORDER BY r.departure_date DESC";

            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['bus_name']} ({$row['bus_number']})</td>
                    <td>{$row['source']} - {$row['destination']}</td>
                    <td>{$row['departure_date']} " . date('h:i A', strtotime($row['departure_time'])) . "</td>
                    <td>Rs. {$row['price']}</td>
                    <td><a href='edit_route.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a> <a href='routes.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
